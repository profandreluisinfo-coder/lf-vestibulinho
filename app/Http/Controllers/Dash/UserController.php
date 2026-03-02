<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

        return view('dash.user.index', compact('users'));
    }

    /**
     * Exibe a página inicial do painel de administração do usuário.
     *
     * Verifica se o período de inscrição está aberto e exibe a página correspondente.
     *
     * @return \Illuminate\View\View
     */
    public function home(): View
    {
        $user = Auth::user();

        $calendar = Calendar::first() ?? new Calendar();

        if ($calendar?->isInscriptionOpen()) {
            return view('dash.user.home', compact('user'));
        }

        return view('dash.user.end-period-of-inscription', compact('user'));
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
}
