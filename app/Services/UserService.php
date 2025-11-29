<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use App\Services\MailService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendVerificationEmailJob;

class UserService
{
    protected MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function register(array $data): array
    {
        $email = strtolower($data['email']);

        if (User::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return [
                'success' => false,
                'message' => 'Não foi possível concluir o cadastro. Verifique os dados informados.',
            ];
        }

        $user = new User();
        $user->email = $email;
        $user->password = Hash::make($data['password']);
        $user->token = Str::random(64);

        $link = route('verify', ['token' => $user->token]);

        $emailEnviado = $this->mailService->send(
            $user->email,
            'Confirme seu e-mail',
            ['link' => $link],
            'mail.verify-email'
        );

        if (!$emailEnviado) {
            return [
                'success' => false,
                'message' => 'Falha ao enviar e-mail de verificação!',
            ];
        }

        $user->save();

        return [
            'success' => true,
            'user' => $user,
        ];
    }

    public function verifyEmailToken(string $token): array
    {
        $user = User::where('token', $token)->lockForUpdate()->first();

        if (!$user) {
            return ['success' => false, 'message' => 'Token inválido.'];
        }

        if ($user->email_verified_at) {
            return ['success' => false, 'already_verified' => true, 'user' => $user];
        }

        $user->email_verified_at = Carbon::now();
        $user->token = null;
        $user->save();

        try {
            $this->mailService->send(
                $user->email,
                'E-mail confirmado',
                ['name' => $user->name],
                'mail.confirmed'
            );

            return ['success' => true, 'user' => $user];
        } catch (\Exception $e) {
            return ['success' => true, 'user' => $user, 'mail_error' => true];
        }
    }

    public function forgotPassword(string $email): array
    {
        $user = User::where('email', $email)->first();

        // Retorna sucesso mesmo que não encontre usuário, pra não vazar informação
        if (!$user) {
            return [
                'success' => true,
                'message' => 'Verifique a sua caixa de e-mail principal ou spam para prosseguir com a redefinição da senha.'
            ];
        }

        $user->token = Str::random(32);
        $user->save();

        $link = route('reset.password', ['token' => $user->token]);

        $emailEnviado = $this->mailService->send(
            $user->email,
            'Redefinir senha',
            ['name' => $user->social_name ?? $user->name, 'link' => $link],
            'mail.reset-password'
        );

        if (!$emailEnviado) {
            Log::error("Erro ao enviar e-mail para redefinição de senha do usuário {$user->email}");
            // Pode escolher retornar erro aqui ou só logar e retornar sucesso mesmo
        }

        return [
            'success' => true,
            'message' => 'E-mail enviado com sucesso! Verifique a sua caixa de e-mail principal ou spam para prosseguir com a redefinição da senha.'
        ];
    }

    public function resetPassword(string $token, string $newPassword): array
    {
        $user = User::where('token', $token)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Token inválido ou expirado.',
            ];
        }

        $user->password = Hash::make($newPassword);
        $user->token = null;
        $user->save();

        $this->mailService->send(
            $user->email,
            'Senha alterada',
            ['name' => $user->social_name ?? $user->name],
            'mail.password-changed'
        );

        return [
            'success' => true,
            'message' => 'Senha alterada.',
            'user' => $user,
        ];
    }

    public function resendEmail(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => true,
                'message' => 'Verifique a sua caixa de e-mail principal ou spam para prosseguir com a validação do e-mail.',
            ];
        }

        if ($user->email_verified_at) {
            return [
                'success' => true,
                'message' => 'Verifique a sua caixa de e-mail principal ou spam para prosseguir com a verificação do e-mail.',
            ];
        }

        if ($user->token_expires_at && now()->lt($user->token_expires_at)) {
            $remaining = ceil(now()->diffInMinutes($user->token_expires_at));
            return [
                'success' => false,
                'message' => "Você precisa aguardar {$remaining} " . Str::plural('minuto', $remaining) . " antes de solicitar um novo e-mail.",
            ];
        }

        // gera o token
        $user->token = Str::random(64);
        $user->token_expires_at = now()->addMinutes(5);

        $link = route('verify', ['token' => $user->token]);

        // dispara o job
        dispatch(new SendVerificationEmailJob(
            email: $user->email,
            // name: $user->social_name ?? $user->name,
            link: $link
        ));

        // salva token no banco
        $user->save();

        return [
            'success' => true,
            'message' => 'Solicitação registrada! Em instantes você receberá um e-mail para finalizar a verificação.',
        ];
    }
    
    /**
     * Envia um e-mail para o usuário informando que a senha foi alterada com sucesso.
     *
     * @param User $user
     * @return array
     */
    public function passwordChanged(User $user)
    {
        $this->mailService->send(
            $user->email,
            'Senha alterada',
            ['name' => $user->social_name ?? $user->name],
            'mail.password-changed'
        );

        return [
            'success' => true,
            'message' => 'Senha alterada.',
            'user' => $user,
        ];
    }
}
