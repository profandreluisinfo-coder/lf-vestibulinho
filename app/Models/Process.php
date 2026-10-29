<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Carbon\CarbonPeriod;

class Process extends Model
{
    protected $fillable = [
        'year',
        'edital',
        'status',

        // Inscrições
        'start',
        'end',

        // Prova
        'location_publish',
        'exam_date',
        'exam_time', // * NOVO

        // Gabarito, revisão e resultado
        'answer_publish',
        'revision_start',
        'revision_end',
        'result_publish',

        // Matrícula
        'enrol_start',
        'enrol_remaining',

        // Divulgação
        'location_released_at',
        'result_released_at',
    ];

    protected function casts(): array
    {
        return [
            'start'                => 'date',
            'end'                  => 'date',
            'location_publish'     => 'date',
            'exam_date'            => 'date',
            'answer_publish'       => 'date',
            'revision_start'       => 'date',
            'revision_end'         => 'date',
            'result_publish'       => 'date',
            'enrol_start'          => 'date',
            'enrol_remaining'      => 'date',
            'created_at'           => 'datetime',
            'updated_at'           => 'datetime',
            'location_released_at' => 'datetime',
            'result_released_at'   => 'datetime',
        ];
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_process')
            ->withPivot('id', 'vacancies')
            ->withTimestamps();
    }

    // Histórico completo do usuário
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public static function current(): ?self
    {
        return self::latest('id')->first();
    }

    // Inscrição do processo atual
    public function inscription(): HasOne
    {
        return $this->hasOne(Inscription::class)
            ->where('process_id', Process::current()?->id);
    }

    /**
     * Converte string vazia em null ao definir atributos.
     */
    public function setAttribute($key, $value)
    {
        if ($value === "") {
            $value = null;
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Verifica se os locais de prova foram divulgados.
     */
    public function isLocationReleased(): bool
    {
        return $this->location_released_at !== null;
    }

    /**
     * Verifica se os resultados da prova foram divulgados.
     */
    public function isResultReleased(): bool
    {
        return $this->result_released_at !== null;
    }

    public function isInscriptionOpen(): bool
    {
        return $this->status === 'open' && $this->isInscriptionPeriod();
    }

    /**
     * Hoje está dentro do período de inscrições (inclusive o último dia)?
     */
    private function isInscriptionPeriod(): bool
    {
        if (!$this->start || !$this->end) {
            return false;
        }

        return today()->between($this->start, $this->end);
    }

    public function isInscriptionStarted(): bool
    {
        return $this->start && today()->gte($this->start);
    }

    public function isInscriptionEnded(): bool
    {
        return $this->end && today()->gt($this->end);
    }

    /**
     * Itens do calendário (antes era Event::event()).
     */
    public function calendar(): array
    {
        return [
            [
                'label' => 'Inscrições',
                'icon'  => '<i class="bi bi-person-lines-fill me-1"></i>',
                'type'  => 'period',
                'start' => $this->start,
                'end'   => $this->end,
            ],
            [
                'label' => 'Divulgação do local da prova',
                'icon'  => '<i class="bi bi-geo-alt me-1"></i>',
                'type'  => 'date',
                'date'  => $this->location_publish,
            ],
            [
                'label' => 'Data da prova',
                'icon'  => '<i class="bi bi-calendar2-week me-1"></i>',
                'type'  => 'date',
                'date'  => $this->exam_date,
            ],
            [
                'label' => 'Divulgação do gabarito',
                'icon'  => '<i class="bi bi-list-check me-1"></i>',
                'type'  => 'date',
                'date'  => $this->answer_publish,
            ],
            [
                'label' => 'Revisão de prova',
                'icon'  => '<i class="bi bi-search me-1"></i>',
                'type'  => 'period',
                'start' => $this->revision_start,
                'end'   => $this->revision_end,
            ],
            [
                'label' => 'Resultado final',
                'icon'  => '<i class="bi bi-list-ol me-1"></i>',
                'type'  => 'date',
                'date'  => $this->result_publish,
            ],
            [
                'label' => 'Divulgação do Chamamento (1ª Chamada)',
                'icon'  => '<i class="bi bi-pin-angle me-1"></i>',
                'type'  => 'date',
                'date'  => $this->enrol_start,
            ],
            [
                'label' => 'Divulgação do Chamamento (Vagas Remanescentes)',
                'icon'  => '<i class="bi bi-pin-angle me-1"></i>',
                'type'  => 'date',
                'date'  => $this->enrol_remaining,
            ],
        ];
    }

    /**
     * Inscrições por dia, do início do período até hoje (ou até o fim).
     * Dias sem inscrição aparecem com total 0.
     *
     * @return array<int, array{date: \Carbon\Carbon, total: int}>
     */
    public function dailyInscriptions(): array
    {
        if (!$this->start) {
            return [];
        }

        $start = $this->start->copy()->startOfDay();
        $last  = $this->end ? $this->end->copy() : today();
        $end   = $last->lt(today()) ? $last : today(); // nunca passa de hoje

        // Inscrições ainda não começaram
        if ($end->lt($start)) {
            return [];
        }

        $counts = Inscription::where('process_id', $this->id)
            ->whereBetween('created_at', [$start, $end->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $days = [];

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $days[] = [
                'date'  => $date,
                'total' => (int) ($counts[$date->toDateString()] ?? 0),
            ];
        }

        return $days;
    }

    public function formatDate($date): string
    {
        return $date ? $date->format('d/m/Y') : '—';
    }

    public function formatPeriod($start, $end): string
    {
        return $this->formatDate($start) . ' até ' . $this->formatDate($end);
    }

    public static function getYear(): ?int
    {
        $process = self::query()
            ->where('status', 'open')
            ->latest('year')
            ->first()
            ?? self::query()->latest('year')->first();

        return $process?->year;
    }

    public static function cached(): ?self
    {
        return Cache::remember('global_process', 300, fn() => self::current());
    }

    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        $clearCache = function () {
            Cache::forget('global_process');
            Cache::forget('global_inscription_open');
            Cache::forget('global_total_inscriptions');
            Cache::forget('global_users_without_inscription');
            Cache::forget('global_results');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
