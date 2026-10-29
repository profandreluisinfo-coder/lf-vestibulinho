<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Services\ChartService;

class ChartController extends Controller
{
    public function index(ChartService $cs)
    {
        $process = Process::current();
        
        return view('admin.charts.index', [
            'bairros'      => $cs->burghs($process),
            'cursos'       => $cs->courses($process),
            'escolas'      => $cs->schools($process),
            'sexos'        => $cs->genders($process),
            'sexoPorCurso' => $cs->genderPerCourse($process),
        ]);
    }
}