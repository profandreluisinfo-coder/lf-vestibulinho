<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Call extends Model
{
    protected $fillable = [
        'exam_result_id',
        'call_list_id',
        'call_number',
        'amount',
        'date',
        'time',
        'is_manual',
    ];

    protected $casts = [
        'date'      => 'date',
        'time'      => 'datetime:H:i',
        'is_manual' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relações
    |--------------------------------------------------------------------------
    */

    /**
     * Relação com a lista de chamada que essa chamada pertence.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function callList()
    {
        return $this->belongsTo(CallList::class);
    }

    /**
     * Relação com o resultado de exame que essa chamada pertence.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examResult()
    {
        return $this->belongsTo(ExamResult::class);
    }

    /**
     * Relação com todas as chamadas associadas a essa chamada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeCompleted($query)
    {
        return $query->whereHas('callList', fn($q) => $q->where('status', 'completed'));
    }

    /*
    |--------------------------------------------------------------------------
    | Eventos: limpa cache automaticamente
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('calls_exists'));
        static::deleted(fn() => Cache::forget('calls_exists'));
    }

    /*
    |--------------------------------------------------------------------------
    | Funções: verificar quantos canddidatos com necessidade especial existem 
    | na última chamada
    |--------------------------------------------------------------------------
    */
    public static function countPcdInLastCall()
    {
        // Pega o ID da última call_list
        $lastCallListId = CallList::orderByDesc('number')
            ->value('id');

        if (!$lastCallListId) {
            return 0;
        }

        // Conta quantos chamados são PCD na última call_list
        return self::where('call_list_id', $lastCallListId)
            ->whereHas(
                'examResult.inscription.user',
                fn($q) =>
                $q->where('pne', true)
            )
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna a lista de convocados com necessidade especial na última chamada.
    | @return \Illuminate\Database\Eloquent\Collection
    |
    | Exemplo do que conseguir acessar em cada item da lista:
    |
    | $lista = Call::pcdListInLastCall();
    |
    | foreach ($lista as $call) {
    |    $user = $call->examResult->inscription->user;
    |
    |    echo $user->name;
    |    echo $user->email;
    |    echo $call->examResult->ranking;
    | }
    |--------------------------------------------------------------------------
    */
    public static function pcdListInLastCall()
    {
        $lastCallListId = CallList::orderByDesc('number')->value('id');

        return self::with('examResult.inscription.user')
            ->where('call_list_id', $lastCallListId)
            ->whereHas(
                'examResult.inscription.user',
                fn($q) =>
                $q->where('pne', true)
            )
            ->get();
    }
}
