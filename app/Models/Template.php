<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'process_id',
        'file_path',
        'status',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public static function getActiveTemplate()
    {
        return self::where('status', 'inactive')->first();
    }
}
