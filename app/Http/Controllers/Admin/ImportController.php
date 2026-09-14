<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SimpleImport;
use App\Models\ExamResult;
use App\Services\ExamRankingService;
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
    public function home(): View
    {
        return view('admin.import.index');
    }

    /**
     * Importa as notas de um arquivo .xlsx
     */
    public function import(Request $request)
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
            // Verificar se a tabela exam_results está vazia antes de importar
            if (ExamResult::count() === 0) {
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

            $inscriptionIds = array_keys($updates);
            $foundInscriptionIds = ExamResult::whereIn('inscription_id', $inscriptionIds)
                ->pluck('inscription_id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $missingInscriptionIds = array_values(array_diff($inscriptionIds, $foundInscriptionIds));

            if ($missingInscriptionIds) {
                return $this->invalidFileResponse(
                    'Não foram encontrados resultados de prova para as inscrições: ' . implode(', ', $missingInscriptionIds) . '.'
                );
            }

            DB::transaction(function () use ($updates) {
                // Uma importação substitui integralmente as notas e classificações anteriores.
                ExamResult::query()->update([
                    'score' => null,
                    'ranking' => null,
                ]);

                foreach ($updates as $inscriptionId => $points) {
                    ExamResult::where('inscription_id', $inscriptionId)
                        ->update(['score' => $points]);
                }

                $this->rankingService->calculate();
            });

            return response()->json([
                'success' => true,
                'message' => count($updates) . ' notas importadas e classificadas com sucesso!'
            ]);
        } catch (\Throwable $e) {
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
