<?php

namespace App\Enums;

/**
 * Tipos de solicitação que o admin analisa (deferir / indeferir).
 *
 * Concentra tudo que muda entre os dois fluxos, para que controller,
 * rotas e views possam ser genéricos.
 */
enum AnalysisType: string
{
    case Pcd = 'pcd';
    case Lgbt = 'lgbt';

    /**
     * Nome da relação no model User ($user->pne / $user->lgbt).
     * Também é o tipo usado nos recursos (appeals) e a pasta dos e-mails.
     */
    public function relation(): string
    {
        return match ($this) {
            self::Pcd => 'pne',
            self::Lgbt => 'lgbt',
        };
    }

    /**
     * Coluna que guarda o arquivo enviado pelo candidato.
     */
    public function fileField(): string
    {
        return match ($this) {
            self::Pcd => 'report',
            self::Lgbt => 'authorization',
        };
    }

    /**
     * Rota da listagem para onde o admin volta depois da análise.
     */
    public function listRoute(): string
    {
        return match ($this) {
            self::Pcd => 'admin.analyses.pcds',
            self::Lgbt => 'admin.analyses.lgbts',
        };
    }

    /**
     * Como o tipo aparece em frases ("não é possível deferir {subject}").
     */
    public function subject(): string
    {
        return match ($this) {
            self::Pcd => 'o relatório/laudo',
            self::Lgbt => 'a autorização de nome social',
        };
    }

    /**
     * Título da página de análise.
     */
    public function pageTitle(): string
    {
        return match ($this) {
            self::Pcd => 'Análise de Recurso - Laudo/Relatório Médico',
            self::Lgbt => 'Análise de Recurso - Autorização de Nome Social',
        };
    }

    /**
     * Nome do documento analisado ("Deferir {label}").
     */
    public function label(): string
    {
        return match ($this) {
            self::Pcd => 'Relatório/Laudo',
            self::Lgbt => 'Autorização de Nome Social',
        };
    }

    /**
     * Rótulo da linha do arquivo na tela de análise.
     */
    public function fileLabel(): string
    {
        return match ($this) {
            self::Pcd => 'Laudo/Relatório',
            self::Lgbt => 'Autorização',
        };
    }

    /**
     * Texto do botão que abre o arquivo.
     */
    public function fileButton(): string
    {
        return match ($this) {
            self::Pcd => 'Abrir laudo',
            self::Lgbt => 'Abrir autorização',
        };
    }

    /**
     * Para a pergunta de confirmação ("Confirma o deferimento {ofSubject} deste candidato?").
     */
    public function ofSubject(): string
    {
        return match ($this) {
            self::Pcd => 'do relatório/laudo',
            self::Lgbt => 'da autorização de uso de nome social',
        };
    }

    /**
     * Assunto do e-mail. $status: 'accepted' ou 'rejected'.
     */
    public function emailSubject(string $status): string
    {
        return match ($this) {
            self::Pcd => $status === 'accepted'
                ? 'Vestibulinho LF - Deferimento de Relatório/Laudo Médico'
                : 'Vestibulinho LF - Indeferimento de Relatório/Laudo Médico',
            self::Lgbt => 'Vestibulinho LF - Autorização de Nome Social',
        };
    }

    /**
     * Mensagem de sucesso depois de deferir/indeferir. $status: 'accepted' ou 'rejected'.
     */
    public function successMessage(string $status): string
    {
        [$label, $accepted, $rejected] = match ($this) {
            self::Pcd => ['Relatório/laudo', 'deferido', 'indeferido'],
            self::Lgbt => ['Autorização de nome social', 'deferida', 'indeferida'],
        };

        $result = $status === 'accepted' ? $accepted : $rejected;

        return "{$label} {$result} com sucesso. O candidato será notificado por e-mail.";
    }

    /**
     * View do e-mail: emails.analyses.pne.accepted, emails.analyses.lgbt.rejected...
     */
    public function emailView(string $status): string
    {
        return "emails.analyses.{$this->relation()}.{$status}";
    }
}