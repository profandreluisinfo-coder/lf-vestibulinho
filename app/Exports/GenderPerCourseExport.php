<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenderPerCourseExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('inscriptions')
            ->join('users', 'users.id', '=', 'inscriptions.user_id')
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->select(
                'courses.name as course',
                DB::raw("SUM(CASE WHEN users.gender = 1 THEN 1 ELSE 0 END) as masculino"),
                DB::raw("SUM(CASE WHEN users.gender = 2 THEN 1 ELSE 0 END) as feminino")
            )
            ->groupBy('courses.name')
            ->orderBy('courses.name')
            ->get();
    }

    public function headings(): array
    {
        return ['Curso', 'Masculino', 'Feminino'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
