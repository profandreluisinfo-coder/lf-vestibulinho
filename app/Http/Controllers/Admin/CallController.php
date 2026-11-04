<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CallExport;
use App\Http\Controllers\Controller;
use App\Jobs\SendCallNotificationJob;
use App\Models\Call;
use App\Models\CallList;
use App\Models\Process;
use App\Models\Result;
use App\Services\CallCandidateSelector;
use Barryvdh\DomPDF\Facade\Pdf;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
                ->with(['calls.result.inscription.user.lgbt', 'calls.result.inscription.user.pne', 'calls.course'])
                ->orderBy('number')
                ->get()
            : collect();
        $courses = $process?->courses ?? collect();

        $calledIds = $process
            ? Call::query()
                ->whereHas('result.inscription', fn ($query) => $query->forProcess($process->id))
                ->pluck('result_id')
            : collect();

        $eligible = $process
            ? Result::query()
                ->whereNotNull('ranking')
                ->where('classified', true)
                ->whereHas('inscription', fn ($query) => $query->approved()->forProcess($process->id))
            : Result::query()->whereRaw('1 = 0');

        $pcdCandidates = (clone $eligible)
            ->whereNotIn('id', $calledIds)
            ->whereHas('inscription.user.pne', fn ($query) => $query->where('status', 'accepted'))
            ->with(['inscription.user.lgbt'])
            ->orderBy('ranking')
            ->get();

        $calledByCourse = $callLists
            ->flatMap(fn ($callList) => $callList->calls)
            ->filter(fn ($call) => $call->course)
            ->groupBy(fn ($call) => $call->course->name)
            ->map(fn ($calls) => $calls->count())
            ->sortKeys();

        $chartData = [
            'labels' => $calledByCourse->keys()->values(),
            'data' => $calledByCourse->values(),
            'colors' => $calledByCourse->keys()->map(fn () => sprintf('#%06X', mt_rand(0, 0xFFFFFF))),
        ];

        $countResults = (clone $eligible)->count();
        $lastCallList = $callLists->last();

        return view('admin.calls.index', compact(
            'process',
            'callLists',
            'courses',
            'pcdCandidates',
            'chartData',
            'countResults',
            'lastCallList',
        ));
    }

    public function store(Request $request, CallCandidateSelector $selector): RedirectResponse
    {
        $process = Process::current();

        if (! $process) {
            return back()->withErrors(['error' => 'Cadastre o processo seletivo antes de criar uma chamada.']);
        }

        $data = $request->validate([
            'number' => ['required', 'integer', 'min:1', Rule::unique('call_lists', 'number')->where('process_id', $process->id)],
            'limit' => ['required', 'integer', 'min:1'],
            'pcd_limit' => ['required', 'integer', 'min:0'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'vacancy_course_id' => [
                'nullable',
                'integer',
                Rule::exists('course_process', 'course_id')->where('process_id', $process->id),
            ],
        ]);

        if ($data['pcd_limit'] > $data['limit']) {
            return back()->withErrors(['pcd_limit' => 'A quantidade de PcDs não pode exceder o total da chamada.'])->withInput();
        }

        $callDate = Carbon::parse($data['date'])->startOfDay();
        $firstEnrollmentDate = $process->enrol_start?->copy()->startOfDay();
        $remainingEnrollmentDate = $process->enrol_remaining?->copy()->startOfDay();
        $enrollmentDeadline = $process->enrol_deadline?->copy()->startOfDay();

        if (! $enrollmentDeadline) {
            return back()->withErrors(['date' => 'Defina a data limite para convocações no calendário do processo.'])->withInput();
        }

        if ($firstEnrollmentDate && $callDate->lt($firstEnrollmentDate)) {
            return back()->withErrors(['date' => 'A convocação não pode ocorrer antes do início das matrículas definido no calendário.'])->withInput();
        }

        if ((int) $data['number'] > 1 && $remainingEnrollmentDate && $callDate->lt($remainingEnrollmentDate)) {
            return back()->withErrors(['date' => 'As chamadas de vagas remanescentes não podem ocorrer antes da data prevista no calendário.'])->withInput();
        }

        if ($callDate->gt($enrollmentDeadline)) {
            return back()->withErrors(['date' => 'A convocação não pode ocorrer após a data limite definida no calendário.'])->withInput();
        }

        try {
            $results = DB::transaction(function () use ($data, $process, $selector): array {
                $callList = CallList::create([
                    'process_id' => $process->id,
                    'number' => $data['number'],
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'status' => 'pending',
                    'vacancy_course_id' => $data['vacancy_course_id'] ?? null,
                ]);

                $selected = $selector->select(
                    $process,
                    (int) $data['limit'],
                    (int) $data['pcd_limit'],
                    isset($data['vacancy_course_id']) ? (int) $data['vacancy_course_id'] : null,
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
                        'enrollment_status' => 'awaiting',
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

    public function show(int $callNumber): View
    {
        $process = Process::current();
        $callList = CallList::query()
            ->where('process_id', $process?->id)
            ->where('number', $callNumber)
            ->with(['calls.result.inscription.user.lgbt', 'calls.result.inscription.user.pne', 'calls.course'])
            ->firstOrFail();
        $courses = $callList->process?->courses ?? collect();

        return view('admin.calls.show', compact('callList', 'courses'));
    }

    public function destroy(CallList $callList): RedirectResponse
    {
        abort_unless($callList->process_id === Process::current()?->id, 404);
        abort_if($callList->status === 'completed', 409, 'Uma chamada publicada não pode ser excluída.');

        $callList->delete();

        return redirect()->route('admin.calls.index')->with('success', 'Rascunho da chamada excluído.');
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

    public function updateEnrollment(Request $request, Call $call): RedirectResponse
    {
        $call->load('callList');
        abort_unless($call->callList?->process_id === Process::current()?->id, 404);
        abort_unless($call->callList?->status === 'completed', 409, 'Publique a chamada antes de registrar a matrícula.');

        $data = $request->validate([
            'enrollment_status' => ['required', Rule::in(['enrolled', 'documents_pending', 'lost', 'withdrawn', 'waiting_course'])],
            'course_id' => [
                Rule::requiredIf(in_array($request->input('enrollment_status'), ['enrolled', 'documents_pending', 'waiting_course'], true)),
                'nullable',
                'integer',
                Rule::exists('course_process', 'course_id')->where('process_id', $call->callList->process_id),
            ],
            'regularization_deadline' => [
                Rule::requiredIf($request->input('enrollment_status') === 'documents_pending'),
                'nullable',
                'date',
                'after_or_equal:' . now()->addHours(48)->format('Y-m-d H:i:s'),
            ],
            'enrollment_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            DB::transaction(function () use ($call, $data): void {
                $locked = Call::query()->lockForUpdate()->findOrFail($call->id);
                $processId = $locked->callList()->value('process_id');
                $courseId = $data['course_id'] ?? null;
                $status = $data['enrollment_status'];

                if ($courseId && in_array($status, ['enrolled', 'documents_pending'], true)) {
                    $course = DB::table('course_process')
                        ->where('process_id', $processId)
                        ->where('course_id', $courseId)
                        ->lockForUpdate()
                        ->first();

                    $occupied = Call::query()
                        ->where('course_id', $courseId)
                        ->whereIn('enrollment_status', ['enrolled', 'documents_pending'])
                        ->where('id', '!=', $locked->id)
                        ->whereHas('callList', fn ($query) => $query->where('process_id', $processId))
                        ->count();

                    if (! $course || $occupied >= $course->vacancies) {
                        throw ValidationException::withMessages(['course_id' => 'Não há vagas disponíveis neste curso.']);
                    }
                }

                $locked->update([
                    'enrollment_status' => $status,
                    'course_id' => $courseId,
                    'regularization_deadline' => $status === 'documents_pending' ? $data['regularization_deadline'] : null,
                    'enrollment_notes' => $data['enrollment_notes'] ?? null,
                    'enrollment_recorded_at' => now(),
                    'enrollment_recorded_by' => Auth::id(),
                ]);
            });
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors())->withInput();
        } catch (ModelNotFoundException $exception) {
            abort(404);
        }

        return back()->with('success', 'Situação da matrícula registrada.');
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

    public function excel(int $callNumber): BinaryFileResponse
    {
        return Excel::download(new CallExport($callNumber, Process::current()?->id), "chamada_{$callNumber}.xlsx");
    }

    public function pdf(int $callNumber): Response
    {
        $callList = CallList::query()
            ->where('process_id', Process::current()?->id)
            ->where('number', $callNumber)
            ->firstOrFail();

        $callListMembers = $callList->calls()
            ->with([
                'result.inscription.user.lgbt',
                'result.inscription.user.pne',
                'result.inscription.user.document',
                'result.inscription.user.certificate',
                'result.inscription.user.academic',
                'result.inscription.user.mother',
                'result.inscription.user.father',
                'result.inscription.user.guardian',
                'result.inscription.user.parent_email',
                'course',
            ])
            ->join('results', 'calls.result_id', '=', 'results.id')
            ->orderBy('results.ranking')
            ->select('calls.*')
            ->get();

        $pdf = Pdf::loadView('admin.calls.pdf', compact('callListMembers', 'callList'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("fichas-matricula-chamada-{$callNumber}.pdf");
    }
}
