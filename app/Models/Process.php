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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_process')
            ->withPivot('id', 'vacancies')
            ->withTimestamps();
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

    public function event(): HasOne
    {
        return $this->hasOne(Event::class);
    }

    public static function current(): ?self
    {
        return self::with('event')
            ->latest('id')
            ->first();
    }

    public function isInscriptionOpen(): bool
    {
        return $this->status === 'open'
            && ($this->event?->isInscriptionOpen() ?? false);
    }

    public function isInscriptionStarted(): bool
    {
        return $this->event?->isInscriptionStarted() ?? false;
    }

    /**
     * Verifica se o período de inscrição do processo seletivo terminou.
     *
     * @return bool
     */
    public function isInscriptionEnded(): bool
    {
        return $this->event?->isInscriptionEnded() ?? false;
    }

    /**
     * Retorna o ano do processo seletivo ativo ou do processo seletivo mais recente.
     *
     * @return int|null
     */
    public static function getYear(): ?int
    {
        $process = self::query()
            ->where('status', 'open')
            ->latest('year')
            ->first()
            ?? self::query()->latest('year')->first();

        return $process?->year;
    }

    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        $clearCache = function () {
            Cache::forget('global_process');
            Cache::forget('global_inscription_open');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
