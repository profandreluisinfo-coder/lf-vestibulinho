<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $attributes = [
        'location' => false,
        'result' => false,
    ];

    protected $fillable = [
        'location',
        'result',
    ];

    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_settings'));
        static::deleted(fn() => Cache::forget('global_settings'));
    }
}
