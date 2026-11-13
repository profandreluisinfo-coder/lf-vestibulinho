<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Result extends Model
{
    protected $fillable = [
        'inscription_id',
        'score',
        'ranking',
        'classified',
        'is_published',
    ];

    protected $casts = [
        'classified'   => 'boolean',
        'is_published' => 'boolean',
    ];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }
}
