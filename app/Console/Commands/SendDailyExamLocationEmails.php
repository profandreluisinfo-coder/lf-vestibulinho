<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\ExamResult;
use Illuminate\Console\Command;
use App\Jobs\SendExamLocationMailJob;
use App\Models\Process;

class SendDailyExamLocationEmails extends Command
{
    protected $signature = 'emails:send-daily-exam-locations';

    protected $description = 'Envia diariamente um lote de emails com o local de prova para os candidatos.';

    public function handle()
    {
        $limit = 225;

        // Log::info("Verificando permissão para envio diário...");

        // 🔎 Busca as configurações
        $settings = Setting::first() ?? new Setting();

        // ❌ Se o admin NÃO liberou o acesso, não envia nada
        if (!$settings || !$settings->location) {
            return Command::SUCCESS;
        }

        // 🔎 Carrega calendário
        $process = Process::latest('id')->first() ?? new Process();

        // 🔎 Busca exam_results ainda não enviados
        $results = ExamResult::whereNull('email_sent_at')
            ->with(['inscription.user.lgbt', 'examLocation'])
            ->limit($limit)
            ->get();

        if ($results->isEmpty()) {
            return Command::SUCCESS;
        }

        foreach ($results as $result) {
            $user = $result->inscription->user;
            $lgbt = $user->lgbt;

            $name = $lgbt?->status === \App\Models\Lgbt::STATUS_ACCEPTED
                ? $lgbt->name
                : $user->name;

            SendExamLocationMailJob::dispatch(
                email: $user->email,
                subject: 'Local de Prova – Vestibulinho LF ' . ($process?->year ?? now()->year),
                content: [
                    'name' => $name,
                    'date' => $result->exam_date,
                    'time' => $result->exam_time,
                    'location' => $result->examLocation->name,
                    'address' => $result->examLocation->address,
                    'room_number' => $result->room_number,
                ],
                view: 'emails.exam.location'
            );

            $result->update(['email_sent_at' => now()]);
        }

        $this->info("Enviados {$results->count()} emails hoje.");

        return Command::SUCCESS;
    }
}

// Adicionar o cron para executar 1x por dia
// No painel da HostGator, adicionar um novo cron:
// 0 8 * * * /usr/local/bin/php /home/leand581/vestibulinho.leandrofranceschini.com.br/artisan emails:send-daily-exam-locations >> /dev/null 2>&1