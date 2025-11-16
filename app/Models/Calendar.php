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

    // 🔹 Limpa o cache automaticamente quando salvar ou excluir
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
     * Verifica se a data de publicação do local de prova já foi atingida.
     *
     * @return bool Verdadeiro se a data de publicação do local de prova já foi atingida, falso caso contrário.
     */
    public function examLocationPublish(): bool
    {
        if (!$this->exam_location_publish) {
            return false;
        }

        return now()->gte($this->exam_location_publish);
    }

    /**
     * Retorna o status das inscrições.
     *
     * @return string 'not_started', 'open' ou 'closed'
     */
    public function getInscriptionStatus(): string
    {
        if (!$this->hasInscriptionStarted()) {
            return 'not_started';
        }

        if ($this->isInscriptionOpen()) {
            return 'open';
        }

        return 'closed';
    }

    /**
     * Retorna os dados de configuração do status de inscrição para a view.
     *
     * @return array
     */
    public function getInscriptionStatusData(): array
    {
        $status = $this->getInscriptionStatus();

        $config = [
            'not_started' => [
                'color' => 'warning',
                'icon' => 'bi-clock',
                'title' => 'Inscrições em Breve',
                'message' => 'As inscrições ainda não iniciaram.',
                'show_button' => false,
            ],
            'open' => [
                'color' => 'success',
                'icon' => 'bi-pencil-square',
                'title' => 'Inscrições Abertas',
                'message' => 'As inscrições estão abertas até <strong>' .
                    \Carbon\Carbon::parse($this->inscription_end)->format('d/m/Y') . '</strong>',
                'show_button' => true,
            ],
            'closed' => [
                'color' => 'danger',
                'icon' => 'bi-x-circle',
                'title' => 'Inscrições Encerradas',
                'message' => 'As inscrições foram encerradas em <strong>' .
                    \Carbon\Carbon::parse($this->inscription_end)->format('d/m/Y') . '</strong>. ' .
                    'Veja as próximas <a href="' . route('calendary') . '" class="fw-semibold text-decoration-none text-danger">etapas</a>.',
                'show_button' => false,
            ],
        ];

        return $config[$status];
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
     * Verifica se a data da prova já passou
     */
    public function hasExamDatePassed(): bool
    {
        if (!$this->exam_date) {
            return false;
        }

        return now()->gt($this->exam_date->endOfDay());
    }

    /**
     * Verifica se o resultado final já foi publicado
     */
    public function isFinalResultPublished(): bool
    {
        if (!$this->final_result_publish) {
            return false;
        }

        return now()->gte($this->final_result_publish);
    }

    /**
     * Verifica se o local de prova já foi publicado
     */
    public function isExamLocationPublished(): bool
    {
        if (!$this->exam_location_publish) {
            return false;
        }

        return now()->gte($this->exam_location_publish);
    }

    /**
     * Verifica se o gabarito já foi publicado
     */
    public function isAnswerKeyPublished(): bool
    {
        if (!$this->answer_key_publish) {
            return false;
        }

        return now()->gte($this->answer_key_publish);
    }

    /**
     * Verifica se está no período de revisão
     */
    public function isRevisionPeriodOpen(): bool
    {
        if (!$this->exam_revision_start || !$this->exam_revision_end) {
            return false;
        }

        return now()->between(
            $this->exam_revision_start->startOfDay(),
            $this->exam_revision_end->endOfDay()
        );
    }

    /**
     * Verifica se o período de revisão já encerrou
     */
    public function hasRevisionPeriodEnded(): bool
    {
        if (!$this->exam_revision_end) {
            return false;
        }

        return now()->gt($this->exam_revision_end->endOfDay());
    }

    /**
     * Verifica se está no período de matrícula
     */
    public function isEnrollmentPeriodOpen(): bool
    {
        if (!$this->enrollment_start || !$this->enrollment_end) {
            return false;
        }

        return now()->between(
            $this->enrollment_start->startOfDay(),
            $this->enrollment_end->endOfDay()
        );
    }

    /**
     * Verifica se o período de matrícula já encerrou
     */
    public function hasEnrollmentPeriodEnded(): bool
    {
        if (!$this->enrollment_end) {
            return false;
        }

        return now()->gt($this->enrollment_end->endOfDay());
    }
}
