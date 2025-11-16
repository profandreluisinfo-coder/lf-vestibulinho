<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersWithInscriptionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::where('role', 'user')
            ->whereHas('inscription')
            ->orderBy('name', 'asc')
            ->with('inscription')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Número da Inscrição',
            'ID do Candidato',
            'CPF',
            'Nome',
            'Data de Nascimento',
            'Pontos',
        ];
    }

    public function map($user): array
    {
        return [
            $user->inscription->id ?? '',
            $user->id,
            $user->cpf,
            $user->social_name ?: $user->name, // ✅ Aqui entra a prioridade para nome social
            $user->birth ? $user->birth->format('Y-m-d') : '',
        ];
    }
}