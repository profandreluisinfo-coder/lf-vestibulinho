<?php

namespace App\Jobs;

use Illuminate\Queue\Middleware\WithoutOverlapping;
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

    protected string $email;
    protected string $subject;
    protected array $content;
    protected string $view;
    protected ?string $attachment;

    /**
     * Cria uma nova instância do Job.
     */
    public function __construct(string $email, string $subject, array $content, string $view, ?string $attachment = null)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->content = $content;
        $this->view = $view;
        $this->attachment = $attachment;
    }

    /**
     * Executa o Job.
     */
    public function handle(): void
    {
        Log::info("⏰ Cron rodando e processando job: {$this->email}");
        try {
            app(MailService::class)->send(
                to: $this->email,
                subject: $this->subject,
                content: $this->content,
                view: $this->view,
                attachment: $this->attachment,
                queue: false // já estamos dentro de um Job
            );

            Log::info("E-mail enviado com sucesso via Job: {$this->email}");
        } catch (\Throwable $e) {
            Log::error("Erro ao enviar e-mail via Job para {$this->email}: {$e->getMessage()}");
            $this->fail($e);
        }
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->email))
                ->releaseAfter(60)
        ];
    }
}