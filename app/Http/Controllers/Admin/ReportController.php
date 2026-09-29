<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Inscription;
use App\Models\Process;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    // route: admin.reports.general
    public function general(Request $request, ReportService $service): View
    {
        $filters = $request->validate([
            'type'        => ['nullable', 'in:list,summary'],
            'group_by' => ['nullable', 'in:course,gender,school,process,course_gender'],
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

    /**
     * Mostra a lista de resultados na página de classificação do painel administrativo com base na nota de corte
     *
     * @return \Illuminate\View\View
     */
    public function classification()
    {
        // determinar o limite de notas de corte
        $limit = Course::sum('vacancies') * 3;

        // Busca todos os resultados com os dados do candidato, nome social e PCD.
        $results = ExamResult::whereNotNull('score')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->leftJoin('lgbts', 'users.id', '=', 'lgbts.user_id')
            ->leftJoin('pnes', 'users.id', '=', 'pnes.user_id')
            ->select(
                'inscriptions.id',
                'users.name',
                'lgbts.name as social_name',
                'users.birth',
                'pnes.id as pne',
                'exam_results.score',
                'exam_results.ranking'
            )
            ->orderBy('exam_results.ranking')
            ->get();

        // ✅ 1️⃣ Pega o último dentro do limite
        $lastInLimit = $results->where('ranking', '<=', $limit)->last();

        // ✅ 2️⃣ Descobre a nota de corte
        $cutoffScore = $lastInLimit ? $lastInLimit->score : 0;

        // envia pra view
        view()->share([
            'results' => $results,
            'limit' => $limit,
            'cutoffScore' => $cutoffScore,
        ]);

        return view('admin.results.index');
    }
}