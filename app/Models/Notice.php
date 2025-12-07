<?php

namespace App\Models;

use App\Models\Answer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['file', 'status', 'user_id'];

    /**
     * Scope: avisos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Verifica se existe algum aviso ativo
     */
    public static function hasActive()
    {
        return self::active()->exists();
    }
    
    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_notice'));
        static::deleted(fn() => Cache::forget('global_notice'));
    }
}