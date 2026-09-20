<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchoolsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return DB::table('academics')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'academics.user_id')
            ->select('academics.school', DB::raw('COUNT(DISTINCT academics.user_id) as total'))
            ->groupBy('academics.school')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    public function headings(): array
    {
        return ['Escola', 'Total de Candidatos'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
