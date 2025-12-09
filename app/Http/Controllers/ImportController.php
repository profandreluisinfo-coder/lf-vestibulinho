<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ExamRankingService;
use Illuminate\Support\Facades\DB;
use App\Imports\SimpleImport;

class ImportController extends Controller
{
    protected $rankingService;

    public function __construct(ExamRankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    // Exibe o formulário de importação
    public function index()
    {
        return view('import.private.index');
    }

    /**
     * Importa as notas de um arquivo .xlsx
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ], [
            'file.required' => 'Carregue um arquivo.',
            'file.file'     => 'O arquivo está corrompido.'
        ]);

        try {
            $import = new SimpleImport();
            Excel::import($import, $request->file('file'));

            $rows = $import->rows->toArray();

            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo vazio ou inválido.'
                ], 422);
            }

            unset($rows[0]);

            DB::beginTransaction();

            foreach ($rows as $row) {

                if (!isset($row[0], $row[5])) {
                    continue;
                }

                $inscriptionId = $row[0];
                $points        = (int) $row[5];

                if (!$inscriptionId || !is_numeric($points)) {
                    continue;
                }

                ExamResult::where('inscription_id', $inscriptionId)
                    ->update(['score' => $points]);
            }

            $this->rankingService->calculate();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Importação concluída com sucesso!'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao importar: ' . $e->getMessage()
            ], 500);
        }
    }
}
