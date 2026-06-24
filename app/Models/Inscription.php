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
     * Defina o valor de um determinado atributo no modelo.
     *
     * Se o valor for uma string vazia, ele será convertido em nulo.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // Se o valor for string vazia, converte para null
        if ($value === "") {
            $value = null;
        }

        return parent::setAttribute($key, $value);
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