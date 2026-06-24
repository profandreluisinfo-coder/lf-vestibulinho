<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CallList extends Model
{
    protected $fillable = ['number', 'date', 'time', 'status'];

    protected function casts(): array
    {
        return [
        'date' => 'date',
        'time' => 'datetime:H:i',
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

    /**
     * Obter todos os resultados de exame associados a essa lista de chamada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function examResults(): BelongsToMany
    {
        return $this->belongsToMany(ExamResult::class, 'call_list_exam_result');
    }
}
