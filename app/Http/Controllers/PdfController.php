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
use Illuminate\Support\Facades\Response;

class PdfController extends Controller
{
    // Relatório de Alocação
    public function allocationsToPdf(ExamReportService $reportService)
    {
        $allocations = $reportService->getGroupedAllocations();

        $pdf = Pdf::loadView('admin.allocations.allocation', compact('allocations'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('relatorio-de-alocacao.pdf');
    }

    public function listsToPdf(
        string $type,
        ExamReportService $reportService
    ) {
        $views = [
            'geral'       => 'admin.allocations.general',
            'salas'       => 'admin.allocations.rooms',
            'assinaturas' => 'admin.allocations.signatures',
        ];

        abort_unless(isset($views[$type]), 404);

        $allocations = $reportService->getGroupedAllocations();

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
     * Gera um PDF com a ficha de inscrição do candidato atual e retorna diretamente como download.
     *
     * @return Response O PDF gerado com a ficha de inscri o do candidato.
     */
    public function proofOfRegistrationToPdf()
    {
        // Gera o PDF com a view
        $pdf = Pdf::loadView('admin.pdf.proof-of-registration', [
            'user' => Auth::user(),
        ]);

        // Sanitiza o CPF (remove espaços, pontos, traços etc.)
        $cpfSanitizado = preg_replace('/[^0-9]/', '', Auth::user()->cpf);

        // Monta o nome do arquivo
        $filename = 'Protocolo_' . $cpfSanitizado . '.pdf';

        // Retorna o PDF diretamente como download
        return $pdf->download($filename);
    }

    /**
     * Gera um PDF com o cartão do local de prova do candidato.
     *
     * Verifica se o usuário logado possui local de prova.
     * Caso não possua, redireciona para a página anterior com um aviso.
     *
     * @return \Illuminate\Http\Response
     */
    public function testLocationCardToPdf()
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
            'pdf.test-location-card',
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
     * @return \Illuminate\Http\Response
     */
    public function testResultCardToPdf()
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
     * @return \Illuminate\Http\Response
     */
    public function callCardToPdf()
    {
        // $user = Auth::user();
        $user = Auth::user()->load(['lgbt', 'inscription.exam_result']);

        // Acessar a inscrição e o resultado do exame do usuário autenticado
        $examResult = $user->inscription->exam_result ?? null;

        if (! $examResult) {
            return null; // Usuário sem resultado de exame
        }

        // Verifica se há chamada para esse resultado com lista finalizada
        $call = Call::where('exam_result_id', $examResult->id)
            ->whereHas('callList', fn($query) => $query->where('status', 'completed'))
            ->with('callList') // carrega os dados da lista de chamada
            ->first();

        if (! $call) {
            return back()->with('warning', 'Nenhuma convocação finalizada encontrada.');
        }

        $pdf = Pdf::loadView('pdf.call-card', [
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
     * @return Response O PDF gerado com a ficha de inscrição do candidato.
     */
    public function inscriptionReceiptToPdf()
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

        $pdf = Pdf::loadView('inscription.pdf.complete', [
            'user' => $user,
            'displayName' => $displayName,
            'process' => $process,
        ]);

        $cpfSanitizado = preg_replace('/\D/', '', $user->cpf);

        return $pdf->download("Protocolo_{$cpfSanitizado}.pdf");
    }
}
