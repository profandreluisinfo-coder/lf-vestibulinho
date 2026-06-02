<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Communicate extends Model
{
    // ── Campos preenchíveis ───────────────────────────────────
    protected $fillable = [
        'titulo',
        'resumo',
        'tipo',
        'url',
        'status',
        'published_at',
        'user_id',
    ];

    // ── Cast de tipos ─────────────────────────────────────────
    protected $casts = [
        'published_at' => 'datetime',
    ];

    // ── Constantes de status ──────────────────────────────────
    const STATUS_RASCUNHO  = 'rascunho';
    const STATUS_PUBLICADO = 'publicado';

    // ── Relacionamento: autor ─────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ───────────────────────────────────────────────

    /** Apenas comunicados publicados */
    public function scopePublicado(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLICADO);
    }

    /** Apenas rascunhos */
    public function scopeRascunho(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_RASCUNHO);
    }

    /** Filtra por tipo (ex: 'info', 'alerta', 'urgente') */
    public function scopeDeTipo(Builder $query, string $tipo): Builder
    {
        return $query->where('tipo', $tipo);
    }

    // ── Helpers ───────────────────────────────────────────────

    /** Verifica se está publicado */
    public function estaPublicado(): bool
    {
        return $this->status === self::STATUS_PUBLICADO;
    }

    /** Publica o comunicado (salva automaticamente) */
    public function publicar(): void
    {
        $this->update([
            'status'       => self::STATUS_PUBLICADO,
            'published_at' => now(),
        ]);
    }

    /** Volta para rascunho (salva automaticamente) */
    public function despublicar(): void
    {
        $this->update([
            'status'       => self::STATUS_RASCUNHO,
            'published_at' => null,
        ]);
    }
}
