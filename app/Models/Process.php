<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

class Process extends Model
{
    protected $fillable = [
        'year',
        'edital',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
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
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    // Recupera o evento mais recente
    public function latestEvent(): HasOne
    {
        return $this->hasOne(Event::class)->latestOfMany();
    }

    public static function current()
    {
        return self::with('latestEvent')
            ->latest('id')
            ->first();
    }

    public function isInscriptionOpen(): bool
    {
        return $this->latestEvent?->isInscriptionOpen() ?? false;
    }

    public function isInscriptionStarted(): bool
    {
        return $this->latestEvent?->isInscriptionStarted() ?? false;
    }

    public function isInscriptionEnded(): bool
    {
        return $this->latestEvent?->isInscriptionEnded() ?? false;
    }
    
    /**
     * Retorna o ano do processo seletivo ativo ou do processo seletivo mais recente.
     *
     * @return int|null
     */
    public static function getYear(): ?int
    {
        $process = self::getStatus() === 'open' ?? self::orderBy('year', 'desc')->first();

        return $process ? $process?->year : null;
    }

    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_process'));
        static::deleted(fn() => Cache::forget('global_process'));
    }
}
