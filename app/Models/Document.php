<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'type',
        'description'
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
