<?php

namespace App\Services;

use App\Jobs\SendTransactionalEmailJob;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function register(array $data): array
    {
        $email = strtolower($data['email']);

        if (User::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return [
                'success' => false,
                'message' => 'Não foi possível concluir o cadastro. Verifique os dados informados.',
            ];
        }

        try {
            $user = DB::transaction(function () use ($data, $email) {
                $user = new User();
                $user->email = $email;
                $user->password = Hash::make($data['password']);
                $user->token = Str::random(64);
                $user->save();

                $link = route('verify', ['token' => $user->token]);

                $this->sendEmail(
                    to: $user->email,
                    subject: 'Confirme seu e-mail',
                    data: ['link' => $link],
                    view: 'emails.verify'
                );

                return $user;
            });

            return [
                'success' => true,
                'user' => $user,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao enviar email de confirmação. Tente novamente.',
            ];
        }
    }

    /**
     * Verifica se o token informado é válido e, se for, confirma o endereço de e-mail do usuário.
     * Se o token for inválido, o usuário será redirecionado para a página de início com um erro.
     * Se o endereço de e-mail informado pelo token já foi validado, o usuário será redirecionado para a página de início com um erro.
     * Se o endereço de e-mail informado pelo token for válido e nunca validado, o endereço de e-mail será armazenado em sessão e o usuário será redirecionado para a página de confirmação de e-mail.
     *
     * @param string $token
     * @return array
     * */
    public function verifyEmailToken(string $token): array
    {
        $user = User::where('token', $token)->first();

        if (!$user) {
            return ['success' => false, 'message' => 'Token inválido.'];
        }

        if ($user->email_verified_at) {
            return ['success' => false, 'already_verified' => true, 'user' => $user];
        }

        $user->email_verified_at = Carbon::now();
        $user->token = null;

        $user->save();

        $this->sendEmail(
            to: $user->email,
            subject: 'E-mail confirmado',
            data: ['name' => $user->social_name ?? $user->name],
            view: 'emails.confirmation'
        );

        return ['success' => true, 'user' => $user];
    }

    public function forgotPassword(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => true,
                'message' => 'Verifique a sua caixa de e-mail principal ou spam...'
            ];
        }

        try {
            DB::transaction(function () use ($user) {
                $user->token = Str::random(32);
                $user->save();

                $link = route('reset.password', ['token' => $user->token]);

                $this->sendEmail(
                    to: $user->email,
                    subject: 'Redefinir senha',
                    data: ['name' => $user->social_name ?? $user->name, 'link' => $link],
                    view: 'emails.reset'
                );
            });

            return [
                'success' => true,
                'message' => 'E-mail enviado com sucesso!...'
            ];
        } catch (\Exception $e) {
            return [
                'success' => true,  // ← Mantém seguro (não vaza se existe)
                'message' => 'Verifique a sua caixa de e-mail...'
            ];
        }
    }
    /**
     * Redefine a senha do usuário com base no token informado.
     *
     * Valida os campos 'token' e 'new_password' e
     * tenta redefinir a senha do usuário com base nas credenciais informadas.
     * Se a redefinição for bem sucedida, o usuário receberá um e-mail para confirmar o endereço de e-mail.
     * Caso contrário, será exibido um erro.
     *
     * @param string $token
     * @param string $newPassword
     * @return array
     */
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

        $this->sendEmail(
            to: $user->email,
            subject: 'Senha alterada',
            data: ['name' => $user->social_name ?? $user->name],
            view: 'emails.password-changed'
        );

        return [
            'success' => true,
            'message' => 'Senha alterada.'
        ];
    }

    /**
     * Reenvia o link de verificação de e-mail para o usuário.
     *
     * Busca o usuário pelo e-mail informado e, caso exista e ainda não tenha
     * verificado o endereço, gera um novo token e envia o link de confirmação.
     * Retorna sucesso genérico quando o usuário não existe ou já está verificado,
     * evitando enumeração de contas.
     *
     * @param string $email
     * @return array{success: bool, message: string}
     */
    public function resendEmail(string $email): array
    {
        $user = User::where('email', $email)->first();

        // Retorna sucesso mesmo que não encontre usuário, pra não vazar informação
        if (!$user) {
            return [
                'success' => true,
                'message' => 'Verifique a sua caixa de e-mail principal ou spam para prosseguir com a validação do e-mail.',
            ];
        }

        // verifica se o email foi verificado
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

        // salva token no banco
        $user->save();

        // define o link de verificação
        $link = route('verify', ['token' => $user->token]);

        try {
            $this->sendEmail(
                to: $user->email,
                subject: 'Confirme seu e-mail',
                data: ['link' => $link],
                view: 'emails.verify'
            );
        } catch (\Throwable $e) {
            // Desfaz o token para permitir nova tentativa imediata
            $user->token = null;
            $user->token_expires_at = null;
            $user->save();

            return [
                'success' => false,
                'message' => 'Não foi possível enviar o e-mail. Tente novamente em instantes.',
            ];
        }
        // retorna sucesso
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
        $this->sendEmail(
            to: $user->email,
            subject: 'Senha alterada',
            data: ['name' => $user->social_name ?? $user->name],
            view: 'emails.password-changed'
        );

        return [
            'success' => true,
            'message' => 'Senha alterada com sucesso.'
        ];
    }

    private function sendEmail(
        string $to,
        string $subject,
        array $data,
        string $view,
        ?string $attachment = null // 👈 ADICIONE ISSO
    ) {
        dispatch(
            new SendTransactionalEmailJob(
                $to,
                $subject,
                $data,
                $view,
                $attachment // 👈 PASSE AQUI
            )
        )->delay(now()->addSeconds(10));
    }
}
