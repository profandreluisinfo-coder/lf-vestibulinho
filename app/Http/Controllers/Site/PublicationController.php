<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\Process;
use App\Models\Setting;
use Closure;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(): View
    {
        return view('site.publications.index');
    }

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
        ], fn (string $status) => $status === 'approved'
            ? Inscription::approved()
            : Inscription::rejected());
    }

    /**
     * Pedidos de uso de nome social deferidos ou indeferidos.
     * Mostra só o número de inscrição de quem fez o pedido.
     */
    public function socialNames(string $status): View
    {
        return $this->renderList($status, 'social-names', [
            'subject' => 'Pedidos de nome social',
            'labels' => ['approved' => 'deferidos', 'rejected' => 'indeferidos'],
            'leads' => [
                'approved' => 'Pedidos de uso de nome social aceitos',
                'rejected' => 'Pedidos de uso de nome social não aceitos',
            ],
        ], fn (string $status) => Inscription::query()->whereHas(
            'user.lgbt',
            fn ($q) => $status === 'approved' ? $q->accepted() : $q->rejected()
        ));
    }

    /**
     * Laudos e relatórios médicos deferidos ou indeferidos.
     * Mostra só o número de inscrição; nunca a descrição, o arquivo ou os motivos.
     */
    public function medicalReports(string $status): View
    {
        return $this->renderList($status, 'medical-reports', [
            'subject' => 'Laudos e relatórios médicos',
            'labels' => ['approved' => 'deferidos', 'rejected' => 'indeferidos'],
            'leads' => [
                'approved' => 'Laudos e relatórios médicos aceitos',
                'rejected' => 'Laudos e relatórios médicos não aceitos',
            ],
        ], fn (string $status) => Inscription::query()->whereHas(
            'user.pne',
            fn ($q) => $status === 'approved' ? $q->accepted() : $q->rejected()
        ));
    }

    /**
     * Monta a página de uma lista de números de inscrição.
     *
     * @param  string   $key    prefixo da lista no Setting (ex.: 'social-names')
     * @param  array    $texts  subject, labels e leads, por status
     * @param  Closure  $query  recebe o status e devolve a query de Inscription
     */
    private function renderList(string $status, string $key, array $texts, Closure $query): View
    {
        abort_unless(in_array($status, ['approved', 'rejected'], true), 404);

        // A lista só é consultada se o administrador a liberou (Admin → Publicações).
        $released = Setting::isPublished("{$key}.{$status}");
        $inscriptions = collect();

        if ($released) {
            $inscriptions = $query($status)
                ->forProcess(Process::current()?->id)
                ->orderBy('id')
                // Só o número que a página pública mostra. Nada de user, lgbt, pne etc.
                ->get(['id']);
        }

        return view('site.publications.list', [
            'inscriptions' => $inscriptions,
            'approved' => $status === 'approved',
            'released' => $released,
            'subject' => $texts['subject'],
            'statusLabel' => $texts['labels'][$status],
            'lead' => $texts['leads'][$status],
        ]);
    }
}