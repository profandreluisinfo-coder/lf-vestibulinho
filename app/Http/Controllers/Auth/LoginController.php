<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Calendar;
use Illuminate\View\View;
use App\Models\User;

class LoginController extends Controller
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
        $calendar = Calendar::first() ?? new Calendar();
        
        if (!$calendar->hasInscriptionStarted()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Mostra a página de login para o administrador.
     *
     * @return View
     */
    public function loginForAdmin(): View
    {
        return view('auth.admin');
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

            /** @var \App\Models\User $user */
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
     * Se o papel do usuário for 'admin', ele será redirecionado para a rota 'dash.admin.home'.
     * Se o papel do usuário for 'user', ele será redirecionado para a rota 'dashboard'.
     * Caso contrário, ele será redirecionado para a rota 'login' com um erro.
     *
     * @param User $user
     * @return RedirectResponse
     */
    protected function redirectUserBasedOnRole(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return redirect()->route('dash.admin.home');
        }

        if ($user->role === 'user') {
            // Verifica se o usuário possui inscrição
            $hasInscription = $user->inscription()->exists();

            return $hasInscription
                ? redirect()->route('dash.user.home') // Vai para o início da inscrição
                : redirect()->route('dash.user.inscription'); // Vai para o dashboard de inscrição
        }

        return redirect()->route('login')->with([
            'status' => [
                'alert-type' => 'danger',
                'message' => 'Perfil de usuário desconhecido.',
            ]
        ]);
    }
}