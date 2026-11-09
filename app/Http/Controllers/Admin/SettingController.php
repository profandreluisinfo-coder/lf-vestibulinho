<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\CallList;
use App\Models\Exam;
use App\Models\Inscription;
use App\Models\Process;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
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
     * Gera um backup manual, sob demanda (botão na tela de backups).
     */
    public function createBackup()
    {
        try {
            $fileName = $this->generateBackup();

            Log::info('Backup manual gerado', ['filename' => $fileName]);

            return redirect()
                ->route('admin.system.backups.index')
                ->with('success', "Backup \"$fileName\" gerado com sucesso.");
        } catch (\Throwable $e) {
            Log::error('Erro ao gerar backup manual', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.system.backups.index')
                ->with('error', $this->limparUtf8($e->getMessage()));
        }
    }

    /**
     * Gera um backup completo do banco de dados (.sql) e salva na pasta de backups.
     * Retorna o nome do arquivo gerado.
     *
     * @throws \Exception se o mysqldump falhar
     */
    private function generateBackup(): string
    {
        // Ano do Processo Seletivo atual
        $processYear = Process::current()?->year ?? now()->year;
        // Dados de conexão (pega automaticamente do seu .env)
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $db = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Nome do arquivo com data e hora
        $fileName = 'Vestibulinho_' . $processYear . '_' . now()->format('Y-m-d_H-i-s') . '.sql';

        // Caminho real do arquivo, sempre consistente com o disco 'local'
        $completePath = Storage::disk('local')->path('backups/' . $fileName);

        // Garante que a pasta "backups" existe
        $backupDir = Storage::disk('local')->path('backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true, true);
        }

        // Monta o comando do mysqldump
        $comand = [
            env('MYSQLDUMP_PATH', 'mysqldump'),
            '-h',
            $host,
            '-P',
            $port,
            '-u',
            $user,
            "--password={$password}",
            $db,
        ];

        // Executa o comando e salva SOMENTE a saída normal (o SQL de verdade)
        $process = new SymfonyProcess($comand);
        $process?->setTimeout(300);
        $process?->run(function ($type, $output) use ($completePath) {
            if ($type === SymfonyProcess::OUT) {
                file_put_contents($completePath, $output, FILE_APPEND);
            }
        });

        if (!$process?->isSuccessful()) {
            $erro = $this->limparUtf8($process?->getErrorOutput());
            throw new \Exception('Falha ao gerar o backup: ' . $erro);
        }

        return $fileName;
    }

    /**
     * Redefine o sistema
     *
     * Garante que só admin possa resetar o sistema.
     * Gera um backup completo do banco (.sql) antes de apagar qualquer coisa.
     * Remove dados do processo e usuários não administradores dentro de uma transação.
     * Inscrições, resultados, provas e dados relacionados a usuários são removidos pelas FKs.
     * Preserva publicações, FAQs, arquivos de provas anteriores e modelos reutilizáveis.
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
            $this->generateBackup();

            // MySQL commits DDL implicitly, so keep only the data changes in this transaction.
            // Collect storage paths before cascading deletes remove their database records.
            [$filesToDelete, $processIds, $resetTables] = DB::transaction(function (): array {
                $deletedUserIds = User::where('role', '!=', 'admin')->pluck('id');
                $processIds = Process::pluck('id');
                $filesToDelete = collect();

                foreach ([
                    ['lgbts', 'authorization'],
                    ['pnes', 'report'],
                    ['appeals', 'path'],
                ] as [$table, $column]) {
                    $filesToDelete = $filesToDelete->merge(
                        DB::table($table)->whereNotNull($column)->pluck($column)
                    );
                }

                // Editais belong to the process being reset; reusable templates do not.
                $filesToDelete = $filesToDelete
                    ->merge(DB::table('processes')->whereNotNull('edital')->pluck('edital'));

                // Delete in FK dependency order. These explicit deletes also clear rows
                // belonging to admin accounts, which must be preserved in the users table.
                Call::query()->delete();
                Exam::query()->delete();
                DB::table('results')->delete();
                Inscription::query()->delete();
                CallList::query()->delete();
                DB::table('course_process')->delete();
                Process::query()->delete();

                foreach ([
                    'appeals',
                    'lgbts',
                    'pnes',
                    'mothers',
                    'fathers',
                    'guardians',
                    'parent_emails',
                    'documents',
                    'addresses',
                    'certificates',
                    'academics',
                ] as $table) {
                    DB::table($table)->delete();
                }

                // Preserve reusable content whose FK would otherwise cascade when
                // a non-admin author is removed. Attribute it to the admin running reset.
                if ($deletedUserIds->isNotEmpty()) {
                    foreach (['faqs', 'posts', 'archives'] as $table) {
                        DB::table($table)
                            ->whereIn('user_id', $deletedUserIds)
                            ->update(['user_id' => Auth::id()]);
                    }
                }

                // Reset publication visibility; these flags are stored in settings,
                // independently of the inscriptions and results being deleted above.
                DB::table('settings')->update([
                    'publications' => json_encode([]),
                    'updated_at' => now(),
                ]);

                User::where('role', '!=', 'admin')->delete(); // cascades user-owned records

                // The sessions table has no FK; clear all database sessions for the reset.
                if (config('session.driver') === 'database') {
                    $sessionsTable = config('session.table', 'sessions');
                    if (DB::getSchemaBuilder()->hasTable($sessionsTable)) {
                        DB::table($sessionsTable)->delete();
                    }
                }

                $resetTables = [
                    'results', 'inscriptions', 'course_process', 'pnes', 'parent_emails',
                    'mothers', 'fathers', 'lgbts', 'guardians', 'documents', 'certificates',
                    'appeals', 'addresses', 'academics',
                    'exams', 'calls', 'call_lists', 'processes',
                ];

                return [$filesToDelete->filter()->unique()->values(), $processIds, $resetTables];
            });

            // Storage is outside the database transaction; failures are logged without undoing the reset.
            $fileCleanupComplete = true;
            try {
                if ($filesToDelete->isNotEmpty()) {
                    $fileCleanupComplete = Storage::disk('public')->delete($filesToDelete->all());
                }
                foreach (['authorizations', 'reports', 'appeals'] as $directory) {
                    if (! Storage::disk('public')->deleteDirectory($directory)) {
                        $fileCleanupComplete = false;
                    }
                }
            } catch (\Throwable $fileError) {
                $fileCleanupComplete = false;
                Log::warning('Reset concluído, mas houve falha ao remover arquivos relacionados', [
                    'message' => $fileError->getMessage(),
                ]);
            }
            if (! $fileCleanupComplete) {
                Log::warning('Reset concluído, mas um ou mais arquivos relacionados não foram removidos.');
            }

            // ALTER TABLE implicitly commits in MySQL, so run sequence adjustments after data commits.
            $sequenceResetComplete = true;
            try {
                $maxUserId = User::max('id');
                $nextUserId = $maxUserId ? $maxUserId + 1 : 1;
                DB::statement('ALTER TABLE users AUTO_INCREMENT = ' . (int) $nextUserId);

                foreach ($resetTables as $table) {
                    DB::statement('ALTER TABLE `' . $table . '` AUTO_INCREMENT = 1');
                }
            } catch (\Throwable $sequenceError) {
                $sequenceResetComplete = false;
                Log::warning('Reset concluído, mas não foi possível redefinir os contadores das tabelas', [
                    'message' => $sequenceError->getMessage(),
                ]);
            }

            Cache::forget('global_calendar');
            Cache::forget('global_process');
            Cache::forget('global_inscription_open');
            Cache::forget('global_total_inscriptions');
            Cache::forget('global_users_without_inscription');
            Cache::forget('global_results');
            Cache::forget('global_results_eliminated');
            Cache::forget('global_settings');
            Cache::forget('calls_exists');
            Cache::forget('global_total_comunicados');
            Cache::forget('global_total_publicacoes');
            Cache::forget('archives_active_list');
            Cache::forget('archives_has_active');
            foreach ($processIds as $processId) {
                Cache::forget('calls_finalized_count_' . $processId);
            }

            // Apagar os dados de autenticação
            session()->flush();

            return response()->json([
                'success' => true,
                'message' => 'Sistema redefinido com sucesso.'
                    . ((! $fileCleanupComplete || ! $sequenceResetComplete)
                        ? ' Confira o log: uma tarefa secundária de limpeza não foi concluída.'
                        : '')
                    . ' Por favor, faça login novamente. Lembre-se de limpar o cache do navegador.'
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
}
