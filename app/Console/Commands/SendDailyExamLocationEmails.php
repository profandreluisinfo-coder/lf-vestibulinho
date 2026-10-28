<?php

namespace App\Console\Commands;

use App\Jobs\SendExamLocationMailJob;
use App\Models\Exam;
use App\Models\Lgbt;
use App\Models\Process;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailyExamLocationEmails extends Command
{
    protected $signature = 'emails:send-daily-exam-locations';

    protected $description = 'Envia diariamente um lote de emails com o local de prova para os candidatos.';

    private const DAILY_LIMIT = 225;

    public function handle(): int
    {
        $process = Process::current();

        // Sem processo, ou com o local ainda não liberado pelo admin, não envia nada
        if (! $process?->isLocationReleased()) {
            return Command::SUCCESS;
        }

        // Segunda camada: a data ou a hora podem ter sido apagadas depois da liberação.
        // Log, e não $this->error(), porque o cron descarta a saída do terminal.
        if (! $process->exam_date || ! $process->exam_time) {
            Log::warning('Envio do local de prova cancelado: data ou hora da prova não definidas no processo.');

            return Command::FAILURE;
        }

        // Alocações deste processo cujo e-mail ainda não foi enviado
        $exams = Exam::whereNull('email_sent_at')
            ->whereHas('inscription', fn ($q) => $q->forProcess($process->id))
            ->with(['inscription.user.lgbt', 'location'])
            ->orderBy('id')
            ->limit(self::DAILY_LIMIT)
            ->get();

        if ($exams->isEmpty()) {
            return Command::SUCCESS;
        }

        foreach ($exams as $exam) {
            $user = $exam->inscription->user;
            $lgbt = $user->lgbt;

            $name = $lgbt?->status === Lgbt::STATUS_ACCEPTED
                ? $lgbt->name
                : $user->name;

            SendExamLocationMailJob::dispatch(
                email: $user->email,
                subject: 'Local de Prova – Vestibulinho LF ' . ($process->year ?? now()->year),
                content: [
                    'name' => $name,
                    'date' => $process->exam_date,   // data e hora vêm do processo
                    'time' => $process->exam_time,
                    'location' => $exam->location->name,
                    'address' => $exam->location->address,
                    'room_number' => $exam->room_number,
                ],
                view: 'emails.exam.location'
            );

            // forceFill: dispensa colocar email_sent_at no $fillable de Exam.
            // Sem isso, um update() comum seria ignorado e o mesmo lote seria reenviado todo dia.
            $exam->forceFill(['email_sent_at' => now()])->save();
        }

        $this->info("Enviados {$exams->count()} emails hoje.");

        return Command::SUCCESS;
    }
}

// Adicionar o cron para executar 1x por dia
// No painel da HostGator, adicionar um novo cron:
// 0 8 * * * /usr/local/bin/php /home/leand581/vestibulinho.leandrofranceschini.com.br/artisan emails:send-daily-exam-locations >> /dev/null 2>&1