<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Call extends Model
{
    protected $fillable = [
        'result_id',
        'call_list_id',
        'call_number',
        'amount',
        'date',
        'time',
        'is_manual',
        'is_pcd_reserve',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'is_manual' => 'boolean',
        'is_pcd_reserve' => 'boolean',
    ];

    public function setAttribute($key, $value)
    {
        if ($value === '') {
            $value = null;
        }

        return parent::setAttribute($key, $value);
    }

    public function callList(): BelongsTo
    {
        return $this->belongsTo(CallList::class);
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    /**
     * Published candidate lists grouped by call-list ID, so repeated numbers
     * (or matching dates and times) remain separate public calls.
     */
    public static function callsCompleted(?int $processId = null)
    {
        return self::with('callList', 'result.inscription.user')
            ->whereHas('callList', fn ($query) => $query
                ->where('status', 'completed')
                ->when($processId, fn ($query) => $query->where('process_id', $processId)))
            ->whereHas('result', fn ($query) => $query->where('classified', true))
            ->get()
            ->groupBy(fn ($call) => implode('|', [
                $call->callList->id,
                $call->callList->number,
                $call->date->format('Y-m-d'),
                $call->time->format('H:i:s'),
            ]))
            ->map(fn ($calls) => $calls->sortBy(fn ($call) => $call->result?->ranking)->values())
            ->sortKeys();
    }

    public function scopeCompleted($query)
    {
        return $query->whereHas('callList', fn ($list) => $list->where('status', 'completed'));
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('calls_exists'));
        static::deleted(fn () => Cache::forget('calls_exists'));
    }
}
