<?php

namespace App\Services;

use App\Models\Inscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportService
{
    // Monta a consulta com os filtros (usada pela Lista e pelo Resumo)
    private function filteredQuery(array $filters): Builder
    {
        $query = Inscription::query();

        if (! empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (! empty($filters['gender'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('gender', $filters['gender']);
            });
        }

        if (! empty($filters['pcd'])) {
            $query->whereHas('user.pne', function ($q) use ($filters) {
                if ($filters['pcd'] !== 'all') {
                    $q->where('status', $filters['pcd']);
                }
            });
        }

        if (! empty($filters['social_name'])) {
            $query->whereHas('user.lgbt', function ($q) use ($filters) {
                if ($filters['social_name'] !== 'all') {
                    $q->where('status', $filters['social_name']);
                }
            });
        }

        // Escola: busca por parte do nome
        if (! empty($filters['school'])) {
            $query->whereHas('user.academic', function ($q) use ($filters) {
                $q->where('school', 'like', '%' . $filters['school'] . '%');
            });
        }

        // Processo/ano
        if (! empty($filters['process_id'])) {
            $query->where('inscriptions.process_id', $filters['process_id']);
        }

        return $query;
    }

    // RELATÓRIO TIPO LISTA
    public function candidatesList(array $filters): LengthAwarePaginator
    {
        return $this->filteredQuery($filters)
            ->with(['user.pne', 'user.lgbt', 'user.academic', 'course'])
            ->latest('id')
            ->paginate(20)
            ->withQueryString();
    }

    // Mesma lista, mas SEM paginação (para o PDF)
    public function candidatesListAll(array $filters): \Illuminate\Database\Eloquent\Collection
    {
        return $this->filteredQuery($filters)
            ->with(['user.pne', 'user.lgbt', 'user.academic', 'course'])
            ->latest('id')
            ->get();
    }

    // RELATÓRIO TIPO RESUMO
    public function summary(array $filters, string $groupBy): Collection
    {
        if ($groupBy === 'course_gender') {
            return $this->courseByGender($filters);
        }

        // Lista permitida: só estes agrupamentos existem
        $columns = [
            'course'  => 'courses.name',
            'gender'  => 'users.gender',
            'school'  => 'academics.school',
            'process' => 'processes.year',
        ];

        $column = $columns[$groupBy] ?? $columns['course'];

        $rows = $this->filteredQuery($filters)
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->join('users', 'users.id', '=', 'inscriptions.user_id')
            ->leftJoin('academics', 'academics.user_id', '=', 'users.id')
            ->leftJoin('processes', 'processes.id', '=', 'inscriptions.process_id')
            ->select($column . ' as grupo', DB::raw('COUNT(inscriptions.id) as total'))
            ->groupBy($column)
            ->orderByDesc('total')
            ->get();

        // O gênero vem como número (1, 2...). Aqui viramos texto.
        if ($groupBy === 'gender') {
            $rows->transform(function ($row) {
                $row->grupo = \App\Models\User::GENDERS[$row->grupo] ?? $row->grupo;
                return $row;
            });
        }

        // Quem não tem o dado (ex.: sem escola cadastrada) aparece como "Não informado"
        $rows->transform(function ($row) {
            if ($row->grupo === null || $row->grupo === '') {
                $row->grupo = 'Não informado';
            }
            return $row;
        });

        return $rows;
    }

    // RESUMO: CURSO x GÊNERO
    private function courseByGender(array $filters): Collection
    {
        return $this->filteredQuery($filters)
            ->join('courses', 'courses.id', '=', 'inscriptions.course_id')
            ->join('users', 'users.id', '=', 'inscriptions.user_id')
            ->select(
                'courses.name as grupo',
                DB::raw('SUM(CASE WHEN users.gender = 1 THEN 1 ELSE 0 END) as masculino'),
                DB::raw('SUM(CASE WHEN users.gender = 2 THEN 1 ELSE 0 END) as feminino'),
                DB::raw('SUM(CASE WHEN users.gender = 3 THEN 1 ELSE 0 END) as outro'),
                DB::raw('SUM(CASE WHEN users.gender = 4 THEN 1 ELSE 0 END) as nao_informado'),
                DB::raw('COUNT(inscriptions.id) as total')
            )
            ->groupBy('courses.name')
            ->orderBy('courses.name')
            ->get();
    }
}
