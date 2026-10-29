<?php

namespace App\Services;

use App\Models\Process;
use Illuminate\Support\Facades\DB;

class ChartService
{
    /**
     * Inscrições e vagas por curso, no processo atual.
     */
    public function courseChart(?Process $process): array
    {
        $empty = ['labels' => [], 'inscritos' => [], 'vagas' => []];

        if (!$process) {
            return $empty;
        }

        $rows = DB::table('course_process')
            ->join('courses', 'courses.id', '=', 'course_process.course_id')
            ->leftJoin('inscriptions', function ($join) use ($process) {
                $join->on('inscriptions.course_id', '=', 'course_process.course_id')
                    ->where('inscriptions.process_id', '=', $process->id);
            })
            ->where('course_process.process_id', $process->id)
            ->groupBy('courses.id', 'courses.name', 'course_process.vacancies')
            ->select(
                'courses.name as curso',
                'course_process.vacancies as vagas',
                DB::raw('COUNT(inscriptions.id) as inscritos')
            )
            ->orderByDesc('inscritos')
            ->get();

        return [
            'labels'    => $rows->pluck('curso')->all(),
            'inscritos' => $rows->pluck('inscritos')->map(fn($v) => (int) $v)->all(),
            'vagas'     => $rows->pluck('vagas')->map(fn($v) => (int) $v)->all(),
        ];
    }

    /**
     * Inscrições por dia, para o gráfico.
     */
    public function dailyChart(?Process $process): array
    {
        $days = $process?->dailyInscriptions() ?? [];

        return [
            'labels' => array_map(fn($day) => $day['date']->format('d/m'), $days),
            'values' => array_column($days, 'total'),
            'total'  => array_sum(array_column($days, 'total')),
        ];
    }

    // Candidatos por bairro
    public function burghs(Process $process)
    {
        return DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->where('inscriptions.process_id', $process->id)
            ->select(
                'users.burgh',
                DB::raw('COUNT(DISTINCT users.id) as total')
            )
            ->groupBy('users.burgh')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    // Inscrições por curso
    public function courses(Process $process)
    {
        return DB::table('inscriptions')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->where('inscriptions.process_id', $process->id)
            ->select(
                'courses.name as curso',
                DB::raw('COUNT(inscriptions.id) as total')
            )
            ->groupBy('courses.name')
            ->orderByDesc('total')
            ->get();
    }

    // Escolas de origem
    public function schools(Process $process)
    {
        return DB::table('academics')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'academics.user_id')
            ->where('inscriptions.process_id', $process->id)
            ->select(
                'academics.school',
                DB::raw('COUNT(DISTINCT academics.user_id) as total')
            )
            ->groupBy('academics.school')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    // Candidatos por sexo
    public function genders(Process $process)
    {
        return DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->where('inscriptions.process_id', $process->id)
            ->select(
                'users.gender',
                DB::raw('COUNT(DISTINCT users.id) as total')
            )
            ->groupBy('users.gender')
            ->orderBy('users.gender')
            ->get();
    }

    // Candidatos por sexo e curso
    public function genderPerCourse(Process $process)
    {
        return DB::table('inscriptions')
            ->join('users', 'users.id', '=', 'inscriptions.user_id')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->where('inscriptions.process_id', $process->id)
            ->select(
                'courses.name as course',
                DB::raw('COUNT(DISTINCT CASE WHEN users.gender = 1 THEN users.id END) as masculino'),
                DB::raw('COUNT(DISTINCT CASE WHEN users.gender = 2 THEN users.id END) as feminino')
            )
            ->groupBy('courses.name')
            ->orderBy('courses.name')
            ->get();
    }
}
