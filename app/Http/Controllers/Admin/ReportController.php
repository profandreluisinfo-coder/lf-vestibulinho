<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pne;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function lgbts(): View
    {
        return view('admin.reports.lgbts');
    }

    public function pcds(): View
    {
        $pcds = Pne::where('status', 'accepted')
            ->with(['user.inscription', 'user.lgbt'])
            ->paginate(20);

        return view('admin.reports.pcds', [
            'pcds' => $pcds
        ]);
    }

    public function pcdsToPdf()
    {
        $pcds = Pne::where('status', 'accepted')
            ->with(['user.inscription', 'user.lgbt'])
            ->get(); // sem paginate aqui — o PDF deve conter todos os registros

        $pdf = Pdf::loadView('admin.reports.pcds-pdf', [
            'pcds' => $pcds
        ]);

        return $pdf->download('relatorio-pcd.pdf');
    }
}
