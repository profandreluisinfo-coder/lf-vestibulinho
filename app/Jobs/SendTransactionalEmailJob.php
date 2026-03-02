<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\MailService;

class SendTransactionalEmailJob implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public function __construct(
    //     protected string $to,
    //     protected string $subject,
    //     protected array $content,
    //     protected string $view
    // ) {}

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $to,
        protected string $subject,
        protected array $content,
        protected string $view,
        protected ?string $attachment = null // 👈 ADICIONE ISSO
    ) {}

    /**
     * Handle the job.
     *
     * Envia o e-mail transactional para o endere o especificado.
     *
     * @return void
     */
    // public function handle(): void
    // {
    //     app(MailService::class)->send(
    //         to: $this->to,
    //         subject: $this->subject,
    //         content: $this->content,
    //         view: $this->view
    //     );
    // }

    public function handle(): void
    {
        // app(MailService::class)->send(
        //     to: $this->to,
        //     subject: $this->subject,
        //     content: $this->content,
        //     view: $this->view,
        //     attachment: $this->attachment // 👈 AQUI
        // );

        $sent = app(MailService::class)->send(
            to: $this->to,
            subject: $this->subject,
            content: $this->content,
            view: $this->view,
            attachment: $this->attachment
        );

        // 👇 Se enviou e existe anexo, apaga
        if ($sent && $this->attachment && file_exists($this->attachment)) {
            unlink($this->attachment);
        }
    }
}