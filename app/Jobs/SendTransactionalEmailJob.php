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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $to,
        protected string $subject,
        protected array $content,
        protected string $view
    ) {}

    public function handle(): void
    {
        app(MailService::class)->send(
            to: $this->to,
            subject: $this->subject,
            content: $this->content,
            view: $this->view
        );
    }
}