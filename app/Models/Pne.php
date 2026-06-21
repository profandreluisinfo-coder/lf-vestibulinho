<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pne extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'support',
        'report',
        'status',
        'observations'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}