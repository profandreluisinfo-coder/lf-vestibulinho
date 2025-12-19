<?php

namespace App\Http\Controllers\App;

use App\Models\ExamResult;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ExamReportService;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    // Relatório de Alocação
    public function exportAllocationToPdf(ExamReportService $reportService)
    {
        $allocations = $reportService->getGroupedAllocations();

        $pdf = Pdf::loadView('reports.pdf.allocation', compact('allocations'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('relatorio-alocacao.pdf');
    }

    // Lista para Salas
    public function exportRoomsToPdf(ExamReportService $reportService)
    {
        $allocations = $reportService->getGroupedAllocations();

        $pdf = Pdf::loadView('reports.pdf.rooms', compact('allocations'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('relatorio-salas.pdf');
    }

    // Lista de Assinaturas
    public function exportSignaturesSheetsToPdf()
    {
        $allocations = ExamResult::join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->join('exam_locations', 'exam_results.exam_location_id', '=', 'exam_locations.id')
            ->select(
                'exam_locations.name as location_name',
                'exam_results.room_number',
                'users.name',
                'users.social_name',
                'users.cpf',
                'users.birth',
                'users.pne',
                'inscriptions.id as inscription_id' // 👈 aqui
            )
            ->orderBy('exam_results.exam_location_id')
            ->orderBy('exam_results.room_number')
            ->orderBy('users.name')
            ->get()
            ->groupBy(['location_name', 'room_number']);

        $pdf = Pdf::loadView('reports.pdf.signatures', compact('allocations'))->setPaper('a4', 'portrait');

        return $pdf->stream('relatorio-alocacao-horizontal.pdf');
    }
}
