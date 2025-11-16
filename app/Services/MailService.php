<?php

namespace App\Services;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class MailService
{
    public function send(
        string $to,
        string $subject,
        array $content,
        string $view,
        ?string $attachment = null,
        bool $queue = false
    ): bool {
        try {
            $message = new SendMail($subject, $content, $view, $attachment);

            if ($queue) {
                Mail::to($to)->queue($message);
            } else {
                Mail::to($to)->send($message);
            }

            // Log::info("E-mail enviado para: {$to} - Assunto: {$subject}");
            Log::info("E-mail enviado para: {$to} - Assunto: {$subject} - Conteúdo: " . json_encode($content, JSON_UNESCAPED_UNICODE));

            return true;
        } catch (\Exception $e) {
            Log::error("Erro ao enviar e-mail para {$to}: {$e->getMessage()}");
            return false;
        }
    }

    public function sendToMany(
        array|Collection $emails,
        string $subject,
        array $content,
        string $view,
        ?string $attachment = null,
        bool $queue = false
    ): array {
        $success = [];
        $failures = [];

        foreach ($emails as $email) {
            $sent = $this->send(
                to: $email,
                subject: $subject,
                content: $content,
                view: $view,
                attachment: $attachment,
                queue: $queue
            );

            if ($sent) {
                $success[] = $email;
            } else {
                $failures[] = $email;
            }
        }

        Log::info("Envio em massa concluído. Sucesso: " . count($success) . ", Falha: " . count($failures));
        return [
            'success' => $success,
            'failures' => $failures,
        ];
    }
}