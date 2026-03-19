<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Exibe a página inicial do painel de administração do usuário.
     *
     * Verifica se o período de inscrição está aberto e exibe a página correspondente.
     *
     * @return \Illuminate\View\View
     */
    public function start(): View
    {
        $user = Auth::user();

        $calendar = Calendar::first() ?? new Calendar();

        if ($calendar?->isInscriptionOpen()) {
            return view('dash.start', compact('user'));
        }

        return view('dash.end-period-of-inscription', compact('user'));
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
                ->route('dash.user.start')
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

        return view('dash.inscription', compact('user', 'exam', 'examResult', 'call'));
    }

}