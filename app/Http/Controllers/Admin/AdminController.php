<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\ExamResult;
use App\Models\Setting;

class AdminController extends Controller
{
    public function index()
    {
        //$calendar_active = Calendar::latest('id')->first() ?? new Calendar();
        $local_status = ExamResult::hasRecords();
        $ranking_active = ExamResult::hasScores();
        // $inscriptions_count = Inscription::count();
        // $settings = Setting::first() ?? new Setting();
        // $calls_exists = Call::first() ?? new Call();

        // Candidatos por bairro
        // $bairros = DB::table('user_details')
        //     ->select('burgh', DB::raw('COUNT(*) as total'))
        //     ->groupBy('burgh')
        //     ->orderByDesc('total')
        //     ->limit(10)
        //     ->get();

        // // Candidatos por curso
        // $cursos = DB::table('inscriptions')
        //     ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
        //     ->select('courses.name as curso', DB::raw('COUNT(inscriptions.id) as total'))
        //     ->groupBy('courses.name')
        //     ->orderByDesc('total')
        //     ->limit(10)
        //     ->get();

        // // Escolas de origem (top 10)
        // $escolas = DB::table('user_details')
        //     ->select('school_name', DB::raw('COUNT(*) as total'))
        //     ->groupBy('school_name')
        //     ->orderByDesc('total')
        //     ->limit(10)
        //     ->get();

        // $sexos = DB::table('users')
        //     ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
        //     ->select('users.gender', DB::raw('COUNT(users.id) as total'))
        //     ->groupBy('users.gender')
        //     ->orderBy('users.gender')
        //     ->get();

        // $sexoPorCurso = DB::table('inscriptions')
        //     ->join('users', 'users.id', '=', 'inscriptions.user_id')
        //     ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
        //     ->select(
        //         'courses.name as course',
        //         DB::raw("SUM(CASE WHEN users.gender = 1 THEN 1 ELSE 0 END) as masculino"),
        //         DB::raw("SUM(CASE WHEN users.gender = 2 THEN 1 ELSE 0 END) as feminino")
        //     )
        //     ->groupBy('courses.name')
        //     ->orderBy('courses.name')
        //     ->get();

        $steps_done = collect([
            $local_status,
            $ranking_active
        ])
            ->filter()
            ->count();

        $steps_total = 5;
        $steps_pct = round(($steps_done / $steps_total) * 100);

        // return view('admin.home.index', compact('calendar_active', 'local_status', 'ranking_active', 'inscriptions_count', 'settings', 'calls_exists', 'bairros', 'cursos', 'escolas', 'sexos', 'sexoPorCurso', 'steps_done', 'steps_total', 'steps_pct'));

        return view('admin.home.index', compact(
            // 'inscriptions_count', 
            // 'settings', 
            'local_status', 
            'ranking_active', 
            // 'calls_exists', 
            'steps_done', 
            'steps_total', 
            'steps_pct')
            );
    }
}