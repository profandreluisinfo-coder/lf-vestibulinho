<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BurghsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return DB::table('users')
        ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
        ->select('users.burgh', DB::raw('COUNT(DISTINCT users.id) as total'))
        ->groupBy('users.burgh')
        ->orderByDesc('total')
        ->limit(10)
        ->get();
    }

    public function headings(): array
    {
        return ['Bairro', 'Total de Candidatos'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}