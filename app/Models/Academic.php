<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Academic extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'city',
        'state',
        'year',
        'ra',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}