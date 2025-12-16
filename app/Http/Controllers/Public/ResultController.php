<?php

namespace App\Http\Controllers\Public;

use App\Models\Course;
use App\Models\Notice;
use App\Models\Setting;
use App\Models\ExamResult;
use App\Http\Controllers\Controller;

class ResultController extends Controller
{
/**
 * Mostra a lista de resultados na página de resultados do site com base na nota de corte
 *
 * @param int $limit Número de vagas que uma nota de corte tem
 * @param ExamResult[] $results Resultados da nota de corte com base na nota de corte
 * @param int $cutoffScore Pontuação de corte para decidir se uma nota é de corte ou não
 *
 * @return \Illuminate\View\View
 */
    public function index()
    {
        // verifica se o  acesso ao resultado foi liberado
        // $settings = Setting::first() ?? new Setting();
        // $status = $settings->result;

        // se o acesso ao resultado nao foi liberado, redireciona para a home
        if (!Setting::first()->result) {
            return redirect()->route('home');
        }

        // Busca pelo arquivo do edital em 'notices'
        $notice = Notice::all()->first();

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
            'notice' => $notice,
            'results' => $results,
            'limit' => $limit,
            'cutoffScore' => $cutoffScore,
        ]);

        return view('results.public.index');
    }
}