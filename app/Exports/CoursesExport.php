<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CoursesExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return DB::table('inscriptions')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select('courses.name as curso', DB::raw('COUNT(inscriptions.id) as total'))
            ->groupBy('courses.name')
            ->orderByDesc('total')
            ->get();
    }

    public function headings(): array
    {
        return ['Curso', 'Total de Inscritos'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}