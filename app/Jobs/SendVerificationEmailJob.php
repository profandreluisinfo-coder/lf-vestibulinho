<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class SendVerificationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $email,
        // protected string $name,
        protected string $link
    ) {}

    public function handle(): void
    {
        app(\App\Services\MailService::class)->send(
            to: $this->email,
            subject: 'Verificação de e-mail',
            // content: ['name' => $this->name, 'link' => $this->link],
            content: ['link' => $this->link],
            view: 'mail.verify'
        );
    }
}