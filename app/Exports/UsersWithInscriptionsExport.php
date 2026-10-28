<?php

namespace App\Exports;

use App\Models\Inscription;
use App\Models\Lgbt;
use App\Models\Process;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersWithInscriptionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Process $process) {}

    /**
     * Inscrições do processo com prova agendada, em ordem de local, sala e nome,
     * para facilitar a conferência durante a correção. Os cabeçalhos continuam
     * exatamente os que o ImportController exige.
     */
    public function collection()
    {
        return Inscription::approved()
            ->forProcess($this->process->id)
            ->whereHas('exam')
            ->whereHas('user', fn ($q) => $q->where('role', 'user'))
            ->with(['user.lgbt', 'exam'])
            ->get()
            ->sortBy(fn ($i) => sprintf(
                '%05d-%05d-%s',
                $i->exam->location_id,
                $i->exam->room_number,
                Str::lower(Str::ascii($this->displayName($i)))
            ))
            ->values();
    }

    public function headings(): array
    {
        return [
            'inscription_id',
            'user_id',
            'user_cpf',
            'user_name',
            'user_birth',
            'points',
        ];
    }

    public function map($inscription): array
    {
        return [
            $inscription->id,
            $inscription->user->id,
            $inscription->user->cpf,
            $this->displayName($inscription),
            $inscription->user->birth,
            '',
        ];
    }

    private function displayName(Inscription $inscription): string
    {
        $lgbt = $inscription->user->lgbt;

        return $lgbt?->status === Lgbt::STATUS_ACCEPTED
            ? $lgbt->name
            : $inscription->user->name;
    }
}