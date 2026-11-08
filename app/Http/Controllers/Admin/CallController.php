<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCallNotificationJob;
use App\Models\Call;
use App\Models\CallList;
use App\Models\Process;
use App\Models\Result;
use App\Services\CallCandidateSelector;
use Barryvdh\DomPDF\Facade\Pdf;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CallController extends Controller
{
    public function index(): View
    {
        $process = Process::current();

        $callLists = $process
            ? CallList::query()
                ->where('process_id', $process->id)
                ->withCount('calls')
                ->with(['calls.result.inscription.user.lgbt', 'calls.result.inscription.user.pne'])
                ->orderByDesc('id')
                ->get()
            : collect();

        $eligible = $process
            ? Result::query()
                ->whereNotNull('ranking')
                ->where('classified', true)
                ->whereHas('inscription', fn ($query) => $query->approved()->forProcess($process->id))
            : Result::query()->whereRaw('1 = 0');

        $countResults = (clone $eligible)->count();

        return view('admin.calls.index', compact('process', 'callLists', 'countResults'));
    }

    public function allCandidates(): View
    {
        $process = Process::current();

        $calls = $process
            ? Call::query()
                ->select('calls.*')
                ->join('call_lists', 'call_lists.id', '=', 'calls.call_list_id')
                ->join('results', 'results.id', '=', 'calls.result_id')
                ->where('call_lists.process_id', $process->id)
                ->with(['callList', 'result.inscription.user.lgbt', 'result.inscription.user.pne'])
                ->orderBy('call_lists.number')
                ->orderBy('call_lists.date')
                ->orderBy('call_lists.time')
                ->orderBy('results.ranking')
                ->orderBy('calls.id')
                ->get()
            : collect();

        return view('admin.calls.all', compact('process', 'calls'));
    }

    public function store(Request $request, CallCandidateSelector $selector): RedirectResponse
    {
        $process = Process::current();

        if (! $process) {
            return back()->withErrors(['error' => 'Cadastre o processo seletivo antes de criar uma chamada.']);
        }

        $data = $request->validate([
            'number' => ['required', 'integer', 'min:1'],
            'limit' => ['required', 'integer', 'min:1'],
            'pcd_limit' => ['required', 'integer', 'min:0'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        if ($data['pcd_limit'] > $data['limit']) {
            return back()->withErrors(['pcd_limit' => 'A quantidade de PcDs não pode exceder o total da chamada.'])->withInput();
        }

        try {
            $results = DB::transaction(function () use ($data, $process, $selector): array {
                $callList = CallList::create([
                    'process_id' => $process->id,
                    'number' => $data['number'],
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'status' => 'pending',
                ]);

                $selected = $selector->select(
                    $process,
                    (int) $data['limit'],
                    (int) $data['pcd_limit'],
                );

                if ($selected->isEmpty()) {
                    throw new DomainException('Não há candidatos classificados e elegíveis ainda não convocados.');
                }

                $pcdIds = $selected
                    ->filter(fn (Result $result) => $result->inscription?->user?->pne?->status === 'accepted')
                    ->take((int) $data['pcd_limit'])
                    ->modelKeys();

                foreach ($selected as $result) {
                    Call::create([
                        'result_id' => $result->id,
                        'call_list_id' => $callList->id,
                        'call_number' => $callList->number,
                        'amount' => $selected->count(),
                        'date' => $callList->date,
                        'time' => $callList->time,
                        'is_manual' => false,
                        'is_pcd_reserve' => in_array($result->id, $pcdIds, true),
                    ]);
                }

                return [$callList->id, $selected->count(), count($pcdIds)];
            });
        } catch (DomainException $exception) {
            return back()->withErrors(['error' => $exception->getMessage()])->withInput();
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withErrors(['error' => 'Não foi possível preparar a chamada.'])->withInput();
        }

        return redirect()
            ->route('admin.calls.index')
            ->with('success', "Rascunho da chamada nº {$data['number']} criado com {$results[1]} convocados, incluindo {$results[2]} PcD.");
    }

    public function show(int $callListId): View
    {
        $process = Process::current();
        $callList = CallList::query()
            ->where('process_id', $process?->id)
            ->whereKey($callListId)
            ->with(['calls.result.inscription.user.lgbt', 'calls.result.inscription.user.pne'])
            ->firstOrFail();

        return view('admin.calls.show', compact('callList'));
    }

    public function destroy(CallList $callList): RedirectResponse
    {
        abort_unless($callList->process_id === Process::current()?->id, 404);

        $number = $callList->number;
        $callList->delete();

        return redirect()->route('admin.calls.index')->with('success', "Chamada nº {$number} excluída.");
    }

    public function finalize(CallList $callList): RedirectResponse
    {
        abort_unless($callList->process_id === Process::current()?->id, 404);

        try {
            DB::transaction(function () use ($callList): void {
                $locked = CallList::query()->lockForUpdate()->findOrFail($callList->id);

                if ($locked->status === 'completed') {
                    throw ValidationException::withMessages(['call' => 'Esta chamada já foi publicada.']);
                }

                if (! $locked->calls()->exists()) {
                    throw ValidationException::withMessages(['call' => 'Não é possível publicar uma chamada sem candidatos.']);
                }

                $locked->update([
                    'status' => 'completed',
                    'published_at' => now(),
                    'published_by' => Auth::id(),
                ]);
            });
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors());
        }

        $callList->refresh()->load('calls.result.inscription.user.lgbt');

        foreach ($callList->calls as $call) {
            $user = $call->result?->inscription?->user;

            if (! $user?->email) {
                Log::warning("Candidato sem e-mail na chamada ID {$call->id}");
                continue;
            }

            SendCallNotificationJob::dispatch(
                $user->email,
                sprintf('CONVOCAÇÃO PARA MATRÍCULA – PROCESSO SELETIVO %s — CHAMADA %d', $callList->process?->year, $callList->number),
                [
                    'nome' => $user->lgbt?->status === 'accepted' ? $user->lgbt->name : $user->name,
                    'data' => $callList->date->format('d/m/Y'),
                    'hora' => $callList->time->format('H:i'),
                    'numero_chamada' => $callList->number,
                ],
                'emails.call.convocation',
            );
        }

        return back()->with('success', 'Chamada publicada no site. Os e-mails são avisos complementares.');
    }

    public function getUserCall(): ?array
    {
        $result = Auth::user()?->inscription?->result;
        $call = $result?->calls()
            ->whereHas('callList', fn ($query) => $query->where('status', 'completed'))
            ->with('callList')
            ->latest('id')
            ->first();

        if (! $call) {
            return null;
        }

        return [
            'date' => $call->date->format('d/m/Y'),
            'time' => $call->time->format('H:i'),
            'call_number' => $call->callList->number,
        ];
    }

    public function pdf(int $callListId): Response
    {
        $callList = CallList::query()
            ->with('process')
            ->where('process_id', Process::current()?->id)
            ->whereKey($callListId)
            ->firstOrFail();

        $callListMembers = $callList->calls()
            ->with([
                'result.inscription.course',
                'result.inscription.user.lgbt',
                'result.inscription.user.pne',
                'result.inscription.user.document',
                'result.inscription.user.certificate',
                'result.inscription.user.academic',
                'result.inscription.user.mother',
                'result.inscription.user.father',
                'result.inscription.user.guardian',
                'result.inscription.user.parent_email',
            ])
            ->join('results', 'calls.result_id', '=', 'results.id')
            ->orderBy('results.ranking')
            ->select('calls.*')
            ->get();

        $process = $callList->process;

        $pdf = Pdf::loadView('admin.calls.pdf', compact('callListMembers', 'callList', 'process'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("fichas-matricula-chamada-{$callList->number}_{$callList->id}.pdf");
    }
}
