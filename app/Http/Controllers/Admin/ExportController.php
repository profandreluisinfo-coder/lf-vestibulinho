<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersWithInscriptionsExport;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Inscription;
use App\Models\Process;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exporToExcel()
    {
        $process = Process::current();

        // Verificar se existem inscrições do processo atual antes de exportar
        if (! $process || ! Inscription::approved()->forProcess($process->id)->exists()) {
            return alertError('Nenhuma inscrição encontrada.');
        }

        // Verificar se existe prova agendada (candidatos alocados em salas)
        $hasExam = Exam::whereHas('inscription', fn ($q) => $q->forProcess($process->id))->exists();

        if (! $hasExam) {
            return alertError('Não é possível exportar os dados dos candidatos, pois no momento não existe uma prova agendada.');
        }

        return Excel::download(new UsersWithInscriptionsExport($process), 'candidatos.xlsx');
    }
}