<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Health extends Model
{
    protected $fillable = [
        'user_id',
        'problem'  
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}