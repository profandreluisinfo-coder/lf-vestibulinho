<?php

namespace App\Console\Commands;

use App\Jobs\SendInscriptionReminderMailJob;
use App\Models\Process;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendInscriptionReminderEmails extends Command
{
    protected $signature = 'emails:send-inscription-reminders
                            {--days=3 : Dias mínimos desde a confirmação do e-mail}
                            {--limit=20 : Máximo de e-mails por execução}';

    protected $description = 'Lembra por e-mail quem confirmou o cadastro há X dias e ainda não fez a inscrição.';

    public function handle(): int
    {
        $process = Process::current();

        // Só roda dentro do período de inscrições (status "open" + data atual entre início e fim)
        if (! $process || ! $process->isInscriptionOpen()) {
            // Log::info('Lembretes de inscrição: fora do período de inscrições, nada a enviar.');
            $this->info('Fora do período de inscrições.');
            return Command::SUCCESS;
        }

        $event = $process->latestEvent;
        $days  = (int) $this->option('days');
        $limit = (int) $this->option('limit');

        $users = User::query()
            ->where('role', 'user')
            ->whereNotNull('email_verified_at')
            ->where('email_verified_at', '<=', now()->subDays($days))
            // sem inscrição neste processo seletivo
            ->whereDoesntHave('inscription', fn ($q) => $q->where('process_id', $process->id))
            // não reenviar: só quem nunca recebeu (ou recebeu antes deste período de inscrições)
            ->where(function ($q) use ($event) {
                $q->whereNull('inscription_reminder_sent_at')
                  ->orWhere('inscription_reminder_sent_at', '<', $event->start->copy()->startOfDay());
            })
            ->orderBy('email_verified_at')
            ->limit($limit)
            ->get();

        if ($users->isEmpty()) {
            // Log::info('Lembretes de inscrição: nenhum candidato pendente.');
            $this->info('Nenhum candidato pendente.');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            SendInscriptionReminderMailJob::dispatch(
                userId: $user->id,
                processId: $process->id,
                subject: 'Complete sua inscrição – Vestibulinho LF ' . $process->year,
                end: $event->end,
                url: config('app.url'),
            );
        }

        // Log::info("Lembretes de inscrição disparados: {$users->count()}.");
        $this->info("Disparados {$users->count()} lembretes.");

        return Command::SUCCESS;
    }
}

// CRON (HostGator) – 1x por dia, em horário diferente do envio dos locais de prova:
// 0 9 * * * /usr/local/bin/php /home/leand581/vestibulinho.leandrofranceschini.com.br/artisan emails:send-inscription-reminders >> /dev/null 2>&1