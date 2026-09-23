<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\CallList;
use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Inscription;
use App\Models\Process;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Process\Process as SymfonyProcess;

class SettingController extends Controller
{
    /**
     * Exibe a a view para a página de redefinição do sistema
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.system.index');
    }

    /**
     * Redefine o sistema
     *
     * Garante que só admin possa resetar o sistema.
     * Gera um backup completo do banco (.sql) antes de apagar qualquer coisa.
     * Apaga todos os usuários que não são admin.
     * Apaga todas as inscrições (e suas dependências) via CASCADE.
     * Ajusta AUTO_INCREMENT dos users.
     * Ajusta AUTO_INCREMENT das inscrições.
     * Zera vagas dos cursos.
     * Atualiza todos os registros da tabela de settings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(): JsonResponse
    {
        try {
            // Garante que só admin possa resetar o sistema
            if (Auth::user()?->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado. Somente administradores podem redefinir o sistema.'
                ], 403);
            }

            // ===== BACKUP COMPLETO DO BANCO (antes de apagar qualquer coisa) =====

            // Dados de conexão (pega automaticamente do seu .env)
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            $db = config('database.connections.mysql.database');
            $user = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            // Nome do arquivo com data e hora
            $fileName = 'backup_completo_' . now()->format('Y-m-d_H-i-s') . '.sql';

            // Caminho completo onde o arquivo vai ser salvo
            // $completePath = storage_path('app/backups/' . $fileName);
            $completePath = Storage::disk('local')->path('backups/' . $fileName);

            // Garante que a pasta "backups" existe
            // Storage::disk('local')->makeDirectory('backups');
            // Garante que a pasta "backups" existe (cria com força, se precisar)
            $backupDir = storage_path('app/backups');

            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true, true);
            }

            // Monta o comando do mysqldump
            $comand = [
                env('MYSQLDUMP_PATH', 'mysqldump'), // usa o caminho do .env, ou "mysqldump" como reserva
                '-h',
                $host,
                '-P',
                $port,
                '-u',
                $user,
                "--password={$password}",
                $db,
            ];

            // Executa o comando e salva SOMENTE a saída normal (o SQL de verdade) no arquivo
            $process = new SymfonyProcess($comand);
            $process->setTimeout(300); // 5 minutos, pra bancos grandes
            $process->run(function ($type, $output) use ($completePath) {
                // Só grava no arquivo se for saída normal (stdout)
                if ($type === SymfonyProcess::OUT) {
                    file_put_contents($completePath, $output, FILE_APPEND);
                }
                // Se for erro/aviso (stderr), simplesmente ignora aqui
            });

            if (!$process->isSuccessful()) {
                // Limpa o texto de erro pra evitar caracteres inválidos quebrando o JSON
                $erro = $this->limparUtf8($process->getErrorOutput());
                throw new \Exception('Falha ao gerar o backup: ' . $erro);
            }

            // ===== FIM DO BACKUP — a partir daqui, começa a remoção dos dados =====

            // Remove os registros na ordem das dependências, sem desativar FKs.
            Call::query()->delete();
            ExamResult::query()->delete();
            Inscription::query()->delete();
            CallList::query()->delete();
            Process::query()->delete();
            User::where('role', '!=', 'admin')->delete();

            // Ajusta AUTO_INCREMENT dos users
            $maxUserId = User::max('id');
            $nextUserId = $maxUserId ? $maxUserId + 1 : 1;
            DB::statement("ALTER TABLE users AUTO_INCREMENT = " . (int) $nextUserId);

            // Ajusta AUTO_INCREMENT das inscriptions
            $maxInscriptionId = DB::table('inscriptions')->max('id');
            $nextInscriptionId = $maxInscriptionId ? $maxInscriptionId + 1 : 1;
            DB::statement("ALTER TABLE inscriptions AUTO_INCREMENT = " . (int) $nextInscriptionId);

            // Zera vagas dos cursos
            Course::whereNotNull('vacancies')->update(['vacancies' => 0]);

            // Alterar para 'false' campos 'result' e 'location' da tabela de settings
            Setting::updateOrCreate(
                ['id' => 1],
                ['result' => false, 'location' => false]
            );

            Cache::forget('global_calendar');
            Cache::forget('global_process');
            Cache::forget('global_total_inscriptions');
            Cache::forget('global_users_without_inscription');
            Cache::forget('global_settings');
            Cache::forget('calls_exists');

            // Apagar os dados de autenticação
            session()->flush();

            return response()->json([
                'success' => true,
                'message' => 'Sistema redefinido com sucesso. Por favor, faça login novamente. Lembre-se de limpar o cache do navegador.'
            ]);
        } catch (\Throwable $e) {
            // Registra o erro completo no log, pra você poder investigar depois
            Log::error('Erro ao redefinir o sistema', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao redefinir o sistema.',
                'error' => $this->limparUtf8($e->getMessage()),
            ], 500);
        }
    }

    /**
     * Garante que um texto está em UTF-8 válido.
     * Evita erros de "Malformed UTF-8" ao devolver mensagens em JSON.
     */
    private function limparUtf8(string $texto): string
    {
        return mb_convert_encoding($texto, 'UTF-8', 'UTF-8');
    }

