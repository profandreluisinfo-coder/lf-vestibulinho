<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'file',
        'status',
        'user_id',
    ];

    // 🔹 Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_notice'));
        static::deleted(fn() => Cache::forget('global_notice'));
    }
}
