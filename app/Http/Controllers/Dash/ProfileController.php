<?php

namespace App\Http\Controllers\Dash;

use App\Models\Call;
use Illuminate\View\View;
use App\Models\ExamResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    /**
     * Página principal do painel de administração do candidato
     * 
     * Exibe as informações do usuário logado, bem como as informações de como fazer a inscrição.
     * 
     * Route: GET /dashboard (dashboard.index)
     * 
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = auth()->user();

        return view('dashboard.user.profile', compact('user'));
    }

    /**
     * Exibe a página com os dados da inscrição do usuário atual.
     *
     * Caso o usuário não possua inscrição ativa, redireciona para a página
     * com um aviso.
     *
     * Carrega os dados necessários para a exibição correta da página.
     *
     * @return \Illuminate\View\View
     */
    public function inscription()
    {
        $user = auth()->user();

        // Segurança extra caso acessem direto sem ter inscrição
        if (!$user->inscription()->exists()) {
            return redirect()
                ->route('dashboard.index')
                ->with('warning', 'Você ainda não possui inscrição ativa.');
        }

        // Carrega tudo que o painel precisa
        $user->load([
            'inscription.exam_result.examLocation',
            'inscription.exam_result.completedCall',
        ]);

        $inscription  = $user->inscription;
        $examResult   = $inscription->exam_result;
        $examLocation = $examResult?->examLocation;

        /*$exam = $examLocation ? [
            'location_name' => $examLocation->name,
            'address'       => $examLocation->address,
            'room_number'   => $examResult->room_number,
            'exam_date'     => $examResult->exam_date,
            'exam_time'     => $examResult->exam_time,
            'user_id'       => $user->id,
            'pne'           => $user->pne,
        ] : null; */
        
        $exam = $examResult; // O EXAM REAL — o model completo

        $call = $examResult?->completedCall;

        return view('inscriptions.private.profile', compact('user', 'exam', 'examResult', 'call'));
    }

    // Método para obtenção dos dados do local de realização de prova do candidato
    public function showExamLocation(): View
    {
        $user = auth()->user();

        $exam = DB::table('exam_results')
            ->join('exam_locations', 'exam_results.exam_location_id', '=', 'exam_locations.id')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->where('inscriptions.user_id', $user->id)
            ->select(
                'exam_locations.name as location_name',
                'exam_results.room_number',
                'exam_results.exam_date',
                'exam_results.exam_time',
                'user_id',
                'users.pne'
            )
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->first();

        return view('user.exam-location', compact('exam'));
    }

    public function examCardPdf()
    {
        $user = Auth::user();

        $exam = ExamResult::with('location')
            ->whereHas('inscription', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (!$exam) {
            return redirect()->back()->with('error', 'Local de prova não encontrado.');
        }

        $pdf = Pdf::loadView('pdf.exam-card', compact('exam'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('cartao-local-prova.pdf');
    }

    public function resultCardPdf()
    {
        $user = auth()->user();

        $examResult = ExamResult::with(['inscription.user'])
            ->whereHas('inscription', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (!$examResult) {
            return redirect()->route('user.results')->withErrors(['error' => 'Resultado ainda não disponível.']);
        }

        return Pdf::loadView('pdf.result-card', compact('examResult', 'user'))
            ->setPaper('a4', 'portrait')
            ->download('resultado-prova.pdf');
    }

    public function generateCallPdf()
    {
        /** @var \App\Models\User $user */
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
            return back()->with('warning', 'Nenhuma convocação finalizada encontrada.');
        }

        $pdf = Pdf::loadView('pdf.generate-call-pdf', [
            'user' => $user,
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

        return $pdf->download('convocacao.pdf');
    }
}