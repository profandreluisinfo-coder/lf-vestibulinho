<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration',
        'info',
        'vacancies',
    ];

    public $timestamps = false;

    // Obter a descricao do curso
    public static function getDescription($id): string
    {
        return static::findOrFail($id)->description;
    }

    // Obter a soma de vagas de todos os cursos
    public static function getVacancies(): int
    {
        return static::sum('vacancies');
    }

    // Obter a quantidade de candidatos inscritos por curso
    public static function getInscriptionsCount(): Collection
    {
        return Course::withCount('inscriptions')
            ->orderByDesc('inscriptions_count')
            ->limit(10)
            ->get(['id', 'name']);
    }

    public static function getCandidatesByGender()
    {
        return DB::table('users')
            ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->select(
                'users.gender',
                DB::raw('COUNT(users.id) as total')
            )
            ->groupBy('users.gender')
            ->orderBy('users.gender')
            ->get();
    }

    public static function getGendersByCourses()
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

    // Relacionamentos
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }
}
