<?php

namespace App\Jobs;

use Illuminate\Queue\Middleware\WithoutOverlapping;
use App\Services\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCallNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $email;
    protected string $subject;
    protected array $content;
    protected string $view;

    /**
     * Cria um novo job.
     */
    public function __construct(string $email, string $subject, array $content, string $view)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->content = $content;
        $this->view = $view;
    }

    /**
     * Executa o job.
     */
    public function handle(MailService $mailService): void
    {
        $mailService->send(
            to: $this->email,
            subject: $this->subject,
            content: $this->content,
            view: $this->view,
            queue: false // já estamos dentro de um job
        );
    }

    public function middleware(): array
    {
        return [
            // Evita que dois jobs com o mesmo e-mail rodem ao mesmo tempo
            (new WithoutOverlapping($this->email))
                ->releaseAfter(60) // tempo (em segundos) até o bloqueio expirar
        ];
    }
}