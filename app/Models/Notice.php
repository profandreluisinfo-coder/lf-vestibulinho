<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['year', 'file', 'status'];

    // Limpa o cache automaticamente quando salvar ou excluir
    public static function booted()
    {
        static::saved(function () {
            cache()->forget('global_notice');
        });

        static::deleted(function () {
            cache()->forget('global_notice');
        });
    }
}