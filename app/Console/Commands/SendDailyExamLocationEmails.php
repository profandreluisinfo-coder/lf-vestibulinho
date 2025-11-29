<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamResult;
use App\Models\Calendar;
use App\Jobs\SendExamLocationMailJob;
use Illuminate\Support\Facades\Log;

class SendDailyExamLocationEmails extends Command
{
    protected $signature = 'emails:send-daily-exam-locations';

    protected $description = 'Envia diariamente um lote de emails com o local de prova para os candidatos.';

    public function handle()
    {
        $limit = 300; // ajuste se quiser

        Log::info("📬 Iniciando envio diário de emails (limit = {$limit})");

        // 🔎 Carrega o calendário
        $calendar = Calendar::first();

        // 🔎 Busca exam_results ainda não enviados
        $results = ExamResult::whereNull('email_sent_at')
            ->with(['inscription.user', 'examLocation'])
            ->limit($limit)
            ->get();

        if ($results->isEmpty()) {
            Log::info("📬 Nenhum email para enviar hoje.");
            $this->info("Nenhum email pendente.");
            return Command::SUCCESS;
        }

        foreach ($results as $result) {

            $user = $result->inscription->user;

            SendExamLocationMailJob::dispatch(
                email: $user->email,
                subject: 'Local de Prova – Vestibulinho ' . ($calendar->year ?? now()->year),
                content: [
                    'name' => $user->name,
                    'date' => $result->exam_date,
                    'time' => $result->exam_time,
                    'location' => $result->examLocation->name,
                    'address' => $result->examLocation->address,
                    'room_number' => $result->room_number,
                ],
                view: 'mail.exam_location_info'
            );

            // Marca como enviado
            $result->update(['email_sent_at' => now()]);
        }

        Log::info("📬 Lote diário enviado com sucesso: {$results->count()} emails.");

        $this->info("Enviados {$results->count()} emails hoje.");

        return Command::SUCCESS;
    }
}

// Adicionar o cron para executar 1x por dia
// No painel da HostGator, adicionar um novo cron:
// 0 8 * * * /usr/local/bin/php /home/leand581/vestibulinho.leandrofranceschini.com.br/artisan emails:send-daily-exam-locations >> /dev/null 2>&1