<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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