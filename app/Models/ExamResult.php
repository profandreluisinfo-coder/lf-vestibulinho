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
    | Verificar se ExamResult não está vazio (sem registros)
    |--------------------------------------------------------------------------
    */
    public static function hasRecords(): bool
    {
        return self::exists();
    }
    
    /*
    |--------------------------------------------------------------------------
    | Verificar se o campo score de ExamResult não está vazio (sem registros com score definido)
    |--------------------------------------------------------------------------
    */
    public static function hasScores(): bool
    {
        return self::whereNotNull('score')->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Relações principais
    |--------------------------------------------------------------------------
    */

    /**
     * Obter a inscrição associada
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    /**
     * Obter o local de realização de prova associado a essa prova.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examLocation()
    {
        return $this->belongsTo(ExamLocation::class);
    }
    
    /**
 * Alias para examLocation — permite usar $result->location
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function location()
{
    return $this->belongsTo(ExamLocation::class, 'exam_location_id');
}


    /**
     * Relação com todas as chamadas associadas a essa prova.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    /**
     * Obter o usuário que realizou a inscrição associada a essa prova.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
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

    /**
     * Obter a chamada finalizada diretamente associada a essa prova.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function completedCall()
    {
        return $this->hasOne(Call::class)
            ->completed()       // usa o scope do teu model Call
            ->with('callList'); // já puxa a lista junto
    }
}
