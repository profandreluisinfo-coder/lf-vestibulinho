<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Calendar;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * * Usuário que inicia sessão no sistema
     * 
     * @param Request $request
     * @return View|The home view if 
     * @throws \Illuminate\Auth\AccessDeniedException
     * @throws \Illuminate\Http\Request\Exception
     */
    public function login(): View | RedirectResponse
    {
        $calendar = Calendar::first();

        if (!$calendar->hasInscriptionStarted()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Autentica o usuário com base nas credenciais informadas.
     * Se a autenticação for bem sucedida, o usuário será redirecionado para a página
     * baseada no seu papel. Caso contrário, será exibido um erro.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'password.required' => 'O campo senha é obrigatório',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->email_verified_at) {
                Auth::logout();
                return redirect()
                    ->back()
                    ->with('warning', 'Para acessar a Área do Candidato, você precisa validar o endereço de e-mail vinculado ao seu registro.');
            }

            $request->session()->regenerate();
            $user->last_login_at = now();
            $user->save();

            return $this->redirectUserBasedOnRole($user);
        }

        return redirect()->back()->with('error', 'Dados inválidos.');
    }

    /**
     * Redireciona o usuário para a rota baseada no seu papel.
     * Se o papel do usuário for 'admin', ele será redirecionado para a rota 'admin.painel'.
     * Se o papel do usuário for 'user', ele será redirecionado para a rota 'dashboard'.
     * Caso contrário, ele será redirecionado para a rota 'login' com um erro.
     *
     * @param User $user
     * @return RedirectResponse
     */
    protected function redirectUserBasedOnRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.painel'),
            'user'  => redirect()->route('dashboard'),
            default => redirect()->route('login')->with([
                'status' => [
                    'alert-type' => 'danger',
                    'message' => 'Perfil de usuário desconhecido.',
                ]
            ])
        };
    }

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
        $calendar = Calendar::first();

        if (!$calendar->isInscriptionOpen()) {
            return redirect()->route('home');
        }

        return view('auth.register');
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
    public function store(Request $request, UserService $userService)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{6,8}$/',
            'password_confirmation' => 'required',
            'terms' => 'required'
        ], [
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'password.required' => 'O campo senha é obrigatório',
            'password.confirmed' => 'As senhas devem ser iguais',
            'password.regex' => 'A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.',
            'password_confirmation.required' => 'O campo repetir senha é obrigatório',
            'terms.required' => 'É necessário aceitar os termos do edital.'
        ]);

        $result = $userService->register($credentials);

        if (!$result['success']) {
            return redirect()->route('register')->with('error', $result['message']);
        }

        return view('messages.email-sent', [
            'email' => $result['user']->email
        ]);
    }

    /**
     * Verifica se o token informado é válido e, se for, redireciona o usuário para a página de confirmação de e-mail.
     * Se o token for inválido, o usuário será redirecionado para a página de início.
     * Se o endereço de e-mail informado pelo token já foi validado, o usuário será redirecionado para a página de início com um erro.
     * Se o endereço de e-mail informado pelo token for válido e nunca validado, o endereço de e-mail será armazenado em sessão e o usuário será redirecionado para a página de confirmação de e-mail.
     *
     * @param string $token
     * @param UserService $userService
     * @return RedirectResponse
     */
    public function verify(string $token, UserService $userService)
    {
        $result = $userService->verifyEmailToken($token);

        if (!$result['success']) {
            return redirect()->route('login');
        }

        if (!empty($result['already_verified'])) {
            return redirect()->route('login')->with([
                'status' => [
                    'alert-type' => 'danger',
                    'message' => 'O endereço de e-mail <strong>' . $result['user']->email . '</strong> já foi validado!'
                ]
            ]);
        }

        session()->flash('email', $result['user']->email);

        return view('messages.email-confirmed');
    }

    /**
     * Exibe a página de redefinição de senha.
     *
     * @return View
     */
    public function forgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Reenvia um e-mail para o usuário com um link para redefinição de senha.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function forgotPasswordAction(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O campo e-mail é obrigatório',
            'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido',
        ]);

        $result = app(UserService::class)->forgotPassword($credentials['email']);

        return redirect()->back()->with('success', $result['message']);
    }

    /**
     * Exibe a página de redefinição de senha com base no token informado.
     *
     * Se o token for inválido, o usuário será redirecionado para a página de início.
     * Se o token for válido, o endereço de e-mail armazenado no token será exibido na página de redefinição de senha.
     *
     * @param string $token
     * @return RedirectResponse|View
     */
    public function resetPassword($token): RedirectResponse|View
    {
        $user = User::where('token', $token)->first();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reenvia um e-mail para o usuário com um link para redefinição de senha com base no token informado.
     *
     * Valida os campos 'token', 'password' e 'password_confirmation' e
     * tenta redefinir a senha do usuário com base nas credenciais informadas.
     * Se a redefinição for bem sucedida, o usuário receberá um e-mail para confirmar o endereço de e-mail.
     * Caso contrário, será exibido um erro.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function resetPasswordAction(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'token' => 'required',
            'password' => 'required|regex:/^(?=.*[A-Z])(?=.*[a-z])[A-Za-z0-9]{6,8}$/',
            'password_confirmation' => 'required|same:password',
        ], [
            'password.required' => 'O campo senha é obrigatório',
            'password.regex' => 'A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.',
            'password_confirmation.required' => 'O campo confirmação de senha é obrigatório',
            'password_confirmation.same' => 'As senhas devem ser iguais',
        ]);

        $result = app(UserService::class)->resetPassword($credentials['token'], $credentials['password']);

        if (!$result['success']) {
            return redirect()->route('login')->with('warning', $result['message']);
        }

        session()->invalidate();

        return redirect()->route('login')->with('success', $result['message']);
    }

    public function resendEmail(): View
    {
        return view('auth.resend-email-verification');
    }

    public function resendEmailAction(Request $request, UserService $userService): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O campo e-mail é obrigatório',
            'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido',
        ]);

        $response = $userService->resendEmail($credentials['email']);

        return redirect()->route('resend.email')->with($response['success'] ? 'success' : 'warning', $response['message']);
    }

    public function adminLogin(): View
    {
        return view('auth.admin');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $redirectRoute = 'login'; // rota padrão

        if ($user && $user->role === 'admin') {
            $redirectRoute = 'admin.login';
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute)->with([
            'status' => [
                'alert-type' => 'success',
                'message' => 'Logout efetuado com sucesso!',
            ]
        ]);
    }

    /**
     * Atualiza a senha do usuário autenticado
     *
     * Valida os campos 'current_password', 'new_password' e 'password_confirmation'
     * e atualiza a senha do usuário autenticado se a senha atual for fornecida corretamente
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request, UserService $userService): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9]{6,8}$/|different:current_password',
            'password_confirmation' => 'required|same:new_password',
        ], [
            'current_password.required' => 'O campo senha atual é obrigatório',
            'new_password.required' => 'O campo nova senha é obrigatório',
            'new_password.regex' => 'A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.',
            'new_password.different' => 'A nova senha deve ser diferente da senha atual.',
            'password_confirmation.required' => 'O campo confirmação de senha é obrigatório',
            'password_confirmation.same' => 'As senhas devem ser iguais',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Senha atual incorreta!');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        $response = $userService->passwordChanged($user);

        return back()->with(
            $response['success'] ? 'success' : 'warning',
            $response['message']
        );
    }
}