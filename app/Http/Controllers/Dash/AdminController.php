<?php

namespace App\Http\Controllers\Dash;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Página Index do dashboard do admin
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        // Candidatos por bairro
        $bairros = DB::table('users')
            ->select('burgh', DB::raw('COUNT(*) as total'))
            ->groupBy('burgh')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $cursos = Course::getInscriptionsCount();
        
        // Escolas de origem (top 10)
        $escolas = DB::table('users')
            ->select('school_name', DB::raw('COUNT(*) as total'))
            ->groupBy('school_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $sexos = Course::getCandidatesByGender();

        $sexoPorCurso = Course::getGendersByCourses();

        return view('dash.admin.home', [
            'bairros'       => $bairros,
            'cursos'        => $cursos,
            'escolas'       => $escolas,
            'sexos'         => $sexos,
            'sexoPorCurso'  => $sexoPorCurso
        ]);
    }
}