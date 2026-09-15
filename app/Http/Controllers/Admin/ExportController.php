<?php

namespace App\Http\Controllers\Admin;

use App\Models\Inscription;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersWithInscriptionsExport;
use App\Models\ExamResult;
use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    public function exporToExcel()
    {
        // Verificar se existem inscrições antes de exportar
        if (!Inscription::exists()) {
            return alertError('Nenhuma inscrição encontrada.');
        }

        // Verificar se existe prova agendada
        if (ExamResult::count() === 0) {
            return alertError('Não é possível exportar os dados dos candidatos, pois no momentonão existe uma prova agendada.');
        }

        return Excel::download(new UsersWithInscriptionsExport, 'candidatos.xlsx');
    }
}
