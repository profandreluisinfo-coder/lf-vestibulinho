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
}
