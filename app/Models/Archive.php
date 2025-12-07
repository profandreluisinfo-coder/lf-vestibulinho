<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = [
        'year',
        'file',
        'status',
        'user_id',
    ];

    /**
     * Obter o usuário que realizou o upload do arquivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna o gabarito ("Answer") vinculado a este aviso, se houver
     */
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }

    /**
     * Retorna o gabarito ("Answer") vinculado a este aviso, se houver
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // Cache::remember('global_archive', 60, fn() => Archive::latest()->first() ?? new Archive());
    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_archive'));
        static::deleted(fn() => Cache::forget('global_archive'));
    }
}
