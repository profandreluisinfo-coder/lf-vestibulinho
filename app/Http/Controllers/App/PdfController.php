<?php

namespace App\Http\Controllers\App;

use App\Models\Call;
use App\Models\User;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ExamReportService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PdfController extends Controller
{
    // Relatório de Alocação
    public function allocationsToPdf(ExamReportService $reportService)
    {
        $allocations = $reportService->getGroupedAllocations();

        $pdf = Pdf::loadView('app.allocations.allocation', compact('allocations'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('relatorio-de-alocacao.pdf');
    }

    // Lista para Salas
    public function roomsToPdf(ExamReportService $reportService)
    {
        $allocations = $reportService->getGroupedAllocations();

        $pdf = Pdf::loadView('app.allocations.rooms', compact('allocations'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('lista-para-salas.pdf');
    }

    // Lista de Assinaturas
    public function signaturesToPdf()
    {
        $allocations = ExamResult::join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->join('exam_locations', 'exam_results.exam_location_id', '=', 'exam_locations.id')
            ->select(
                'exam_locations.name as location_name',
                'exam_results.room_number',
                'users.name',
                'users.social_name',
                'users.cpf',
                'users.birth',
                'users.pne',
                'inscriptions.id as inscription_id' // 👈 aqui
            )
            ->orderBy('exam_results.exam_location_id')
            ->orderBy('exam_results.room_number')
            ->orderBy('users.name')
            ->get()
            ->groupBy(['location_name', 'room_number']);

        $pdf = Pdf::loadView('app.allocations.signatures', compact('allocations'))->setPaper('a4', 'portrait');

        return $pdf->stream('lista-de-assinaturas.pdf');
    }

    /**
     * Gera um PDF com a ficha de inscrição do candidato atual e retorna diretamente como download.
     *
     * @return Response O PDF gerado com a ficha de inscri o do candidato.
     */
    public function proofOfRegistrationToPdf()
    {
        // Gera o PDF com a view
        $pdf = Pdf::loadView('app.pdf.proof-of-registration', [
            'user' =>  Auth::user()
        ]);

        // Sanitiza o CPF (remove espaços, pontos, traços etc.)
        $cpfSanitizado = preg_replace('/[^0-9]/', '',  Auth::user()->cpf);

        // Monta o nome do arquivo
        $filename = 'comprovante_' . $cpfSanitizado . '.pdf';

        // Retorna o PDF diretamente como download
        return $pdf->download($filename);
    }

    /**
     * Exporta a lista de inscrições em formato PDF.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function allInscriptionsToPdf(Request $request)
    {
        $search = $request->get('search');

        $users = User::whereHas('inscription')
            ->with('inscription')
            ->join('inscriptions', 'users.id', '=', 'inscriptions.user_id')
            ->select('users.*')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.cpf', 'like', "%{$search}%")
                        ->orWhere('inscriptions.id', 'like', "%{$search}%");
                });
            })
            ->orderBy('inscriptions.id', 'asc')
            ->get();

        if ($users->isEmpty()) {
            return redirect()->back()->with('warning', 'Nenhuma inscrição encontrada para os critérios informados.');
        }

        // código de geração de PDF...
        $pdf = Pdf::loadView('app.pdf.all-inscriptions', [
            'users' => $users,
            'search' => $search,
        ]);

        return $pdf->download('inscricoes.pdf');
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
        $exam = ExamResult::with('location')
            ->whereHas('inscription', fn($q) => $q->where('user_id',  Auth::user()->id))
            ->first();

        if (!$exam) {
            return redirect()->back()->with('error', 'Local de prova não encontrado.');
        }

        $pdf = Pdf::loadView('app.pdf.test-location-card', compact('exam'))->setPaper('a4', 'portrait');

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
        $user = Auth::user();
        // Verifica se o usuário logado possui resultado de exame
        $examResult = ExamResult::with(['inscription.user'])
            ->whereHas('inscription', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (!$examResult) {
            return redirect()->back()->withErrors(['error' => 'Resultado ainda não disponível.']);
        }

        return Pdf::loadView('app.pdf.test-result-card', compact('examResult', 'user'))
            ->setPaper('a4', 'portrait')
            ->download('resultado-da-prova.pdf');
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
        $user = Auth::user();

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
            return back()->with('warning', 'Nenhuma convocação finalizada encontrada.');
        }

        $pdf = Pdf::loadView('app.pdf.call-card', [
            'user' =>  $user,
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
                'Laudo médico atualizado (se PCD).'
            ],
        ]);

        return $pdf->download('convocacao-para-matricula.pdf');
    }

    /**
     * Gera um PDF com a ficha de inscrição do candidato atual e retorna diretamente como download.
     *
     * @return Response O PDF gerado com a ficha de inscri o do candidato.
     */
    public function inscriptionReceiptToPdf()
    {
        $user = Auth::user();

        if ($user) {
            // Gera o PDF com a view
            $pdf = Pdf::loadView('app.pdf.proof-of-registration', [
                'user' => $user
            ]);

            // Sanitiza o CPF (remove espaços, pontos, traços etc.)
            $cpfSanitizado = preg_replace('/[^0-9]/', '', $user->cpf);

            // Monta o nome do arquivo
            $filename = 'Inscricao_' . $cpfSanitizado . '.pdf';

            // Retorna o PDF diretamente como download
            return $pdf->download($filename);
        }
    }
}