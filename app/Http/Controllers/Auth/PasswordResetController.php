<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
     /**
     * Reenvia um e-mail para o usuário com um link para redefinição de senha.
     * Valida os campos 'email' e tenta reenviar um e-mail para o usuário com base no token informado.
     * Se o token for inválido, o usuário será redirecionado para a página de início.
     * Se o token for válido, o endereço de e-mail armazenado no token será exibido na página de redefinição de senha.
     *
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse
     */
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
