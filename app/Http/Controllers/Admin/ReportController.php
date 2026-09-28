<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Lgbt;
use App\Models\Pne;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function lgbts(): View
    {
        $lgbts = Lgbt::where('status', 'accepted')
            ->with(['user.inscription'])
            ->paginate(20);

        return view('admin.reports.lgbts', [
            'lgbts' => $lgbts
        ]);
    }

    public function lgbtsToPdf()
    {
        $lgbts = Lgbt::where('status', 'accepted')
            ->with(['user.inscription'])
            ->get(); // sem paginate aqui — o PDF deve conter todos os registros

        $pdf = Pdf::loadView('admin.reports.lgbts-pdf', [
            'lgbts' => $lgbts
        ]);

        return $pdf->download('relatorio-lgbts.pdf');
    }

    public function pcds(): View
    {
        $pcds = Pne::where('status', 'accepted')
            ->with(['user.inscription', 'user.lgbt'])
            ->paginate(20);

        return view('admin.reports.pcds', [
            'pcds' => $pcds
        ]);
    }

    public function pcdsToPdf()
    {
        $pcds = Pne::where('status', 'accepted')
            ->with(['user.inscription', 'user.lgbt'])
            ->get(); // sem paginate aqui — o PDF deve conter todos os registros

        $pdf = Pdf::loadView('admin.reports.pcds-pdf', [
            'pcds' => $pcds
        ]);

        return $pdf->download('relatorio-pcds.pdf');
    }

    /**
     * Mostra a lista de resultados na página de classificação do painel administrativo com base na nota de corte
     *
     * @return \Illuminate\View\View
     */
    public function classification()
    {
        // determinar o limite de notas de corte
        $limit = Course::sum('vacancies') * 3;

        // Busca todos os resultados com os dados do candidato, nome social e PCD.
        $results = ExamResult::whereNotNull('score')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->leftJoin('lgbts', 'users.id', '=', 'lgbts.user_id')
            ->leftJoin('pnes', 'users.id', '=', 'pnes.user_id')
            ->select(
                'inscriptions.id',
                'users.name',
                'lgbts.name as social_name',
                'users.birth',
                'pnes.id as pne',
                'exam_results.score',
                'exam_results.ranking'
            )
            ->orderBy('exam_results.ranking')
            ->get();

        // ✅ 1️⃣ Pega o último dentro do limite
        $lastInLimit = $results->where('ranking', '<=', $limit)->last();

        // ✅ 2️⃣ Descobre a nota de corte
        $cutoffScore = $lastInLimit ? $lastInLimit->score : 0;

        // envia pra view
        view()->share([
            'results' => $results,
            'limit' => $limit,
            'cutoffScore' => $cutoffScore,
        ]);

        return view('admin.results.index');
    }
}
