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

    protected $casts = [
        'location' => 'boolean',
        'result' => 'boolean',
    ];

    /**
     * Defina o valor de um determinado atributo no modelo.
     *
     * Se o valor for uma string vazia, ele será convertido em nulo.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // Se o valor for string vazia, converte para null
        if ($value === "") {
            $value = null;
        }

        return parent::setAttribute($key, $value);
    }
   
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