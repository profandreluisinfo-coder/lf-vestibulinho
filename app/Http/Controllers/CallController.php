<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\CallList;
use Illuminate\View\View;
use App\Models\ExamResult;
use App\Exports\CallExport;
use Illuminate\Http\Request;
use App\Services\MailService;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendCallNotificationJob;
use App\Models\Calendar;
use Illuminate\Support\Facades\DB;


/**
 * Class CallController
 * Controller para gerenciamento de chamadas
 *
 * @package App\Http\Controllers
 */
class CallController extends Controller
{
    /**
     * Obter todas as chamadas e seus respectivos dados: nº da chamada, data e hora, além do, nome do usuário e 
     * o nº da sua inscrição.
     * As informações são exibdas na página pública de chamadas do site.
     * $calls é uma coleção agrupada
     */
    public function index()
    {
        // Obter todas as chamadas e seus respectivos dados
        $calls = Call::with('callList', 'examResult.inscription.user')
            ->whereHas('callList', fn($q) => $q->where('status', 'completed'))
            ->get()
            ->groupBy(fn($call) => $call->callList->number)
            ->sortKeys();

        // verificar se 'calls' é uma coleção vazia
        if ($calls->isEmpty()) {
            return redirect()->route('home');
        }

        view()->share('calls', $calls);

        return view('calls.public.index');
    }

    /**
     * Renderiza a view para criar uma nova chamada
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $callLists = CallList::withCount('calls')
            ->with(['calls.examResult.inscription.user'])
            ->orderBy('number')
            ->get();

        // IDs dos exam_results que já foram convocados (qualquer chamada, seja manual ou não)
        $alreadyCalledIds = Call::pluck('exam_result_id')->toArray();

        // Filtra os PCDs que ainda não foram convocados
        $pneCandidates = ExamResult::whereNotNull('ranking')
            ->whereNotIn('id', $alreadyCalledIds)
            ->whereHas('inscription.user', fn($query) => $query->where('pne', true))
            ->with(['inscription.user'])
            ->orderBy('ranking')
            ->get();

        // Quantidade de convocados por curso
        $convocadosPorCurso = Call::with('examResult.inscription.course')
            ->get()
            ->groupBy(function ($call) {
                return optional($call->examResult->inscription->course)->name ?? 'Sem curso';
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->sortKeys();

        $chartData = [
            'labels' => $convocadosPorCurso->keys(),
            'data' => $convocadosPorCurso->values(),
        ];

        $colors = collect(range(1, count($chartData['labels'])))
            ->map(function () {
                return sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Gera cores aleatórias
            });

        $chartData['colors'] = $colors;

        // Verificar se as provas já foram corrigidas para exibir o botão de lançamento de chamada
        $countResults = ExamResult::count();

        view()->share(compact('callLists', 'pneCandidates', 'chartData', 'countResults'));

        return view('calls.private.create');
    }

    /**
     * Armazena uma chamada no banco de dados e seus respectivos chamados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'manual_pcds' => 'array',
            'manual_pcds.*' => 'exists:exam_results,id',
        ], [
            'date.required' => 'Por favor, informe a data.',
            'time.required' => 'Por favor, informe a hora.',
            'time.date_format' => 'Por favor, informe a hora no formato HH:MM.',
            'manual_pcds.*.exists' => 'Um PCD inválido foi informado.',
        ]);

        $number = (int) $request->number;
        $limit = (int) $request->limit;
        $date = $request->date;
        $time = $request->time;

        // 🔍 Impede chamadas duplicadas
        if (CallList::where('number', $number)->exists()) {
            return back()->withErrors(['number' => 'Já existe uma chamada com esse número.'])->withInput();
        }

        try {
            DB::transaction(function () use ($number, $limit, $date, $time, $request) {

                // 🧾 Cria a lista principal
                $callList = CallList::create([
                    'number' => $number,
                    'date' => $date,
                    'time' => $time,
                    'status' => 'pending',
                ]);

                // 📜 Candidatos já convocados (em qualquer chamada anterior)
                $alreadyCalledIds = Call::pluck('exam_result_id')->toArray();

                // 🔢 Próximos classificados ainda não chamados
                $examResults = ExamResult::whereNotNull('ranking')
                    ->whereNotIn('id', $alreadyCalledIds)
                    ->orderBy('ranking')
                    ->take($limit)
                    ->get();

                // ⚙️ Criação dos chamados automáticos
                foreach ($examResults as $result) {
                    Call::create([
                        'exam_result_id' => $result->id,
                        'call_list_id' => $callList->id,
                        'call_number' => $number,
                        'amount' => $limit,
                        'date' => $date,
                        'time' => $time,
                        'is_manual' => false,
                    ]);
                }

                // ♿ Criação dos chamados manuais (PCDs)
                if ($request->filled('manual_pcds')) {
                    // Evita duplicação com automáticos
                    $autoIds = $examResults->pluck('id')->toArray();
                    $manualIds = array_diff($request->manual_pcds, $autoIds);

                    if (!empty($manualIds)) {
                        $manualPcds = ExamResult::whereIn('id', $manualIds)->get();

                        foreach ($manualPcds as $result) {
                            Call::create([
                                'exam_result_id' => $result->id,
                                'call_list_id' => $callList->id,
                                'call_number' => $number,
                                'amount' => $limit,
                                'date' => $date,
                                'time' => $time,
                                'is_manual' => true,
                            ]);
                        }
                    }
                }
            });

            return redirect()
                ->route('callings.create')
                ->with('success', 'Chamada registrada com sucesso!');
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors(['error' => 'Ocorreu um erro ao registrar a chamada.'])->withInput();
        }
    }

    /**
     * Exibe a lista de convocados para uma chamada específica.
     *
     * @param int $call_number Número da chamada.
     *
     * @return \Illuminate\View\View
     */
    public function show($call_number)
    {
        // Busca as chamadas com todos os dados do usuário carregados via relacionamento
        $convocados = Call::with('examResult.inscription.user')
            ->where('call_number', $call_number)
            ->get();

        return view('calls.private.show', compact('convocados', 'call_number'));
    }

