<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\Calendar;
use App\Models\ExamResult;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendExamLocationMailJob;

class SendDailyExamLocationEmails extends Command
{
    protected $signature = 'emails:send-daily-exam-locations';

    protected $description = 'Envia diariamente um lote de emails com o local de prova para os candidatos.';

    public function handle()
    {
        $limit = 225;

        Log::info("Verificando permissão para envio diário...");

        // 🔎 Busca as configurações
        $settings = Setting::first() ?? new Setting();

        // ❌ Se o admin NÃO liberou o acesso, não envia nada
        if (!$settings || !$settings->location) {
            Log::warning("Envio bloqueado: acesso aos locais de prova ainda não liberado pelo admin.");
            $this->info("Envio bloqueado. Libere o acesso aos locais de prova para iniciar o envio.");
            return Command::SUCCESS;
        }

        Log::info("Acesso liberado! Iniciando processamento de envios...");

        // 🔎 Carrega calendário
        $calendar = Calendar::latest('id')->first() ?? new Calendar();

        // 🔎 Busca exam_results ainda não enviados
        $results = ExamResult::whereNull('email_sent_at')
            ->with(['inscription.user', 'examLocation'])
            ->limit($limit)
            ->get();

        if ($results->isEmpty()) {
            Log::info("Nenhum email pendente.");
            $this->info("Nenhum email pendente.");
            return Command::SUCCESS;
        }

        foreach ($results as $result) {
            $user = $result->inscription->user;

            SendExamLocationMailJob::dispatch(
                email: $user->email,
                subject: 'Local de Prova – Vestibulinho ' . ($selection_process?->year ?? now()->year),
                content: [
                    'name' => $user->name,
                    'date' => $result->exam_date,
                    'time' => $result->exam_time,
                    'location' => $result->examLocation->name,
                    'address' => $result->examLocation->address,
                    'room_number' => $result->room_number,
                ],
                view: 'emails.location'
            );

            $result->update(['email_sent_at' => now()]);
        }

        Log::info("Lote diário enviado com sucesso: {$results->count()} emails.");

        $this->info("Enviados {$results->count()} emails hoje.");

        return Command::SUCCESS;
    }
}

// Adicionar o cron para executar 1x por dia
// No painel da HostGator, adicionar um novo cron:
// 0 8 * * * /usr/local/bin/php /home/leand581/vestibulinho.leandrofranceschini.com.br/artisan emails:send-daily-exam-locations >> /dev/null 2>&1