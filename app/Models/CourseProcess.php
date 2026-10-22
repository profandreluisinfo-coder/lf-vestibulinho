<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseProcess extends Model
{
    protected $table = 'course_process';

    protected $fillable = [
        'course_id',
        'process_id',
        'vacancies',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }
}