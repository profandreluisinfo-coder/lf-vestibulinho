<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialName extends Model
{
    protected $table = 'social_names';

    protected $fillable = [
        'user_id',
        'name',
        'file',
        'status',
        'response',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
