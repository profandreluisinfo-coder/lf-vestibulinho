<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\Inscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInscriptionReminderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public int $userId,
        public int $processId,
        public string $subject,
        public ?Carbon $end,
        public string $url,
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);

        if (! $user) {
            return;
        }

        // O candidato pode ter se inscrito entre o disparo e o envio
        $alreadyEnrolled = Inscription::where('user_id', $user->id)
            ->where('process_id', $this->processId)
            ->exists();

        if ($alreadyEnrolled) {
            return;
        }

        Mail::to($user->email)->send(new SendMail(
            subject: $this->subject,
            content: [
                'name' => $user->name,
                'end'  => $this->end,
                'url'  => $this->url,
            ],
            view: 'emails.inscription.reminder',
        ));

        // Só marca como enviado depois que o envio deu certo
        User::whereKey($user->id)->update(['inscription_reminder_sent_at' => now()]);
    }
}
