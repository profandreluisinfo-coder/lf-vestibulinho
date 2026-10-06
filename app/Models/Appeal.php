<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appeal extends Model
{
    // Tipos de recurso (qual pedido indeferido está sendo contestado)
    public const TYPE_PNE = 'pne';   // laudo/relatório PcD
    public const TYPE_LGBT = 'lgbt'; // nome social

    // Valores gravados na coluna `status` (mesmos de Pne e Lgbt)
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    // Nomes para mostrar nas telas
    public const TYPE_LABELS = [
        self::TYPE_PNE => 'Laudo/Relatório',
        self::TYPE_LGBT => 'Nome Social',
    ];

    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Em análise',
        self::STATUS_ACCEPTED => 'Deferido',
        self::STATUS_REJECTED => 'Indeferido',
    ];

    // Cor do selo (Bootstrap) de cada status
    public const STATUS_BADGES = [
        self::STATUS_PENDING => 'bg-warning text-dark',
        self::STATUS_ACCEPTED => 'bg-success',
        self::STATUS_REJECTED => 'bg-danger',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'protocol',
        'allegations',
        'status',
        'observations',
        'path',
        'decided_by',
        'decided_at',
    ];

    protected function casts(): array
    {
        return [
            'decided_at' => 'datetime',
        ];
    }

    /**
     * Defina o valor de um determinado atributo no modelo.
     *
     * Se o valor for uma string vazia, ele será convertido em nulo.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // Se o valor for string vazia, converte para null
        if ($value === '') {
            $value = null;
        }

        return parent::setAttribute($key, $value);
    }

    // ── Scopes ───────────────────────────────────────────────

    /** Recursos aguardando decisão. */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /** Recursos deferidos. */
    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    /** Recursos indeferidos. */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /** Recursos de um tipo (pne ou lgbt). */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    // ── Relacionamentos ──────────────────────────────────────

    /** Candidato dono do recurso. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Funcionário que decidiu o recurso. */
    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    // ── Ajudantes ────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /** Nome do tipo em português (Laudo/Relatório ou Nome Social). */
    public function typeLabel(): string
    {
        return self::TYPE_LABELS[$this->type] ?? (string) $this->type;
    }

    /** Nome do status em português (Em análise, Deferido, Indeferido). */
    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? (string) $this->status;
    }

    /** Classe do selo do status (cor). */
    public function badgeClass(): string
    {
        return self::STATUS_BADGES[$this->status] ?? 'bg-secondary';
    }
}