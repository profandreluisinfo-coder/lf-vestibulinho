<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
// use App\Models\ExamResult;
// use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function index(): View
    {
        $user = Auth::user();

        $calendar = Calendar::first() ?? new Calendar();

        if ($calendar?->isInscriptionOpen()) {
            return view('dash.start', compact('user'));
        }

        return view('dash.end-period-of-inscription', compact('user'));
    }

    /**
     * Mostra a página de registro de dados de acesso para o usuário.
     *
     * Caso o calendário do Processo Seletivo esteja aberto, a página de registro será exibida.
     * Caso contrário, o usuário será redirecionado para a página de início.
     *
     * @return View|RedirectResponse
     */
    public function create(): View | RedirectResponse
    {
        $calendar = Calendar::first() ?? new Calendar();

        if (empty($calendar) || !($calendar?->isInscriptionOpen())) {
            return alertError('Não é possível efetuar o registro no momento.');
        }

        return view('site.user.create');
    }

    /**
     * Realiza o registro do usuário com base nas credenciais informadas.
     *
     * Valida os campos 'email', 'password', 'password_confirmation' e 'terms' e
     * tenta registrar o usuário com base nas credenciais informadas.
     * Se o registro for bem sucedido, o usuário receberá um e-mail para confirmar o endereço de e-mail.
     * Caso contrário, será exibido um erro.
     *
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse|View
     */
    public function store(Request $request, UserService $userService): RedirectResponse|View
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{6,8}$/',
            'password_confirmation' => 'required'
        ], [
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'password.required' => 'O campo senha é obrigatório',
            'password.confirmed' => 'As senhas devem ser iguais',
            'password.regex' => 'A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.',
            'password_confirmation.required' => 'O campo repetir senha é obrigatório'
        ]);

        $result = $userService->register($credentials);

        if (!($result['success'])) {
            return redirect()->back()->with('error', $result['message']);
        }

        return view('emails.email-sent', ['email' => $result['user']->email]);
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
    public function show(): View | RedirectResponse
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