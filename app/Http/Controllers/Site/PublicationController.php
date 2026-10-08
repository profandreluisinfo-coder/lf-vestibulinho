<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Inscription;
use App\Models\Process;
use App\Models\Setting;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(): View
    {
        return view('site.publications.index');
    }

    // ── Listas de inscrições (identificadas pelo número de inscrição) ──────

    /**
     * Inscrições deferidas ou indeferidas.
     * O $status vem do ->defaults('status', ...) de cada rota ('approved' ou 'rejected').
     */
    public function inscriptions(string $status): View
    {
        return $this->renderList($status, 'inscriptions', [
            'subject' => 'Inscrições',
            'labels' => ['approved' => 'deferidas', 'rejected' => 'indeferidas'],
            'leads' => [
                'approved' => 'Candidatos com inscrição aceita',
                'rejected' => 'Inscrições que não foram aceitas',
            ],
            'identifiedBy' => 'Os candidatos são identificados pelo número de inscrição.',
            'unit' => ['inscrição', 'inscrições'],
        ], fn(string $status, ?int $processId) => ($status === 'approved'
            ? Inscription::approved()
            : Inscription::rejected())
            ->forProcess($processId)
            ->orderBy('id')
            ->pluck('id'));
    }

    /** Pedidos de uso de nome social deferidos ou indeferidos (só o número de inscrição). */
    public function socialNames(string $status): View
    {
        return $this->renderList($status, 'social-names', [
            'subject' => 'Pedidos de nome social',
            'labels' => ['approved' => 'deferidos', 'rejected' => 'indeferidos'],
            'leads' => [
                'approved' => 'Pedidos de uso de nome social aceitos',
                'rejected' => 'Pedidos de uso de nome social não aceitos',
            ],
            'identifiedBy' => 'Os candidatos são identificados pelo número de inscrição.',
            'unit' => ['inscrição', 'inscrições'],
        ], fn(string $status, ?int $processId) => Inscription::query()
            ->forProcess($processId)
            ->whereHas('user.lgbt', fn($q) => $status === 'approved' ? $q->accepted() : $q->rejected())
            ->orderBy('id')
            ->pluck('id'));
    }

    /** Laudos e relatórios médicos deferidos ou indeferidos (só o número de inscrição). */
    public function medicalReports(string $status): View
    {
        return $this->renderList($status, 'medical-reports', [
            'subject' => 'Laudos e relatórios médicos',
            'labels' => ['approved' => 'deferidos', 'rejected' => 'indeferidos'],
            'leads' => [
                'approved' => 'Laudos e relatórios médicos aceitos',
                'rejected' => 'Laudos e relatórios médicos não aceitos',
            ],
            'identifiedBy' => 'Os candidatos são identificados pelo número de inscrição.',
            'unit' => ['inscrição', 'inscrições'],
        ], fn(string $status, ?int $processId) => Inscription::query()
            ->forProcess($processId)
            ->whereHas('user.pne', fn($q) => $status === 'approved' ? $q->accepted() : $q->rejected())
            ->orderBy('id')
            ->pluck('id'));
    }

    // ── Listas de recursos (identificadas pelo protocolo do recurso) ───────

    /** Recursos de nome social deferidos ou indeferidos. */
    public function appealsSocialNames(string $status): View
    {
        return $this->renderList($status, 'appeals.social-names', [
            'subject' => 'Recursos de nome social',
            'labels' => ['approved' => 'deferidos', 'rejected' => 'indeferidos'],
            'leads' => [
                'approved' => 'Recursos de nome social aceitos',
                'rejected' => 'Recursos de nome social não aceitos',
            ],
            // 'identifiedBy' => 'Os recursos são identificados pelo número de protocolo.',
            'identifiedBy' => 'Os recursos são identificados pelo número de protocolo e pelo número de inscrição.',
            'unit' => ['recurso', 'recursos'],
        ], fn(string $status, ?int $processId) => $this->appealProtocols(Appeal::TYPE_LGBT, $status, $processId));
    }

    /** Recursos de laudos e relatórios médicos deferidos ou indeferidos. */
    public function appealsMedicalReports(string $status): View
    {
        return $this->renderList($status, 'appeals.medical-reports', [
            'subject' => 'Recursos de laudos e relatórios médicos',
            'labels' => ['approved' => 'deferidos', 'rejected' => 'indeferidos'],
            'leads' => [
                'approved' => 'Recursos de laudos e relatórios médicos aceitos',
                'rejected' => 'Recursos de laudos e relatórios médicos não aceitos',
            ],
            // 'identifiedBy' => 'Os recursos são identificados pelo número de protocolo.',
            'identifiedBy' => 'Os recursos são identificados pelo número de protocolo e pelo número de inscrição.',
            'unit' => ['recurso', 'recursos'],
        ], fn(string $status, ?int $processId) => $this->appealProtocols(Appeal::TYPE_PNE, $status, $processId));
    }

    // ── Apoio ──────────────────────────────────────────────────────────────

    /**
     * Protocolos dos recursos de um tipo e status, do processo atual.
     * Busca só a coluna `protocol`: alegações, motivos e arquivos nunca são carregados.
     */
    private function appealProtocols(string $type, string $status, ?int $processId): Collection
    {
        return Appeal::query()
            ->ofType($type)
            ->when($status === 'approved', fn($q) => $q->accepted(), fn($q) => $q->rejected())
            ->whereHas('user.inscription', fn($q) => $q->forProcess($processId))
            ->with(['user.inscription' => fn($q) => $q->forProcess($processId)])
            ->select('id', 'user_id', 'protocol')
            ->orderBy('protocol')
            ->get()
            ->map(fn($appeal) => [
                'protocol' => $appeal->protocol,
                'inscription' => $appeal->user->inscription->id,
            ]);
    }
    // private function appealProtocols(string $type, string $status, ?int $processId): Collection
    // {
    //     return Appeal::query()
    //         ->ofType($type)
    //         ->when($status === 'approved', fn ($q) => $q->accepted(), fn ($q) => $q->rejected())
    //         // O recurso não guarda o processo: vem da inscrição do candidato.
    //         ->whereHas('user.inscription', fn ($q) => $q->forProcess($processId))
    //         ->orderBy('protocol')
    //         ->pluck('protocol');
    // }

    /**
     * Monta a página de uma lista de identificadores públicos.
     *
     * @param  string   $key      chave da lista no Setting (ex.: 'social-names')
     * @param  array    $texts    subject, labels, leads, identifiedBy e unit
     * @param  Closure  $numbers  recebe (status, processId) e devolve a coleção de identificadores
     */
    private function renderList(string $status, string $key, array $texts, Closure $numbers): View
    {
        abort_unless(in_array($status, ['approved', 'rejected'], true), 404);

        // A lista só é consultada se o administrador a liberou (Admin → Publicações).
        $released = Setting::isPublished("{$key}.{$status}");
        $items = $released ? $numbers($status, Process::current()?->id) : collect();

        return view('site.publications.list', [
            'numbers' => $items,
            'approved' => $status === 'approved',
            'released' => $released,
            'subject' => $texts['subject'],
            'statusLabel' => $texts['labels'][$status],
            'lead' => $texts['leads'][$status],
            'identifiedBy' => $texts['identifiedBy'],
            'unitOne' => $texts['unit'][0],
            'unitMany' => $texts['unit'][1],
        ]);
    }
}
