<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'zip',
        'street',
        'number',
        'complement',
        'burgh',
        'city',
        'state'
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

    // Acessors e Mutators

    /**
     * Retorna o valor do atributo 'zip' (CEP) formatado como 99.999-99.
     *
     * @param string $value    O valor do atributo 'zip'
     * @return string    O valor do atributo 'zip' formatado como 99.999-99
     */
    public function getZipAttribute($value)
    {
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $value);
    }

    /**
     * Converte o valor do atributo 'city' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'city'
     * @return $this
     */
    public function setCityAttribute($value)
    {
        $this->attributes['city'] = Str::of($value)->upper();;
    }

    /**
     * Converte o valor do atributo 'state' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'state'
     * @return $this
     */
    public function setStateAttribute($value)
    {
        $this->attributes['state'] = Str::of($value)->upper();;
    }
    
}
