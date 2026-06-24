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

    /**
     * Defina o valor de um determinado atributo no modelo.
     *
     * Se o valor for uma string vazia, ele será convertido em nulo.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // Se o valor for string vazia, converte para null
        if ($value === "") {
            $value = null;
        }

        return parent::setAttribute($key, $value);
    }

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