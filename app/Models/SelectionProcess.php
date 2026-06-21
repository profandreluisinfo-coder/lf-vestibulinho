<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SelectionProcess extends Model
{
    protected $table = 'selection_processes';

    protected $fillable = [
        'year',
        'edital',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'status' => 'boolean'
        ];
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

    /**
     * Retorna o ano do processo seletivo ativo ou do processo seletivo mais recente.
     *
     * @return int|null
     */
    public static function getYear(): ?int
    {
        $selection_process = self::getActive() ?? self::orderBy('year', 'desc')->first();

        return $selection_process ? $selection_process->year : null;
    }
}
