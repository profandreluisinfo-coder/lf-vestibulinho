<?php

namespace App\Http\Controllers\Dash;

use App\Models\User;
use Illuminate\View\View;
use App\Models\ExamResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Página principal do painel de administração contendo a lista de usuários sem inscrição.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $users = User::getWithoutInscription();

        return view('users.admin.index', compact('users'));
    }

    /**
     * Página principal do painel de administração do candidato
     * 
     * Exibe as informações do usuário logado, bem como as informações de como fazer a inscrição.
     * 
     * @return \Illuminate\View\View
     */
    public function home(): View
    {
        $user = Auth::user();

        return view('dash.user.home', compact('user'));
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
    public function inscription(): View | RedirectResponse
    {
        $user = Auth::user();

        // Segurança extra caso acessem direto sem ter inscrição
        if (!$user->inscription()->exists()) {
            return redirect()
                ->route('dash.user.home')
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

        $exam = $examResult; // O EXAM REAL — o model completo

        $call = $examResult?->completedCall;

        return view('dash.user.inscription', compact('user', 'exam', 'examResult', 'call'));
    }

    /**
     * Apaga o nome social do candidato.
     * 
     * Verifica se o candidato não tem prova agendada e, se for verdadeiro, remove-o da lista de PNE.
     * 
     * @param User $user O usuário a ter o nome social apagado.
     * @return JsonResponse Com um JSON contendo o status da operação e uma mensagem.
     */
    public function clearSocialNameFromList(User $user): JsonResponse
    {
        if (ExamResult::exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível apagar o nome social, pois existe prova agendada!'
            ]);
        }

        $user->update(['social_name' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Nome social apagado com sucesso!'
        ]);
    }

    /**
     * Remove um candidato da lista de PNE.
     *
     * Verifica se o candidato não tem prova agendada e, se for verdadeiro, remove-o da lista de PNE.
     *
     * @param User $user O usuário a ser removido da lista de PNE.
     * @return JsonResponse Com um JSON contendo o status da operação e uma mensagem.
     */
    public function clearPneFromListFromList(User $user): JsonResponse
    {
        if (ExamResult::exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível remover este candidato desta lista, pois existe prova agendada!'
            ]);
        }

        $user->update(['pne' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Candidato removido da list com sucesso!'
        ]);
    }

    // Método para obtenção dos dados do local de realização de prova do candidato (DÚVIDA SE SERÁ USADO NO FUTURO)
    public function showExamLocation(): View
    {
        $user = Auth::user();

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
}
