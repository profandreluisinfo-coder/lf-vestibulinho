<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BurghsExport;
use App\Exports\CoursesExport;
use App\Exports\GenderPerCourseExport;
use App\Exports\GendersExport;
use App\Exports\SchoolsExport;
use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\ExamResult;
use App\Models\Process;
use App\Models\Setting;
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
        $settings_location = Setting::first()?->location ?? new Setting();
        $settings_result = Setting::first()?->result ?? new Setting();
        // $calls_exists = Call::first() ?? new Call();

        // Candidatos por bairro
        $burghs = DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select('users.burgh', DB::raw('COUNT(DISTINCT users.id) as total'))
            ->groupBy('users.burgh')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // // Candidatos por curso
        $courses = DB::table('inscriptions')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select('courses.name as curso', DB::raw('COUNT(inscriptions.id) as total'))
            ->groupBy('courses.name')
            ->orderByDesc('total')
            ->get();

        // // Escolas de origem (top 10)
        $schools = DB::table('academics')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'academics.user_id')
            ->select('academics.school', DB::raw('COUNT(DISTINCT academics.user_id) as total'))
            ->groupBy('academics.school')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $genders = DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select('gender', DB::raw('COUNT(users.id) as total'))
            ->groupBy('gender')
            ->orderBy('gender')
            ->get();

        $genderPerCourse = DB::table('inscriptions')
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
            $settings_location,
            $settings_result,
            $process_status,
            $local_status,
            $ranking_active
        ])
            ->filter()
            ->count();

        $steps_total = 5;
        $steps_pct = round(($steps_done / $steps_total) * 100);

        return view('admin.home.index', [
            'process_status' => $process_status,
            'bairros' => $burghs,
            'cursos' => $courses,
            'escolas' => $schools,
            'sexos' => $genders,
            'sexoPorCurso' => $genderPerCourse,
            'local_status' => $local_status,
            'ranking_active' => $ranking_active,
            'steps_done' => $steps_done,
            'steps_total' => $steps_total,
            'steps_pct' => $steps_pct
        ]);
    }

    public function exportCoursesPdf()
    {
        $courses = DB::table('inscriptions')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select('courses.name as course', DB::raw('COUNT(inscriptions.id) as total'))
            ->groupBy('courses.name')
            ->orderByDesc('total')
            ->get();

        if ($courses->isEmpty()) {
            return alertError('Não há dados disponíveis para exportação.');
        }

        $pdf = Pdf::loadView('admin.exports.courses_pdf', compact('courses'));

        return $pdf->download('candidatos_por_curso.pdf');
    }

    public function exportCoursesExcel()
    {
        $export = new CoursesExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'candidatos_por_curso.xlsx');
    }

    public function exportGendersPdf()
    {
        $genders = DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select('gender', DB::raw('COUNT(users.id) as total'))
            ->groupBy('gender')
            ->orderBy('gender')
            ->get()
            ->map(function ($row) {
                $row->gender = User::GENDERS[$row->gender] ?? $row->gender;
                return $row;
            });

        if ($genders->isEmpty()) {
            return alertError('Não há dados disponíveis para exportação.');
        }

        $pdf = Pdf::loadView('admin.exports.genders_pdf', compact('genders'));

        return $pdf->download('candidatos_por_sexo.pdf');
    }

    public function exportGendersExcel()
    {
        $export = new GendersExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'candidatos_por_sexo.xlsx');
    }

    public function exportGenderPerCoursePdf()
    {
        $genderPerCourse = DB::table('inscriptions')
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

        if ($genderPerCourse->isEmpty()) {
            return alertError('Não há dados disponíveis para exportação.');
        }

        $pdf = Pdf::loadView('admin.exports.gender_per_course_pdf', compact('genderPerCourse'));

        return $pdf->download('candidatos_por_curso_e_sexo.pdf');
    }

    public function exportGenderPerCourseExcel()
    {
        $export = new GenderPerCourseExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'candidatos_por_curso_e_sexo.xlsx');
    }

    public function exportBurghsPdf()
    {
        $burghs = DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select('users.burgh', DB::raw('COUNT(DISTINCT users.id) as total'))
            ->groupBy('users.burgh')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        if ($burghs->isEmpty()) {
            return alertError('Não há dados disponíveis para exportação.');
        }

        $pdf = Pdf::loadView('admin.exports.burghs_pdf', compact('burghs'));

        return $pdf->download('bairros_com_mais_candidatos.pdf');
    }

    public function exportBurghsExcel()
    {
        $export = new BurghsExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'bairros_com_mais_candidatos.xlsx');
    }

    public function exportSchoolsPdf()
    {
        $schools = DB::table('academics')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'academics.user_id')
            ->select('academics.school', DB::raw('COUNT(DISTINCT academics.user_id) as total'))
            ->groupBy('academics.school')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        if ($schools->isEmpty()) {
            return alertError('Não há dados disponíveis para exportação.');
        }

        $pdf = Pdf::loadView('admin.exports.schools_pdf', compact('schools'));

        return $pdf->download('escolas_de_origem.pdf');
    }

    public function exportSchoolsExcel()
    {
        $export = new SchoolsExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'escolas_de_origem.xlsx');
    }
}