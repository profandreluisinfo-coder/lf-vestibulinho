<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'number',
        'expedition',
    ];

    protected $casts = [
        'expedition' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Acessors e Mutators

    public function getTypeAttribute($value)
    {
        $types = [
            '1' => 'RG -  Registro Geral',
            '2' => 'CIN - Carteira de Identidade Nacional',
            '3' => 'RNM - Registro Nacional Migratório',
        ];

        return Str::of($types[$value])->upper() ?? $value;  // Se não encontrar, retorna o valor original
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'number'.
     *
     * @param  string  $value  O valor do atributo 'number'
     */
    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = str_replace(['.', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'number' formatado como 99.999.999-9.
     *
     * @param  string  $value  O valor do atributo 'number'
     * @return string O valor do atributo 'number' formatado como 99.999.999-9
     */
    public function getNumberAttribute($value)
    {
        // Formato ao retornar deve ser 99.999.999-9
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{1})/', '$1.$2.$3-$4', $value);
    }

    /**
     * Verifica se a data de expedição é maior ou igual a 5 anos atrás.
     *
     * @return bool true se a expedição foi há 5 ou mais anos, false caso contrário
     */
    public function isExpeditionOlderThanFiveYears(): bool
    {
        if (! $this->expedition) {
            return false;
        }

        return $this->expedition->addYears(5)->isPast();
    }
}