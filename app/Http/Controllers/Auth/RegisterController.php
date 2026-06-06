<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Setting;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Mostra a página de registro de dados de acesso para o usuário.
     *
     * Caso o calendário do Processo Seletivo esteja aberto, a página de registro será exibida.
     * Caso contrário, o usuário será redirecionado para a página de início.
     *
     * @return View|RedirectResponse
     */
    public function register(): View | RedirectResponse
    {
        $settings = Setting::first();
        $calendar = Calendar::first() ?? new Calendar();

        if (!$settings?->calendar) {
            return alertError('O período de inscrições para o Processo Seletivo ainda não está aberto. Por favor, aguarde o início das inscrições para criar sua conta.');
        }
        
        if (!$calendar?->isInscriptionOpen()) {
             return alertError('O período de inscrições para o Processo Seletivo está encerrado.');
        }

        return view('register.create');
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

        return view('register.email-sent', ['email' => $result['user']->email]);
    }
}