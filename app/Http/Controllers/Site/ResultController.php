<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Setting;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ResultController extends Controller
{
    /**
     * Mostra a lista de resultados na página de resultados do site com base na nota de corte
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @return \Illuminate\View\View
     */
    public function index(): View|RedirectResponse
    {

        if (!Setting::isResultEnabled()) {
            return redirect()->back();
        }

        // determinar o limite de notas de corte
        $limit = Course::sum('vacancies') * 3;

        // Busca todos os resultados com user e user_details carregados
        $results = ExamResult::whereNotNull('score')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            // ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                'inscriptions.id',
                'users.name',
                'users.name',
                'users.birth',
                'exam_results.score',
                'exam_results.ranking'
            )
            ->orderBy('exam_results.ranking')
            ->get();

        // ✅ 1️⃣ Pega o último dentro do limite
        $lastInLimit = $results->where('ranking', '<=', $limit)->last();

        // ✅ 2️⃣ Descobre a nota de corte
        $cutoffScore = $lastInLimit ? $lastInLimit->score : 0;

        return view('site.results.index', [
            'results' => $results,
            'limit' => $limit,
            'cutoffScore' => $cutoffScore,
        ]);
    }
}
