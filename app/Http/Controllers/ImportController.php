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

        $file = $request->file('file');

        // Import sem depender de TemporaryFile (funciona 100% no Windows)
        $import = new SimpleImport();
        Excel::import($import, $file);

        // Converte coleção para array comum
        $rows = $import->rows->toArray();

        // Validação básica
        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'Arquivo vazio ou sem colunas suficientes.']);
        }

        // Remove cabeçalho
        unset($rows[0]);

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {

                // Protege contra linhas vazias
                if (!isset($row[0]) || !isset($row[5])) {
                    continue;
                }

                $inscriptionId = $row[0]; // coluna inscription_id
                $points = (int) $row[5];  // coluna points

                if (!$inscriptionId || !is_numeric($points)) {
                    continue;
                }

                ExamResult::where('inscription_id', $inscriptionId)
                    ->update(['score' => $points]);
            }

            $this->rankingService->calculate();
            DB::commit();

            return redirect()->route('ranking')->with('success', 'Operação realizada com sucesso!');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Erro ao importar: ' . $e->getMessage()]);
        }
    }
}