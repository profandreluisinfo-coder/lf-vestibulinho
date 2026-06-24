<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Academic extends Model
{
    protected $table = 'academic';
    
    protected $fillable = [
        'user_id',
        'school',
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