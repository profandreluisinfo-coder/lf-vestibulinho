<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'inscription_id',
        'score',
        'ranking',
        'status',
        'published_at',
    ];

    protected $casts = [
        'status'       => 'boolean',
        'published_at' => 'datetime',
    ];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }
}
