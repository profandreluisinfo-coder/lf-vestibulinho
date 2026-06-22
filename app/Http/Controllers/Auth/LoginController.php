<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SelectionProcess;
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
     * @return View|RedirectResponse A view da página de login ou um redirecionamento para a página inicial se as inscrições ainda não 
     * tiverem começado o período de inscrições.
     */
    public function login(): View | RedirectResponse
    {
        $selection_process_status = SelectionProcess::current();
        
        if (!$selection_process_status?->status) {
            return alertError('O período de inscrições para o Processo Seletivo ainda não foi definido. Por favor, aguarde!');
        }

        return view('user.auth.login');
    }

    /**
     * Mostra a página de login para o administrador.
     *
     * @return View
     */
    public function loginForAdmin(): View
    {
        return view('admin.auth.login');
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
            return route('admin.index');
        }

        if ($user->role === 'user') {
            return $user->inscription()->exists()
                ? route('inscription.user.show') // O usuário não possui inscrição
                : route('inscription.step.start'); // O usuário possui inscrição
        }

        return route('login');
    }
}