    /**
     * Remove a chamada com todos os seus chamados.
     *
     * @param \App\Models\CallList $callList
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CallList $callList)
    {
        $callList->delete();

        return redirect()->route('callings.create')->with('success', 'Chamada excluída com sucesso!');
    }

    /**
     * Finaliza uma lista de chamadas atualizando seu status para concluído e enviando notificações por e-mail
     * para todos os usuários associados às chamadas na lista.
     *
     * @param \App\Models\CallList $callList A lista de chamadas está sendo finalizada.
     * @param \App\Services\MailService $mailService O serviço de correio usado para enviar e-mails.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finalize(CallList $callList, MailService $mailService)
    {
        // Obtém o ano do calendário atual
        $actual_calendar = Calendar::getYear();

        // Atualiza o status da lista de chamadas para 'completed'
        $callList->update(['status' => 'completed']);

        // Carrega todas as chamadas associadas à lista de chamadas, incluindo os dados do usuário
        $calls = $callList->calls()->with('examResult.inscription.user')->get();

        // Envia e-mails de convocação para os usuários associados às chamadas
        foreach ($calls as $call) {
            $user = $call->examResult->inscription->user;

            if (!$user || !$user->email) {
                Log::warning("Usuário sem e-mail na chamada ID {$call->id}");
                continue;
            }

            $subject = sprintf(
                'CONVOCAÇÃO PARA MATRÍCULA – PROCESSO SELETIVO %s E.M. DR. LEANDRO FRANCESCHINI — CHAMADA %d',
                $actual_calendar,
                $callList->number
            );

            $content = [
                'nome' => $user->social_name ?? $user->name,
                'data' => \Carbon\Carbon::parse($callList->date)->format('d/m/Y'),
                'hora' => \Carbon\Carbon::parse($callList->time)->format('H:i'),
                'numero_chamada' => $callList->number,
            ];

            // Dispara o job para fila
            SendCallNotificationJob::dispatch(
                $user->email,
                $subject,
                $content,
                'mail.call_notification'
            );
        }

        return back()->with('success', 'Chamada finalizada! Os convocados serão notificados por e-mail.');
    }

    /**
     * Retorna os dados da chamada para o qual o usuário autenticado foi convocado.
     * Caso o usuário não tenha resultado de exame ou não tenha sido convocado em nenhuma chamada finalizada,
     * retorna null.
     *
     * @return array|null Os dados da chamada ou null se o usuário não tiver resultado de exame ou não tiver sido convocado.
     */
    public function getUserCall()
    {
        $user = auth()->user();

        // Acessar a inscrição e o resultado do exame do usuário autenticado
        $examResult = $user->inscription->exam_result ?? null;

        if (!$examResult) {
            return null; // Usuário sem resultado de exame
        }

        // Verifica se há chamada para esse resultado com lista finalizada
        $call = Call::where('exam_result_id', $examResult->id)
            ->whereHas('callList', fn($query) => $query->where('status', 'completed'))
            ->with('callList') // carrega os dados da lista de chamada
            ->first();

        if (!$call) {
            return null; // Não foi convocado em nenhuma chamada finalizada
        }

        return [
            'date' => $call->date->format('d/m/Y'),
            'time' => $call->time->format('H:i'),
            'call_number' => $call->callList->number,
        ];
    }

    /**
     * Gera uma lista de matrícula com os convocados para uma chamada específica.
     *
     * @param int $call_number Número da chamada.
     * @return \Illuminate\View\View
     */
    public function excel($call_number)
    {
        return Excel::download(new CallExport($call_number), "chamada_{$call_number}.xlsx");
    }

    /**
     * Gera um PDF com a lista de matrícula com os convocados para uma chamada específica.
     *
     * @param int $call_number Número da chamada.
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf($call_number)
    {
        $callListMembers = Call::with('examResult.inscription.user.user_detail')
            ->where('call_number', $call_number)
            ->whereHas('callList', fn($q) => $q->where('status', 'completed'))
            ->join('exam_results', 'calls.exam_result_id', '=', 'exam_results.id')
            ->orderBy('exam_results.ranking')
            ->select('calls.*')
            ->get();

        $pdf = Pdf::loadView('calls.private.pdf', compact('callListMembers'))
            ->setPaper('a4', 'portrait');

        // return $pdf->download("chamada_{$call_number}.pdf"); // faz download

        return $pdf->stream("chamada_{$call_number}.pdf"); // abre no navegador

    }
}
