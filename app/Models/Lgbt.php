<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Lgbt extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'authorization', // path do arquivo
        'status',
        'observations',
    ];

    /**
     * Defina o valor de um determinado atributo no modelo.
     *
     * Se o valor for uma string vazia, ele será convertido em nulo.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // Se o valor for string vazia, converte para null
        if ($value === '') {
            $value = null;
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Converte o valor do atributo 'name' para maiúsculo.
     *
     * @param  string  $value  O valor do atributo 'name'
     * @return $this
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->toUpper($value);
    }

    /**
     * Converte o valor para maiúsculas, tratando nulos e espaços.
     */
    private function toUpper(?string $value): ?string
    {
        return $value ? Str::of(trim($value))->upper() : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica e retorna se o atributo 'authorization' está preenchido.
     */
    public function hasAuthorization(): bool
    {
        return ! empty($this->authorization);
    }
}
