<?php

namespace App\Services;

use App\Models\ExamResult;

class ExamRankingService
{
    public function calculate(): void
    {
        $results = ExamResult::whereNotNull('score')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->select('exam_results.id', 'exam_results.score', 'users.birth')
            ->orderByDesc('exam_results.score')
            ->orderByDesc('users.birth') // <- Aqui está o ajuste
            ->get();

        $rank = 1;
        foreach ($results as $result) {
            ExamResult::where('id', $result->id)
                ->update(['ranking' => $rank++]);
        }
    }
}
