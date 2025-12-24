<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $attributes = [
        'claendar' => false,
        'location' => false,
        'result' => false,
    ];

    protected $fillable = [
        'calendar',
        'location',
        'result',
    ];

    // Retornar o valor de 'location'
    public static function isLocationEnabled()
    {
        $setting = self::first();
        return $setting ? (bool) $setting->location : false;
    }

    // Retornar o valor de 'result'
    public static function isResultEnabled()
    {
        $setting = self::first();
        return $setting ? (bool) $setting->result : false;
    }

    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_settings'));
        static::deleted(fn() => Cache::forget('global_settings'));
    }
}
