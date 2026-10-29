<?php

namespace App\Services;

use App\Models\Process;
use App\Models\Result;
use DomainException;

class ExamRankingService
{
    /** Edital, item 9.1: limite de classificados = 3 vezes o total de vagas. */
    // public const LIST_MULTIPLIER = 3;
    public const LIST_MULTIPLIER = 3;

    /**
     * Calcula a classificação do processo informado.
     *
     * - ordem decrescente de nota (9.1);
     * - desempate pelo candidato de menor idade, isto é, nascimento mais recente (9.2);
     * - classificados = até 3x o total de vagas, mais todos que tiverem a mesma nota
     *   do último classificado dentro desse limite (9.1);
     * - quem empata em nota E idade recebe a mesma posição (1, 2, 2, 4) e fica
     *   sinalizado no retorno, porque o edital não define o desempate seguinte.
     *
     * @return array{scored:int, classified:int, limit:int, tied:int}
     *
     * @throws DomainException
     */
    public function calculate(Process $process): array
    {
        $vacancies = (int) $process->courses()->sum('course_process.vacancies');

        if ($vacancies < 1) {
            throw new DomainException(
                'O processo não tem vagas cadastradas, então o limite de classificação não pode ser calculado.'
            );
        }

        $limit = $vacancies * self::LIST_MULTIPLIER;

        // Zera a classificação apenas dos resultados deste processo
        Result::whereHas('inscription', fn ($q) => $q->forProcess($process->id))
            ->update(['ranking' => null, 'classified' => false]);

        $scored = Result::query()
            ->join('inscriptions', 'results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->where('inscriptions.process_id', $process->id)
            ->whereNotNull('results.score')
            ->orderByDesc('results.score')
            ->orderByDesc('users.birth')
            ->get(['results.inscription_id', 'results.score', 'users.birth']);

        // Nota do último classificado dentro do limite (só existe se houver mais candidatos que o limite)
        $cutoffScore = $scored->count() > $limit
            ? $scored[$limit - 1]->score
            : null;

        $now = now();
        $rows = [];
        $position = 0;
        $rank = 0;
        $previousKey = null;
        $tied = 0;
        $classified = 0;

        foreach ($scored as $result) {
            $position++;
            $key = $result->score . '|' . $result->birth;

            if ($key === $previousKey) {
                $tied++;
            } else {
                $rank = $position;
                $previousKey = $key;
            }

            $isClassified = $cutoffScore === null || $result->score >= $cutoffScore;
            $classified += $isClassified ? 1 : 0;

            $rows[] = [
                'inscription_id' => $result->inscription_id,
                'ranking'        => $rank,
                'classified'     => $isClassified,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        // exige índice unique em results.inscription_id
        foreach (array_chunk($rows, 1000) as $batch) {
            Result::upsert($batch, ['inscription_id'], ['ranking', 'classified', 'updated_at']);
        }

        return [
            'scored'     => $scored->count(),
            'classified' => $classified,
            'limit'      => $limit,
            'tied'       => $tied,
        ];
    }
}