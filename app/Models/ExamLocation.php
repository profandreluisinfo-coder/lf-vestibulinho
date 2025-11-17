<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamLocation extends Model
{
    protected $table = 'exam_locations';

    protected $fillable = [
        'name',
        'address',
        'rooms_available',
    ];
}
