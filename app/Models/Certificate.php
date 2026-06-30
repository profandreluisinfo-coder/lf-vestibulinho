<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'number',
        'fls',
        'book',
        'city'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Converte o valor do atributo 'fls' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'fls'
     */
    public function getFlsAttribute($value)
    {
        return Str::of($value)->upper();
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
    
    /**
     * Converte o valor do atributo 'city' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'city'
     */
    public function getCityAttribute($value)
    {
        return Str::of($value)->upper();
    }
}
