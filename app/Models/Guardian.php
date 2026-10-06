<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'degree_id',
        'kinship',
    ];

    // ---------------------------------------------------------------------
    // Relacionamentos
    // ---------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }

    // ---------------------------------------------------------------------
    // Mutators
    // ---------------------------------------------------------------------

    /**
     * Converte o valor do atributo 'name' para maiúsculo.
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value
            ? mb_strtoupper(trim($value))
            : null;
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'phone'.
     */
    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value
            ? preg_replace('/\D/', '', $value)
            : null;
    }

    /**
     * Normaliza 'kinship': string vazia vira null.
     */
    public function setKinshipAttribute($value): void
    {
        $this->attributes['kinship'] = ($value !== null && trim($value) !== '')
            ? trim($value)
            : null;
    }

    // ---------------------------------------------------------------------
    // Acessor
    // ---------------------------------------------------------------------

    /**
     * Retorna o telefone formatado (celular ou fixo).
     */
    public function getPhoneAttribute($value): ?string
    {
        if (!$value) {
            return $value;
        }

        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $digits);
        }

        if (strlen($digits) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $digits);
        }

        return $value;
    }
}