<?php

namespace App\Jobs;

use App\Services\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendExamLocationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $email;
    public string $subject;
    public array $content;
    public string $view;

    public function __construct(string $email, string $subject, array $content, string $view)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->content = $content;
        $this->view = $view;
    }

    public function handle(): void
    {
        try {
            app(MailService::class)->send(
                to: $this->email,
                subject: $this->subject,
                content: $this->content,
                view: $this->view,
                queue: false
            );

            Log::info("Enviado: {$this->email}");

        } catch (\Throwable $e) {
            Log::error("Erro ao enviar para {$this->email}: {$e->getMessage()}");
            $this->fail($e);
        }
    }
}