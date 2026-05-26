<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailController extends Controller
{
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

        return view('register.email-confirmed');
    }

    /**
     * Exibe a página de reenvio de e-mail de verificação.
     *
     * @return View
     */
    public function resendEmail(): View
    {
        return view('register.resend-email');
    }

    /**
     * Reenvia um e-mail para o usuário com um link para confirmação de e-mail.
     * Valida o campo 'email' e tenta reenviar uma mensagem para o usuário contendo um token.
     *
     * @param Request $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function resendEmailAction(Request $request, UserService $userService): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email'    => 'O campo e-mail deve ser um endereço de e-mail válido.',
        ]);

        $response = $userService->resendEmail($credentials['email']);

        return response()->json(
            ['message' => $response['message']],
            $response['success'] ? 200 : 422
        );
    }
}