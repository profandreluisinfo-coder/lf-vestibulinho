<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Inscription;
use App\Models\Process;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BurghsExport;
use App\Exports\CoursesExport;
use App\Exports\GenderPerCourseExport;
use App\Exports\GendersExport;
use App\Exports\SchoolsExport;
use App\Models\User;


class ReportController extends Controller
{
    // route: admin.reports.general
    public function general(Request $request, ReportService $service): View
    {
        $filters = $request->validate([
            'type'        => ['nullable', 'in:list,summary'],
            'group_by'    => ['nullable', 'in:course,gender,school,process,course_gender'],
            'course_id'   => ['nullable', 'exists:courses,id'],
            'gender'      => ['nullable', 'in:1,2,3,4'],
            'pcd'         => ['nullable', 'in:all,pending,accepted,rejected'],
            'social_name' => ['nullable', 'in:all,pending,accepted,rejected'],
            'school'      => ['nullable', 'string', 'max:100'],
            'process_id'  => ['nullable', 'exists:processes,id'],
        ]);

        $type = $filters['type'] ?? 'list';

        return view('admin.reports.general', [
            'type'         => $type,
            'groupBy'      => $filters['group_by'] ?? 'course',
            'inscriptions' => $type === 'list' ? $service->candidatesList($filters) : null,
            'summary'      => $type === 'summary' ? $service->summary($filters, $filters['group_by'] ?? 'course') : null,
            'courses'      => Course::orderBy('name')->get(),
            'processes'    => Process::orderByDesc('year')->get(),
        ]);
    }

    public function generalPdf(Request $request, ReportService $service)
    {
        $filters = $request->validate([
            'type'        => ['nullable', 'in:list,summary'],
            'group_by'    => ['nullable', 'in:course,gender,school,process,course_gender'],
            'course_id'   => ['nullable', 'exists:courses,id'],
            'gender'      => ['nullable', 'in:1,2,3,4'],
            'pcd'         => ['nullable', 'in:all,pending,accepted,rejected'],
            'social_name' => ['nullable', 'in:all,pending,accepted,rejected'],
            'school'      => ['nullable', 'string', 'max:100'],
            'process_id'  => ['nullable', 'exists:processes,id'],
        ]);

        $type = $filters['type'] ?? 'list';

        $pdf = Pdf::loadView('admin.reports.general-pdf', [
            'type'            => $type,
            'groupBy'         => $filters['group_by'] ?? 'course',
            'selectedProcess' => ! empty($filters['process_id']) ? Process::find($filters['process_id']) : null,
            'inscriptions'    => $type === 'list' ? $service->candidatesListAll($filters) : null,
            'summary'         => $type === 'summary' ? $service->summary($filters, $filters['group_by'] ?? 'course') : null,
        ]);

        return $pdf->stream('relatorio-geral.pdf');
    }

    public function registrantsByCourse(): View
    {
        $inscriptions = Inscription::with(['course', 'user'])
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->orderBy('courses.name')
            ->select('inscriptions.*')
            ->get();

        // Candidatos por curso
        $courses = DB::table('inscriptions')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select('courses.name as curso', DB::raw('COUNT(inscriptions.id) as total'))
            ->groupBy('courses.name')
            ->orderByDesc('total')
            ->get();

        return view('admin.reports.registrants-by-course', [
            'inscriptions' => $inscriptions,
            'cursos' => $courses
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