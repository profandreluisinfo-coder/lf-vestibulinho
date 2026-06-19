<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name'];

    public function getNormalizedNameAttribute(): string
    {
        return Str::of($this->name)
            ->ascii()
            ->lower()
            ->toString();
    }
}