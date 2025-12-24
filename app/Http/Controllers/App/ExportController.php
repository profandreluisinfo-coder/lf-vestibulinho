<?php

namespace App\Http\Controllers\App;

use App\Models\Inscription;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersWithInscriptionsExport;
use App\Models\ExamResult;
use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    public function exportUsers()
    {
        // Verificar se existem inscrições antes de exportar
        if (!Inscription::exists()) {
            return redirect()->route('dash.admin.home')->with('error', 'Nenhuma inscrição encontrada.');
        }

        // Verificar se existe prova agendada
        if (!ExamResult::exists()) {
            return redirect()->route('dash.admin.home')->with('error', 'Não é possível exportar os candidatos, pois não existe uma prova agendada.');
        }
        
        return Excel::download(new UsersWithInscriptionsExport, 'candidatos.xlsx');
    }
}