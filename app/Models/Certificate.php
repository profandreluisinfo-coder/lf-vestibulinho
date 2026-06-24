<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'number',
        'fls',
        'book',
        'municipality'
    ];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Converte o valor do atributo 'fls' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'fls'
     */
    public function setFlsAttribute($value)
    {
        $this->attributes['fls'] = strtoupper($value);
    }

    /**
     * Remove qualquer coisa que não seja número do atributo 'number'.
     *
     * @param string $value    O valor do atributo 'number'
     * @return void
     */
    public function setNumberAttribute($value)
    {
        // Remove qualquer coisa que não seja número
        $this->attributes['number'] = preg_replace('/\D/', '', $value);
    }

    /**
     * Aplica o formato 99.99999 ao valor do atributo 'number'.
     *
     * @param string $value    O valor do atributo 'number'
     * @return string    O valor do atributo 'number' formatado como 99.99999
     */
    public function getNumberAttribute($value)
    {
        // Aplica o formato 99.99999
        return preg_replace('/^(\d{2})(\d{5})$/', '$1.$2', $value);
    }
}
