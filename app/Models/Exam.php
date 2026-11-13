<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'inscription_id',
        'location_id',
        'room_number',
        'email_sent_at',
        'is_finalized',
    ];

    protected function casts(): array
    {
        return [
            'is_finalized' => 'boolean',
            'email_sent_at' => 'datetime',
        ];
    }

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

     /**
     * True se já existe alguma alocação de candidatos nas salas
     * para o processo seletivo informado (ou o atual, se nenhum for informado).
     */
    public static function allocationExists(?Process $process = null): bool
    {
        $process ??= Process::current();

        if (! $process) {
            return false;
        }

        return static::whereHas('inscription', fn ($q) => $q->forProcess($process?->id))
            ->exists();
    }
}
