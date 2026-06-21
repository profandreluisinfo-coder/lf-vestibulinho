<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Event extends Model
{
    protected $fillable = [
        'selection_process_id',
        'start',
        'end',
        'location_publish',
        'exam_date',
        'answer_publish',
        'revision_start',
        'revision_end',
        'result_publish',
        'enrol_start',
        'enrol_remaining',
    ];

    protected $casts = [
        // Inscrições
        'start' => 'datetime',
        'end' => 'datetime',

        // Prova
        'location_publish' => 'datetime',
        'exam_date' => 'datetime',

        // Gabarito e Revisão
        'answer_publish' => 'datetime',
        'revision_start' => 'datetime',
        'revision_end' => 'datetime',

        // Resultado e Matrícula
        'result_publish' => 'datetime',
        'enrol_start' => 'datetime',
        'enrol_remaining' => 'datetime'
    ];

    public function selectionProcess(): BelongsTo
    {
        return $this->belongsTo(SelectionProcess::class);
    }

    /**
     * Retorna um array com os eventos do calendário.
     *
     * @return array Contendo os eventos do calendário com as seguintes informações:
     *   - label: Nome do evento
     *   - icon: Ícon do evento
     *   - type: Tipo do evento (date ou period)
     *   - date: Data do evento (caso tipo date)
     *   - start: Data de início do evento (caso tipo period)
     *   - end: Data de término do evento (caso tipo period)
     */
    public function events(): array
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
                'date' => $this->enrol_start
            ],
            [
                'label' => 'Divulgação do Chamamento (Vagas Remanescentes)',
                'icon'  => '<i class="bi bi-pin-angle me-1"></i>',
                'type'  => 'date',
                'date' => $this->enrol_remaining
            ],
        ];
    }

    /**
     * Verifica se as inscrições estão abertas.
     *
     * @return bool Verdadeiro se as inscrições estão abertas, falso caso contrário.
     */
    public function isInscriptionOpen(): bool
    {
        // Verifica se as datas de início e fim das inscrições foram definidas
        if (!$this->start || !$this->end) {
            return false;
        }
        // Verifica se a data atual está entre a data de início e fim das inscrições
        return now()->between(
            $this->start->startOfDay(),
            $this->end->endOfDay()
        );
    }

    /**
     * Verifica se a data de início das inscrições já foi atingida.
     *
     * @return bool Verdadeiro se a data de início das inscrições já foi atingida, falso caso contrário.
     */
    public function isInscriptionStarted(): bool
    {
        if (!$this->start) {
            return false;
        }

        return now()->gte($this->start->startOfDay());
    }

    /**
     * Verifica se as inscrições já encerraram.
     *
     * @return bool Verdadeiro se as inscrições já encerraram, falso caso contrário.
     */
    public function isInscriptionEnded(): bool
    {
        if (!$this->end) {
            return false;
        }

        return now()->gt($this->end->endOfDay());
    }

    /**
     * Retorna o calendário ativo, ou null caso não tenha nenhum calendário ativo.
     *
     * Esta função utiliza o cache para evitar consultas repetidas ao banco de dados.
     * O cache é atualizado automaticamente quando o calendário for salvo ou excluído.
     *
     * @return self|null
     */
    public static function getActive(): ?self
    {
        return Cache::rememberForever('global_calendar', function () {
            return self::where('is_active', true)->first();
        });
    }

    /**
     * Formata uma data no formato 'd/m/Y' ou retorna '—' se a data for nula.
     *
     * @param \Carbon\Carbon|null $attribute Data a ser formatada.
     *
     * @return string Data formatada ou '—' se a data for nula.
     */
    public function formatDate($date): string
    {
        return $date ? $date->format('d/m/Y') : '—';
    }
    // public function formatDate($attribute): string
    // {
    //     return $this->$attribute
    //         ? $this->$attribute->format('d/m/Y')
    //         : '—';
    // }

    /**
     * Formata um período de tempo com as datas de início e término no formato 'd/m/Y' e retorna como string.
     *
     * @param \Carbon\Carbon|null $start Data de início do período.
     * @param \Carbon\Carbon|null $end Data de término do período.
     *
     * @return string Período formatado como string.
     */
    public function formatPeriod($start, $end): string
    {
        return $this->formatDate($start) . ' até ' . $this->formatDate($end);
    }

    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_calendar'));
        static::deleted(fn() => Cache::forget('global_calendar'));
    }
}
