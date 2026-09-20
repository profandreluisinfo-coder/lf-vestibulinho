<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GendersExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select('gender', DB::raw('COUNT(users.id) as total'))
            ->groupBy('gender')
            ->orderBy('gender')
            ->get()
            ->map(function ($row) {
                $row->gender = User::GENDERS[$row->gender] ?? $row->gender;
                return $row;
            });
    }

    public function headings(): array
    {
        return ['Sexo', 'Total de Inscritos'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}