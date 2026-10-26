<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'inscription_id',
        'location_id',
        'room_number'
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
