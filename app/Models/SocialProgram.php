<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialProgram extends Model
{
    protected $table = 'social_programs';

    protected $fillable = [
        'user_id',
        'nis',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'nis'.
     *
     * @param string $value    O valor do atributo 'nis'
     * @return void
     */
    public function setNisAttribute($value)
    {
        $this->attributes['nis'] = str_replace(['.', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'nis' formatado como 999.99999-9.
     *
     * @param string $value    O valor do atributo 'nis'
     * @return string    O valor do atributo 'nis' formatado como 999.99999-9
     */
    public function getNisAttribute($value)
    {
        return preg_replace('/(\d{3})(\d{5})(\d{2})(\d{1})/', '$1.$2.$3-$4', $value);
    }
}