<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CallList extends Model
{
    protected $fillable = [
        'process_id',
        'number',
        'date',
        'time',
        'status',
        'published_at',
        'published_by',
        'vacancy_course_id',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'time' => 'datetime:H:i',
            'published_at' => 'datetime',
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
     * Relação com todas as chamadas associadas a essa lista de chamada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function vacancyCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'vacancy_course_id');
    }
}
