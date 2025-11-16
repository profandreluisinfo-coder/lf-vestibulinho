<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{    
    /**
     * Página Index do dashboard do admin
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Candidatos por bairro
        $bairros = DB::table('user_details')
            ->select('burgh', DB::raw('COUNT(*) as total'))
            ->groupBy('burgh')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Candidatos por curso
        $cursos = DB::table('inscriptions')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select('courses.name as curso', DB::raw('COUNT(inscriptions.id) as total'))
            ->groupBy('courses.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Escolas de origem (top 10)
        $escolas = DB::table('user_details')
            ->select('school_name', DB::raw('COUNT(*) as total'))
            ->groupBy('school_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $sexos = DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select('users.gender', DB::raw('COUNT(users.id) as total'))
            ->groupBy('users.gender')
            ->orderBy('users.gender')
            ->get();

        $sexoPorCurso = DB::table('inscriptions')
            ->join('users', 'users.id', '=', 'inscriptions.user_id')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select(
                'courses.name as course',
                DB::raw("SUM(CASE WHEN users.gender = 1 THEN 1 ELSE 0 END) as masculino"),
                DB::raw("SUM(CASE WHEN users.gender = 2 THEN 1 ELSE 0 END) as feminino")
            )
            ->groupBy('courses.name')
            ->orderBy('courses.name')
            ->get();

        return view('admin.dashboard', [
            'bairros' => $bairros,
            'cursos' => $cursos,
            'escolas' => $escolas,
            'sexos' => $sexos,
            'sexoPorCurso' => $sexoPorCurso
        ]);
    }
}