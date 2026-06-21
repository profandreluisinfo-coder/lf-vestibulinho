<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'selection_process_id'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Obtenha o calendário associado ao curso.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class);
    }

    /**
     * Obtenha o resultado do exame associado à inscrição.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function exam_result(): HasOne
    {
        return $this->hasOne(ExamResult::class, 'inscription_id');
    }

    /**
     * Obtenha o curso associado à inscrição.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Obtenha o usuário que realizou a inscrição.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenha as chamadas associadas ao resultado do exame.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls(): HasMany
    {
        return $this->hasMany(Call::class, 'exam_result_id');
    }

    /**
     * Obtenha o processo de seleção associado ao curso.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function selection_process(): BelongsTo
    {
        return $this->belongsTo(SelectionProcess::class);
    }

    // 🔹 Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_total_inscriptions'));
        static::deleted(fn() => Cache::forget('global_total_inscriptions'));
    }
}