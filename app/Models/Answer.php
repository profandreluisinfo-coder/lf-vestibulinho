<?php

namespace App\Models;

use App\Models\Archive;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'archive_id',
        'file',
    ];

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    // Obter o último arquivo de resposta da tabela answers
    public static function getLastAnswerFile()
    {
        $lastAnswer = self::latest()->first();
        return $lastAnswer ? $lastAnswer->file : null;
    }
}