<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamLocation extends Model
{
    protected $table = 'exam_locations';

    protected $fillable = [
        'id',
        'name',
        'address',
        'rooms_available',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($examLocation) {
    //         // Exclui todos os resultados associados antes de deletar o local
    //         $examLocation->results()->delete(); // Assumindo que há uma relação 'results()'
    //     });
    // }
}
