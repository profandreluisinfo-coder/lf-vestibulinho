<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Call extends Model
{
    protected $fillable = [
        'result_id',
        'call_list_id',
        'call_number',
        'amount',
        'date',
        'time',
        'is_manual',
        'course_id',
        'enrollment_status',
        'is_pcd_reserve',
        'regularization_deadline',
        'enrollment_notes',
        'enrollment_recorded_at',
        'enrollment_recorded_by',
    ];

    protected $casts = [
        'date'      => 'date',
        'time'      => 'datetime:H:i',
        'is_manual' => 'boolean',
        'regularization_deadline' => 'datetime',
        'enrollment_recorded_at' => 'datetime',
        'is_pcd_reserve' => 'boolean',
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
    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
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

    /**
     * Obter todas as chamadas finalizadas, agrupadas por lista de chamada.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * Exemplo do que conseguir acessar em cada item da lista:
     * $calls = Call::callsCompleted();
     * foreach ($calls as $call_number => $calls_in_call_number) {
     *     foreach ($calls_in_call_number as $call) {
     *         $user = $call->result->inscription->user;
     *         echo $user->name;
     *         echo $user->email;
     *         echo $call->result->ranking;
     *     }
     * }
     */
    public static function callsCompleted(?int $processId = null)
    {
        return Call::with('callList', 'result.inscription.user')
            ->whereHas('callList', fn ($query) => $query
                ->where('status', 'completed')
                ->when($processId, fn ($query) => $query->where('process_id', $processId)))
            ->whereHas('result', fn ($query) => $query->where('classified', true))
            ->get()
            ->groupBy(function ($call) {
                return $call->callList->number
                    . '|' . $call->date
                    . '|' . $call->time;
            })
            ->map(fn ($calls) => $calls->sortBy(fn ($call) => $call->result?->ranking)->values())
            ->sortKeys();
    }

    // public static function callsCompleted()
    // {
    //     $calls = Call::with('callList', 'examResult.inscription.user')
    //         ->whereHas('callList', fn($q) => $q->where('status', 'completed'))
    //         ->get()
    //         ->groupBy(fn($call) => $call->callList->number)
    //         ->sortKeys();

    //     return $calls;
    // }

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
        $lastCallListId = CallList::orderByDesc('number')
            ->value('id');

        if (!$lastCallListId) {
            return 0;
        }

        return self::where('call_list_id', $lastCallListId)
            ->whereHas('result.inscription.user.pne', function ($q) {
                $q->where('status', 'accepted');
                    // ->where('pne_report_accepted', true);
            })
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

        return self::with('result.inscription.user.pne')
            ->where('call_list_id', $lastCallListId)
            ->whereHas('result.inscription.user.pne', function ($q) {
                $q->where('status', 'accepted');
            })
            ->get();
    }
}
