<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class CallList extends Model
{
    protected $fillable = [
        'process_id',
        'number',
        'date',
        'time',
        'status',
        'published_at',
        'published_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'time' => 'datetime:H:i',
            'published_at' => 'datetime',
        ];
    }

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

    /**
     * Relação com todas as chamadas associadas a essa lista de chamada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    protected static function booted(): void
    {
        $forgetCallCache = function (CallList $callList): void {
            Cache::forget('calls_exists');
            Cache::forget('calls_finalized_count_' . ($callList->process_id ?? 'none'));
        };

        static::saved($forgetCallCache);
        static::deleted($forgetCallCache);
    }

}
