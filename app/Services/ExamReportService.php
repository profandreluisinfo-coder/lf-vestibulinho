<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ExamReportService
{
    /**
     * Retorna alocação de candidatos agrupada por local e número da sala.
     * Usado para relatórios detalhados ou resumidos.
     */
    public function getGroupedAllocations()
    {
        return DB::table('exam_results')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->join('exam_locations', 'exam_results.exam_location_id', '=', 'exam_locations.id')
            ->select(
                'exam_locations.name as location_name',
                'exam_results.room_number',
                'users.name',
                'users.social_name',
                'users.cpf',
                'users.birth',
                'users.pne',
                'inscriptions.id as inscription_id' // 👈 aqui
            )
            ->orderBy('exam_results.exam_location_id')
            ->orderBy('exam_results.room_number')
            ->orderBy('users.name')
            ->get()
            ->groupBy(['location_name', 'room_number']);
    }
}
