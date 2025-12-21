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

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answer()
    {
        return $this->hasOne(Answer::class);
    }

    public static function getActiveArchives()
    {
        return Cache::remember('archives_active_list', 3600, function () {
            return self::where('status', true)->orderBy('year', 'desc')->get();
        });
    }

    public static function hasActiveArchives()
    {
        return Cache::remember('archives_has_active', 3600, function () {
            return self::where('status', true)->exists();
        });
    }

    // Limpar cache ao salvar/excluir - CORRIGIDO
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('archives_active_list');
            Cache::forget('archives_has_active');
        });
        
        static::deleted(function () {
            Cache::forget('archives_active_list');
            Cache::forget('archives_has_active');
        });
    }
}