<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ExamRankingService;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    protected $rankingService;

    public function __construct(ExamRankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    // Método para exibir o formulário para importação de notas a partir de um arquivo .xlsx
    public function index()
    {
        return view('import.private.index');
    }

/**
 * Importa as notas de um arquivo .xlsx
 *
 * Valida se o arquivo tem ao menos 2 colunas e se a primeira coluna tem um valor numérico e a quinta coluna tem um valor numérico.
 * Se a validação for bem sucedida, atualiza as notas na tabela de resultados.
 * Se a validação falhar, retorna um erro.
 * Se a importação for bem sucedida, recalcula a classificação geral e redireciona para a página de classificação.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        $file = $request->file('file');
        $spreadsheet = Excel::toArray([], $file);
        $rows = $spreadsheet[0];

        // Validação básica
        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'Arquivo vazio ou sem colunas suficientes.']);
        }

        unset($rows[0]); // Remove cabeçalho

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $inscriptionId = $row[0]; // coluna: inscription_id
                $points = (int) $row[5];  // coluna: points

                if (!$inscriptionId || !is_numeric($points)) {
                    continue; // pula linhas inválidas
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