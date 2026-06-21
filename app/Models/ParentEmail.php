<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentEmail extends Model
{
    protected $table = 'parent_emails';

    protected $fillable = [
        'user_id',
        'email',
    ];
}
