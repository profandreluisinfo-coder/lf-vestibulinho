<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SimpleImport;
use App\Models\Exam;
use App\Models\Process;
use App\Models\Result;
use App\Services\ExamRankingService;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    protected $rankingService;

    public function __construct(ExamRankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    // Exibe o formulário de importação
    public function index(): View
    {
        return view('admin.import.index');
    }

    /**
     * Importa as notas de um arquivo .xlsx e calcula a classificação.
     */
    public function create(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:10240',
        ], [
            'file.required' => 'Carregue um arquivo.',
            'file.file'     => 'O arquivo está corrompido.',
            'file.mimes'    => 'Envie um arquivo no formato .xlsx.',
            'file.max'      => 'O arquivo não pode ultrapassar 10 MB.',
        ]);

        try {
            $process = Process::current();

            if (! $process) {
                return $this->invalidFileResponse('Nenhum processo seletivo cadastrado.');
            }

            // Reimportar mudaria notas e classificação já visíveis aos candidatos
            if ($process->isResultReleased()) {
                return $this->invalidFileResponse(
                    'O resultado já foi liberado aos candidatos. Retire-o do ar antes de importar novas notas.'
                );
            }

            // Candidatos alocados (com prova agendada) neste processo
            $allocated = Exam::whereHas('inscription', fn ($q) => $q->forProcess($process->id));

            if (! (clone $allocated)->exists()) {
                return $this->invalidFileResponse('Nenhuma prova foi agendada.');
            }

            $import = new SimpleImport();
            Excel::import($import, $request->file('file'));

            $rows = $import->rows->toArray();

            if (count($rows) < 2) {
                return $this->invalidFileResponse('Arquivo vazio ou inválido.');
            }

            $expectedHeaders = [
                'inscription_id',
                'user_id',
                'user_cpf',
                'user_name',
                'user_birth',
                'points',
            ];
            $headers = array_map(
                fn ($header) => strtolower(trim((string) $header)),
                $rows[0] ?? []
            );

            if ($headers !== $expectedHeaders) {
                return $this->invalidFileResponse(
                    'Cabeçalhos inválidos. Use: ' . implode(', ', $expectedHeaders) . '.'
                );
            }

            $updates = [];
            $errors = [];

            foreach (array_slice($rows, 1) as $index => $row) {
                $line = $index + 2;

                if (count($row) < count($expectedHeaders)) {
                    $errors[] = "Linha {$line}: todas as seis colunas são obrigatórias.";
                    continue;
                }

                $inscriptionId = $row[0];
                $points = $row[5];

                if (!is_numeric($inscriptionId) || (float) $inscriptionId < 1 || floor((float) $inscriptionId) !== (float) $inscriptionId) {
                    $errors[] = "Linha {$line}: inscription_id inválido.";
                    continue;
                }

                if (!is_numeric($points) || (float) $points < 0 || floor((float) $points) !== (float) $points) {
                    $errors[] = "Linha {$line}: points deve ser um número inteiro igual ou maior que zero.";
                    continue;
                }

                $normalizedInscriptionId = (int) $inscriptionId;

                if (isset($updates[$normalizedInscriptionId])) {
                    $errors[] = "Linha {$line}: inscription_id duplicado no arquivo.";
                    continue;
                }

                $updates[$normalizedInscriptionId] = (int) $points;
            }

            if ($errors) {
                return $this->invalidFileResponse(implode(' ', $errors));
            }

            if (!$updates) {
                return $this->invalidFileResponse('O arquivo não possui notas válidas para importar.');
            }

            // Toda inscrição da planilha precisa ter prova agendada neste processo
            $inscriptionIds = array_keys($updates);
            $foundInscriptionIds = (clone $allocated)
                ->whereIn('inscription_id', $inscriptionIds)
                ->pluck('inscription_id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $missingInscriptionIds = array_values(array_diff($inscriptionIds, $foundInscriptionIds));

            if ($missingInscriptionIds) {
                return $this->invalidFileResponse(
                    'As inscrições a seguir não têm prova agendada neste processo: '
                    . implode(', ', $missingInscriptionIds) . '.'
                );
            }

            $withoutScore = (clone $allocated)->count() - count($updates);
            $now = now();
            $summary = [];

            DB::transaction(function () use ($updates, $process, $now, &$summary) {
                // Uma importação substitui integralmente as notas e classificações DESTE processo.
                Result::whereHas('inscription', fn ($q) => $q->forProcess($process->id))
                    ->update(['score' => null, 'ranking' => null, 'classified' => false]);

                $rows = [];

                foreach ($updates as $inscriptionId => $points) {
                    $rows[] = [
                        'inscription_id' => $inscriptionId,
                        'score'          => $points,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                }

                // exige índice unique em results.inscription_id
                foreach (array_chunk($rows, 1000) as $batch) {
                    Result::upsert($batch, ['inscription_id'], ['score', 'updated_at']);
                }

                $summary = $this->rankingService->calculate($process);
            });

            $message = count($updates) . ' notas importadas. '
                . "{$summary['classified']} candidatos classificados (limite de {$summary['limit']} pelo edital, "
                . 'mais os empatados com o último).';

            if ($withoutScore > 0) {
                $message .= " {$withoutScore} candidato(s) com prova agendada não constam na planilha e ficaram sem nota.";
            }

            if ($summary['tied'] > 0) {
                $message .= " Atenção: {$summary['tied']} candidato(s) empatam em nota e idade com outro e dividem a mesma posição.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (DomainException $e) {
            return $this->invalidFileResponse($e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao importar: ' . $e->getMessage()
            ], 500);
        }
    }

    private function invalidFileResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 422);
    }
}