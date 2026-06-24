<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthIssue extends Model
{
    protected $table = 'health_issues';

    protected $fillable = [
        'description',
    ];
    
    public function health(): HasMany
    {
        return $this->hasMany(Health::class);
    }
}
