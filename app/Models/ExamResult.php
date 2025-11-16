<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $table = "exam_results";

    protected $fillable = [
        'inscription_id',
        'exam_date',
        'exam_time',
        'score',
        'ranking',
        'room_number',
        'exam_location_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relações principais
    |--------------------------------------------------------------------------
    */

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    // Recomendo renomear para examLocation (fica mais intuitivo)
    public function examLocation()
    {
        return $this->belongsTo(ExamLocation::class, 'exam_location_id');
    }

    // Relação com todas as chamadas
    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    // Acesso direto ao usuário da inscrição
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Inscription::class,
            'id',              // PK de Inscription
            'id',              // PK de User
            'inscription_id',  // FK em ExamResult
            'user_id'          // FK em Inscription
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Chamada finalizada diretamente do ExamResult
    |--------------------------------------------------------------------------
    */

    public function completedCall()
    {
        return $this->hasOne(Call::class)
            ->completed()       // usa o scope do teu model Call
            ->with('callList'); // já puxa a lista junto
    }
}