    public function listBackups()
    {
        // Pega todos os arquivos da pasta "backups"
        $files = Storage::disk('local')->files('backups');

        // Monta uma lista simples com nome e data de cada um
        $backups = collect($files)
            ->map(function ($path) {
                return [
                    'filename' => basename($path),
                    'size' => $this->formatSize(Storage::disk('local')->size($path)),
                    'date' => \Carbon\Carbon::createFromTimestamp(Storage::disk('local')->lastModified($path))->format('d/m/Y H:i'),
                    'timestamp' => Storage::disk('local')->lastModified($path), // usado só para ordenar
                ];
            })
            ->sortByDesc('timestamp')
            ->values();

        return view('admin.system.backups', ['backups' => $backups]);
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' bytes';
    }

    // public function downloadBackup($fileName)
    // {
    //     $caminho = 'backups/' . $fileName;

    //     if (!Storage::disk('local')->exists($caminho)) {
    //         abort(404, 'Backup não encontrado.');
    //     }

    //     return Storage::disk('local')->download($caminho);
    // }

    /**
     * Baixa um arquivo de backup específico.
     */
    public function downloadBackup(string $filename)
    {
        // Remove qualquer tentativa de "escapar" da pasta de backups (ex: ../../.env)
        $filename = basename($filename);

        $path = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Backup não encontrado.');
        }

        return Storage::disk('local')->download($path);
    }

    /**
     * Exclui um arquivo de backup específico.
     */
    public function deleteBackup(string $filename)
    {
        $filename = basename($filename);

        $path = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Backup não encontrado.');
        }

        Storage::disk('local')->delete($path);

        return redirect()
            ->route('admin.system.backups.index') // troque pelo nome da sua rota de listagem
            ->with('success', 'Backup excluído com sucesso.');
    }

    /**
     * Altera o status de um arquivo de edital.
     *
     * Este método alterna o status de um arquivo de edital da pasta 'notices' no banco de dados.
     * Se o arquivo estiver publicado, ele será despublicado e vice-versa.
     *
     * @param \App\Models\Notice $notice Arquivo de edital a ser publicado/despublicado.
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function notice(): RedirectResponse
    // {
    //     $setting = Setting::firstOrCreate(['id' => 1]);

    //     $setting->notice = !$setting->notice; // alterna o valor
    //     $setting->save();

    //     Cache::forget('global_settings'); // MUITO IMPORTANTE!

    //     return alertSuccess('Status alterado com sucesso!', 'admin.notices.index');
    // }

    /**
     * Atualiza o status de acesso ao local de prova e dispara e-mails em fila.
     *
     * Caso o acesso seja liberado (location = true),
     * cria um Job para cada candidato com local de prova definido.
     */
    public function location(Request $request): RedirectResponse
    {
        // Define se acesso foi liberado
        $status = $request->filled('location');

        // Atualiza a configuração
        Setting::updateOrCreate(
            ['id' => 1],
            ['location' => $status]
        );

        Cache::forget('global_settings'); // MUITO IMPORTANTE!

        // Se bloqueou, simplesmente retorna
        if (!$status) {
            return redirect()->back()->with('success', 'Acesso ao local bloqueado com sucesso!');
        }

        // Se liberou, também não enviamos nada aqui.
        // Apenas permitimos que o CRON diurno processe o envio
        // de forma segura e em lotes (300/dia, por exemplo).

        return redirect()->back()->with(
            'success',
            'Acesso ao Local liberado! Os e-mails serão enviados automaticamente pelo sistema.'
        );
    }

    /**
     * Atualiza o status de acesso ao resultado e dispara e-mails em fila.
     *
     * Caso o acesso seja liberado (result = true),
     * cria um Job para cada candidato com resultado definido.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function result(Request $request): RedirectResponse
    {
        $settings = [
            'result' => $request->filled('result')
        ];

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'result' => $settings['result']
            ]
        );

        Cache::forget('global_settings'); // MUITO IMPORTANTE!

        if (Setting::first()->result) {
            return redirect()->back()->with('success', 'Acesso ao resultado liberado com sucesso!');
        }

        return redirect()->back()->with('success', 'Acesso ao resultado bloqueado com sucesso!');
    }
}
