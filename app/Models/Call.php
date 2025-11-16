<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Call extends Model
{
    protected $fillable = [
        'exam_result_id',
        'call_list_id',
        'call_number',
        'amount',
        'date',
        'time',
        'is_manual',
    ];

    protected $casts = [
        'date'      => 'date',
        'time'      => 'datetime:H:i',
        'is_manual' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relações
    |--------------------------------------------------------------------------
    */

    public function callList()
    {
        return $this->belongsTo(CallList::class);
    }

    public function examResult()
    {
        return $this->belongsTo(ExamResult::class);
    }

    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeCompleted($query)
    {
        return $query->whereHas('callList', fn($q) => $q->where('status', 'completed'));
    }

    /*
    |--------------------------------------------------------------------------
    | Eventos: limpa cache automaticamente
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('calls_exists'));
        static::deleted(fn() => Cache::forget('calls_exists'));
    }
}
