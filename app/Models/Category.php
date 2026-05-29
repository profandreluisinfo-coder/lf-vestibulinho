<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['category'];

    public function getNormalizedCategoryAttribute()
    {
        return Str::lower(
            Str::ascii($this->category)
        );
    }
}