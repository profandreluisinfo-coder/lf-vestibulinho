<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = [
        'inscription_start',
        'inscription_end',
        'exam_location_publish',
        'exam_date',
        'answer_key_publish',
        'exam_revision_start',
        'exam_revision_end',
        'final_result_publish',
        'enrollment_start',
        'enrollment_end',
        'name',
        'year',
        'is_active'
    ];

    protected $casts = [
        // Inscrições
        'inscription_start' => 'datetime',
        'inscription_end' => 'datetime',

        // Prova
        'exam_location_publish' => 'datetime',
        'exam_date' => 'datetime',

        // Gabarito e Revisão
        'answer_key_publish' => 'datetime',
        'exam_revision_start' => 'datetime',
        'exam_revision_end' => 'datetime',

        // Resultado e Matrícula
        'final_result_publish' => 'datetime',
        'enrollment_start' => 'datetime',
        'enrollment_end' => 'datetime',

        // Flags
        'is_active' => 'boolean'
    ];

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
                'start' => $this->inscription_start,
                'end'   => $this->inscription_end,
            ],
            [
                'label' => 'Divulgação do local da prova',
                'icon'  => '<i class="bi bi-geo-alt me-1"></i>',
                'type'  => 'date',
                'date'  => $this->exam_location_publish,
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
                'date'  => $this->answer_key_publish,
            ],
            [
                'label' => 'Revisão de prova',
                'icon'  => '<i class="bi bi-search me-1"></i>',
                'type'  => 'period',
                'start' => $this->exam_revision_start,
                'end'   => $this->exam_revision_end,
            ],
            [
                'label' => 'Resultado final',
                'icon'  => '<i class="bi bi-list-ol me-1"></i>',
                'type'  => 'date',
                'date'  => $this->final_result_publish,
            ],
            [
                'label' => 'Divulgação do Chamamento (1ª Chamada)',
                'icon'  => '<i class="bi bi-pin-angle me-1"></i>',
                'type'  => 'date',
                'date' => $this->enrollment_start
            ],
            [
                'label' => 'Divulgação do Chamamento (Vagas Remanescentes)',
                'icon'  => '<i class="bi bi-pin-angle me-1"></i>',
                'type'  => 'date',
                'date' => $this->enrollment_end
            ],
        ];
    }

    /**
     * Formata uma data no formato 'd/m/Y' ou retorna '—' se a data for nula.
     *
     * @param \Carbon\Carbon|null $date Data a ser formatada.
     *
     * @return string Data formatada ou '—' se a data for nula.
     */
    public function formatDate($date): string
    {
        return $date ? $date->format('d/m/Y') : '—';
    }

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

    /**
     * Verifica se as inscrições estão abertas.
     *
     * @return bool Verdadeiro se as inscrições estão abertas, falso caso contrário.
     */
    public function isInscriptionOpen(): bool
    {
        if (!$this->inscription_start || !$this->inscription_end) {
            return false;
        }

        return now()->between(
            $this->inscription_start->startOfDay(),
            $this->inscription_end->endOfDay()
        );
    }

    /**
     * Verifica se a data de início das inscrições já foi atingida.
     *
     * @return bool Verdadeiro se a data de início das inscrições já foi atingida, falso caso contrário.
     */
    public function hasInscriptionStarted(): bool
    {
        if (!$this->inscription_start) {
            return false;
        }

        return now()->gte($this->inscription_start->startOfDay());
    }

    /**
     * Verifica se as inscrições já encerraram.
     *
     * @return bool Verdadeiro se as inscrições já encerraram, falso caso contrário.
     */
    public function hasInscriptionEnded(): bool
    {
        if (!$this->inscription_end) {
            return false;
        }

        return now()->gt($this->inscription_end->endOfDay());
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
     * Retorna o ano do calendário ativo ou do calendário mais recente.
     *
     * @return int|null
     */
    public static function getYear(): ?int
    {
        $calendar = self::getActive() ?? self::orderBy('year', 'desc')->first();

        return $calendar ? $calendar->year : null;
    }
}
