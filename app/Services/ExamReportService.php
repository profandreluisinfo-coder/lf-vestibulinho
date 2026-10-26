<?php

namespace App\Services;

use App\Models\Process;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExamReportService
{
    /**
     * Retorna a alocação de candidatos agrupada por local e número da sala.
     * Usado para relatórios detalhados ou resumidos.
     *
     * @param  int|null  $processId  Processo seletivo do relatório. Se omitido, usa o atual.
     */
    public function getGroupedAllocations(?int $processId = null): Collection
    {
        $processId ??= Process::current()?->id;

        if (! $processId) {
            return collect();
        }

        return DB::table('exams')
            ->join('inscriptions', 'exams.inscription_id', '=', 'inscriptions.id') // número de inscrição
            ->join('processes', 'inscriptions.process_id', '=', 'processes.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id') // dados do candidato
            ->leftJoin('lgbts', 'users.id', '=', 'lgbts.user_id')
            ->leftJoin('pnes', 'users.id', '=', 'pnes.user_id')
            ->join('locations', 'exams.location_id', '=', 'locations.id')
            ->where('inscriptions.process_id', $processId)
            ->select(
                'locations.name as location_name',
                'exams.room_number',
                'processes.exam_time',
                'processes.exam_date',
                'users.name as name',
                DB::raw("CASE WHEN lgbts.status = 'accepted' THEN lgbts.name END as social_name"),
                'users.cpf',
                'users.birth',
                DB::raw("CASE WHEN pnes.status = 'accepted' THEN 1 ELSE 0 END as pne"),
                'inscriptions.id as inscription_id'
            )
            ->orderBy('exams.location_id')
            ->orderBy('exams.room_number')
            ->orderByRaw("CASE WHEN lgbts.status = 'accepted' THEN lgbts.name ELSE users.name END")
            ->get()
            ->groupBy(['location_name', 'room_number']);
    }
}