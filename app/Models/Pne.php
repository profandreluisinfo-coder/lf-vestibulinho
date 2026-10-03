<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pne extends Model
{
    // Valores gravados na coluna `status` (definidos em Admin\DeferralController)
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'description',
        'support',
        'report', // path do arquivo
        'status',
        'observations'
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

    /** Laudos/relatórios médicos deferidos. */
    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    /** Laudos/relatórios médicos indeferidos. */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}