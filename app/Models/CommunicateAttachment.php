<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunicateAttachment extends Model
{
    protected $fillable = ['communicate_id', 'name', 'path', 'size'];
    public function communicate(): BelongsTo
    {
        return $this->belongsTo(Communicate::class);
    }
}
