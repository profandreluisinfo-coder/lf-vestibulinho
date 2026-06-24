<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Father extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone' 
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
     * Converte o valor do atributo 'name' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'name'
     * @return $this
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->toUpper($value);
    }

    /**
     * Converte o valor para maiúsculas, tratando nulos e espaços.
     *
     * @param  string|null  $value
     * @return string|null
     */
    private function toUpper(?string $value): ?string
    {
        return $value ? Str::of(trim($value))->upper() : null;
    }
    
    /**
     * Retorna o valor do atributo 'phone' formatado como (99) 99999-9999.
     *
     * @param string $value    O valor do atributo 'phone'
     * @return string    O valor do atributo 'phone' formatado como (99) 99999-9999
     */
    public function getPhoneAttribute($value)
    {
        // return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value);
        return $this->formatPhone($value);
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'phone'.
     *
     * @param string $value    O valor do atributo 'phone'
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = str_replace(['(', ')', ' ', '-'], '', $value);
    }

    /**
     * Formata o valor do atributo 'telefone' no formato (XX) XXXX-XXXX.
     * O formato aceita 11 dígitos seguidos de 1 letra ou número.
     * Se o valor não seguir o formato, ele será retornado sem alterações.
     *
     * @param string $value    O valor do atributo 'telefone'
     * @return string    O valor do atributo 'telefone' formatado como (XX) XXXX-XXXX
     */
    private function formatPhone($value)
    {
        return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value);
    }    
}
