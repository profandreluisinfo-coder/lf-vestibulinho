<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Exam;
use App\Models\Lgbt;
use App\Models\Pne;
use App\Models\Process;
use App\Services\ExamReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class PdfController extends Controller
{
    /**
     * Gera o relatório de alocação de candidatos em formato PDF para visualização.
     *
     * Este método obtém os dados de alocação agrupados através do serviço de relatórios,
     * renderiza a respectiva view administrativa em formato A4 (retrato) e abre o
     * fluxo (stream) do arquivo PDF diretamente no navegador do usuário.
     * 
     * Local: Painel Administrativo
     *
     * @param  \App\Services\ExamReportService  $rs  Serviço responsável pela lógica do relatório.
     * @return \Illuminate\Http\Response Resposta HTTP contendo o stream do PDF.
     */
    public function listsToPdf(string $type, ExamReportService $rs): Response
    {
        $views = [
            'geral'       => 'pdf.allocations.general',
            'salas'       => 'pdf.allocations.rooms',
            'assinaturas' => 'pdf.allocations.signatures',
        ];

        abort_unless(isset($views[$type]), 404);

        $allocations = $rs->getGroupedAllocations();

        $pdf = Pdf::loadView($views[$type], compact('allocations'))
            ->setPaper('a4', 'portrait');

        $filename = match ($type) {
            'geral'       => 'relatorio-geral-de-alocacao.pdf',
            'salas'       => 'lista-para-salas.pdf',
            'assinaturas' => 'lista-de-assinaturas.pdf',
        };

        return $pdf->stream($filename);
    }

    /**
     * Gera o PDF com o cartão do local de prova do candidato.
     *
     * Verifica se as alocações foram liberadas pelo administrador e se o candidato
     * possui um registro válido. Caso contrário, redireciona o usuário com uma mensagem de erro.
     * 
     * Local: Área do Candidato
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse Stream do PDF ou redirecionamento em caso de erro.
     */
    public function locationCardToPdf(): Response | RedirectResponse
    {
        $process = Process::current();

        // O cartão só existe depois que o admin libera o local da prova
        if (! $process?->isLocationReleased()) {
            return redirect()->back()->with('error', 'O local de prova ainda não foi liberado.');
        }

        // Só a alocação do próprio candidato, no processo atual
        $exam = Exam::with(['location', 'inscription.user.lgbt', 'inscription.user.pne'])
            ->whereHas(
                'inscription',
                fn($q) => $q
                    ->forProcess($process->id)
                    ->where('user_id', Auth::id())
            )
            ->first();

        if (! $exam) {
            return redirect()->back()->with('error', 'Local de prova não encontrado.');
        }

        $user = $exam->inscription->user;

        $displayName = $user->lgbt?->status === Lgbt::STATUS_ACCEPTED
            ? $user->lgbt->name
            : $user->name;

        $isPneRoom = $user->pne?->status === Pne::STATUS_ACCEPTED;

        $pdf = Pdf::loadView(
            'pdf.cards.location',
            compact('exam', 'process', 'user', 'displayName', 'isPneRoom')
        )->setPaper('a4', 'portrait');

        return $pdf->stream('cartao-local-prova.pdf');
    }

    /**
     * Gera um PDF com o cartão do resultado da prova do candidato.
     *
     * Verifica se o usuário logado possui resultado da prova.
     * Caso não possua, redireciona para a página anterior com um aviso.
     *
     * Local: Área do Candidato
     * 
     * @return \Illuminate\Http\Response
     */
    public function resultCardToPdf()
    {
        return "precisa refazer este método";
    }

    /**
     * Gera um PDF com a ficha de convocação do candidato atual com base na lista de chamada finalizada.
     *
     * Verifica se o usuário logado possui resultado de exame e se há chamada para esse resultado com lista finalizada.
     * Caso o usuário não tenha resultado de exame ou não tenha sido convocado em nenhuma chamada finalizada,
     * retorna null.
     * 
     * Local: Área do Candidato
     *
     * @return \Illuminate\Http\Response
     */
    public function callCardToPdf()
    {
        // $user = Auth::user();
        $user = Auth::user()->load(['lgbt', 'inscription.result']);

        // Acessar a inscrição e o resultado do exame do usuário autenticado
        $examResult = $user->inscription?->result;

        if (! $examResult) {
            return null; // Usuário sem resultado de exame
        }

        // Verifica se há chamada para esse resultado com lista finalizada
        $call = Call::where('result_id', $examResult->id)
            ->whereHas('callList', fn ($query) => $query
                ->where('status', 'completed')
                ->where('process_id', $examResult->inscription?->process_id))
            ->with('callList') // carrega os dados da lista de chamada
            ->first();

        if (! $call) {
            return back()->with('warning', 'Nenhuma convocação finalizada encontrada.');
        }

        $pdf = Pdf::loadView('pdf.cards.call', [
            'user' => $user,
            'examResult' => $examResult,
            'call' => $call,
            'location' => 'R. Geraldo de Souza, 157/221 - Jardim Sao Carlos, Sumaré - SP, 13170-232',
            'phone' => '(19) 3873-2605',
            'open_hours' => 'Aberto ⋅ Fecha às 23:00',
            'documents' => [
                'Original e 01 cópia do Histórico Escolar do Ensino Fundamental e/ou Declaração de Conclusão;',
                'Original e 01 cópia do CPF;',
                'Original e 01 cópia do RG;',
                'Original e 01 cópia do comprovante de residência em Sumaré (menos de 30 dias);',
                'Original e 01 cópia da certidão de nascimento;',
                '3 (três) fotos 3x4;',
                'Laudo médico atualizado (se PCD).',
            ],
        ]);

        return $pdf->download('convocacao-para-matricula.pdf');
    }

    /**
     * Gera um PDF com a ficha de inscrição do candidato atual e retorna diretamente como download.
     * 
     * Local: Área do Candidato
     * 
     * @return Response O PDF gerado com a ficha de inscrição do candidato.
     */
    public function inscriptionReceiptToPdf(): Response
    {
        $user = auth()->user()->load([
            'inscription',
            'lgbt',
            'document',
            'certificate',
            'academic',
            'mother',
            'father',
            'guardian',
            'parent_email',
            'pne'
        ]);

        $displayName = $user->lgbt?->status === "accepted"
            ? $user?->lgbt?->name
            : $user->name;

        $process = $user->inscription?->process;

        $pdf = Pdf::loadView('pdf.inscription.complete', [
            'user' => $user,
            'displayName' => $displayName,
            'process' => $process,
        ]);

        $inscription = $user->inscription?->id;

        return $pdf->download("Protocolo_{$inscription}.pdf");
    }
}
