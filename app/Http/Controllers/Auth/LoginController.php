<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * * Usuário que inicia sessão no sistema
     * 
     * @param Request $request
     * @return View|RedirectResponse A view da página de login ou um redirecionamento para a página inicial se as inscrições ainda não 
     * tiverem começado o período de inscrições.
     */
    public function login(): View | RedirectResponse
    {
        $settings = Setting::first();

        if (!$settings?->calendar) {
            return alertError('O período de inscrições para o Processo Seletivo ainda não está aberto. Por favor, aguarde!');
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
    public function authenticate(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'O campo email é obrigatório',
            'email.email'       => 'O campo email deve ser um email válido',
            'password.required' => 'O campo senha é obrigatório',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {

            $user = Auth::user();

            if (!$user->email_verified_at) {
                Auth::logout();
                $msg = 'Para acessar a Área do Candidato, você precisa validar seu endereço de e-mail.';

                return $request->wantsJson()
                    ? response()->json(['success' => false, 'message' => $msg], 422)
                    : alertWarning($msg);
            }

            $request->session()->regenerate();
            $user->last_login_at = Carbon::now();
            $user->save();

            $redirectUrl = $this->getRedirectUrl($user);

            return $request->wantsJson()
                ? response()->json(['success' => true, 'redirect' => $redirectUrl])
                : redirect($redirectUrl);
        }

        $msg = 'Dados inválidos.';

        return $request->wantsJson()
            ? response()->json(['success' => false, 'message' => $msg], 422)
            : alertError($msg);
    }

    protected function getRedirectUrl(User $user): string
    {
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        }

        if ($user->role === 'user') {
            return $user->inscription()->exists()
                ? route('dash.user.start')
                : route('dash.user.inscription');
        }

        return route('login');
    }

    /**
     * Redireciona o usuário para a rota baseada no seu papel.
     * Se o papel do usuário for 'admin', ele será redirecionado para a rota 'admin.dashboard'.
     * Se o papel do usuário for 'user', ele será redirecionado para a rota 'dashboard'.
     * Caso contrário, ele será redirecionado para a rota 'login' com um erro.
     *
     * @param User $user
     * @return RedirectResponse
     */
    protected function redirectUserBasedOnRole(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'user') {
            // Verifica se o usuário possui inscrição
            $hasInscription = $user->inscription()->exists();

            return $hasInscription
                ? redirect()->route('dash.user.start') // Vai para o início da inscrição
                : redirect()->route('dash.user.inscription'); // Vai para o dashboard de inscrição
        }

        return alertError('Perfil de usuário desconhecido.');
    }
}
