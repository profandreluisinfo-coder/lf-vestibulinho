<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CallList extends Model
{
    protected $fillable = ['number', 'date', 'time', 'status'];

    protected function casts(): array
    {
        return [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];
    }

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }

    public function examResults(): BelongsToMany
    {
        return $this->belongsToMany(ExamResult::class, 'call_list_exam_result');
    }
}
