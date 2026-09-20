<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CoursesExport;
use App\Exports\GendersExport;
use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\ExamResult;
use App\Models\Process;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {
        $process_status = Process::current()?->status === "open" ? true : false;
        $local_status = ExamResult::hasRecords();
        $ranking_active = ExamResult::hasScores();
        // $inscriptions_count = Inscription::count();
        // $settings = Setting::first() ?? new Setting();
        // $calls_exists = Call::first() ?? new Call();

        // Candidatos por bairro
        $bairros = DB::table('users')
            ->select('burgh', DB::raw('COUNT(*) as total'))
            ->groupBy('burgh')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // // Candidatos por curso
        $cursos = DB::table('inscriptions')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select('courses.name as curso', DB::raw('COUNT(inscriptions.id) as total'))
            ->groupBy('courses.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // // Escolas de origem (top 10)
        $escolas = DB::table('academics')
            ->select('school', DB::raw('COUNT(*) as total'))
            ->groupBy('school')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $sexos = DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select('gender', DB::raw('COUNT(users.id) as total'))
            ->groupBy('gender')
            ->orderBy('gender')
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

        $steps_done = collect([
            $process_status,
            $local_status,
            $ranking_active
        ])
            ->filter()
            ->count();

        $steps_total = 5;
        $steps_pct = round(($steps_done / $steps_total) * 100);

        return view('admin.home.index', [
            'bairros' => $bairros,
            'cursos' => $cursos,
            'escolas' => $escolas,
            'sexos' => $sexos,
            'sexoPorCurso' => $sexoPorCurso,
            'local_status' => $local_status,
            'ranking_active' => $ranking_active,
            'steps_done' => $steps_done,
            'steps_total' => $steps_total,
            'steps_pct' => $steps_pct
        ]);
    }

    public function exportCoursesPdf()
    {
        $cursos = DB::table('inscriptions')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select('courses.name as curso', DB::raw('COUNT(inscriptions.id) as total'))
            ->groupBy('courses.name')
            ->orderByDesc('total')
            ->get();

        if ($cursos->isEmpty()) {
            return redirect()->back()->with('error', 'Não há dados disponíveis para exportação.');
        }

        $pdf = Pdf::loadView('admin.exports.courses_pdf', compact('cursos'));

        return $pdf->download('candidatos_por_curso.pdf');
    }

    public function exportCoursesExcel()
    {
        $export = new CoursesExport;

        if ($export->collection()->isEmpty()) {
            return back()->with('error', 'Não há dados para exportar.');
        }

        return Excel::download($export, 'candidatos_por_curso.xlsx');
    }

    public function exportGendersPdf()
    {
        $sexos = DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select('gender', DB::raw('COUNT(users.id) as total'))
            ->groupBy('gender')
            ->orderBy('gender')
            ->get()
            ->map(function ($row) {
                $row->gender = User::GENDERS[$row->gender] ?? $row->gender;
                return $row;
            });

        if ($sexos->isEmpty()) {
            return redirect()->back()->with('error', 'Não há dados disponíveis para exportação.');
        }

        $pdf = Pdf::loadView('admin.exports.genders_pdf', compact('sexos'));

        return $pdf->download('candidatos_por_sexo.pdf');
    }

    public function exportGendersExcel()
    {
        $export = new GendersExport;

        if ($export->collection()->isEmpty()) {
            return back()->with('error', 'Não há dados para exportar.');
        }
        
        return Excel::download(new GendersExport, 'candidatos_por_sexo.xlsx');
    }
}
