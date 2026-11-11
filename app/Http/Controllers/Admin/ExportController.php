<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersWithInscriptionsExport;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Inscription;
use App\Models\Process;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BurghsExport;
use App\Exports\CoursesExport;
use App\Exports\GenderPerCourseExport;
use App\Exports\GendersExport;
use App\Exports\SchoolsExport;
use Illuminate\View\View;

class ExportController extends Controller
{
    public function index(): View
    {
        $process = Process::current();
        $hasApprovedInscriptions = $process
            && Inscription::approved()->forProcess($process->id)->exists();
        $hasExam = $process
            && Exam::whereHas('inscription', fn ($query) => $query->forProcess($process->id))->exists();

        return view('admin.exports.index', [
            'hasCurrentProcess' => (bool) $process,
            'hasApprovedInscriptions' => (bool) $hasApprovedInscriptions,
            'hasExam' => (bool) $hasExam,
            'canExportCandidates' => (bool) ($hasApprovedInscriptions && $hasExam),
            'appealsPeriodOpen' => $process?->appeals_end
                ? today()->lte($process->appeals_end)
                : false,
            'appealsEnd' => $process?->appeals_end,
        ]);
    }

    public function exporToExcel()
    {
        $process = Process::current();

        // Verificar se existem inscrições do processo atual antes de exportar
        if (! $process || ! Inscription::approved()->forProcess($process?->id)->exists()) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Nenhuma inscrição encontrada.'], 422);
            }

            return alertError('Nenhuma inscrição encontrada.');
        }

        // Verificar se existe prova agendada (candidatos alocados em salas)
        $hasExam = Exam::whereHas('inscription', fn ($q) => $q->forProcess($process?->id))->exists();

        if (! $hasExam) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Não é possível exportar os dados dos candidatos, pois no momento não existe uma prova agendada.',
                ], 422);
            }

            return alertError('Não é possível exportar os dados dos candidatos, pois no momento não existe uma prova agendada.');
        }

        return Excel::download(new UsersWithInscriptionsExport($process), 'candidatos.xlsx');
    }

    public function exportCoursesExcel()
    {
        $export = new CoursesExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'candidatos_por_curso.xlsx');
    }

    public function gendersToExcel()
    {
        $export = new GendersExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'candidatos_por_sexo.xlsx');
    }

    public function genderPerCourseToExcel()
    {
        $export = new GenderPerCourseExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'candidatos_por_curso_e_sexo.xlsx');
    }

    public function burghsToExcel()
    {
        $export = new BurghsExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'bairros_com_mais_candidatos.xlsx');
    }

    public function schoolsToExcel()
    {
        $export = new SchoolsExport;

        if ($export->collection()->isEmpty()) {
            return alertError('Não há dados para exportar.');
        }

        return Excel::download($export, 'escolas_de_origem.xlsx');
    }
}
