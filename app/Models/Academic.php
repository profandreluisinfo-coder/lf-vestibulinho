<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Academic extends Model
{
    protected $fillable = [
        'user_id',
        'school',
        'city',
        'state',
        'year',
        'ra',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna o valor do atributo 'ra' formatado como XXX.XXX.XXX-Y.
     * O formato aceita 9 dígitos seguidos de 1 letra ou número.
     * Se o valor não seguir o formato, ele será retornado sem alterações.
     *
     * @param string $value    O valor do atributo 'ra'
     * @return string    O valor do atributo 'ra' formatado como XXX.XXX.XXX-Y
     */
    public function getRaAttribute($value)
    {
        // Aceita 9 dígitos seguidos de 1 letra ou número
        if (preg_match('/^(\d{9})([A-Za-z0-9])$/', $value, $matches)) {
            return substr($matches[1], 0, 3) . '.' .
                substr($matches[1], 3, 3) . '.' .
                substr($matches[1], 6, 3) . '-' .
                $matches[2];
        }

        return $value;
    }

    /**
     * Remove apenas pontos e traços do valor do atributo 'ra'.
     *
     * @param string $value    O valor do atributo 'ra'
     * @return void
     */
    public function setRaAttribute($value)
    {
        // Remove apenas pontos e traços
        $cleaned = str_replace(['.', '-'], '', $value);

        $this->attributes['ra'] = $cleaned;
    }
}