<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Setting;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * Mostra a lista de resultados na página de classificação do painel administrativo com base na nota de corte
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // verifica se o  acesso ao resultado foi liberado
        $status = Setting::first() ?? new Setting();

        // determinar o limite de notas de corte
        $limit = Course::sum('vacancies') * 3;

        // Busca todos os resultados com user e user_details carregados
        $results = ExamResult::whereNotNull('score')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                'inscriptions.id',
                'users.name',
                'users.social_name',
                'users.birth',
                'users.pne',
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
            'status' => $status,
            'limit' => $limit,
            'cutoffScore' => $cutoffScore,
        ]);

        return view('results.private.index');
    }

    public function setAccessToResult(Request $request)
    {
        $settings = [
            'result' => $request->filled('result')
        ];

        Setting::updateOrCreate(['id' => 1], [
            'result' => $settings['result']
        ]);

        if (Setting::first()->result) {
            return redirect()->back()->with('success', 'Acesso ao resultado liberado com sucesso!');
        }

        return redirect()->back()->with('success', 'Acesso ao resultado bloqueado com sucesso!');
    }
}