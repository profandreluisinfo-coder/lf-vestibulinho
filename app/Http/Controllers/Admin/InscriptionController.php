<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InscriptionController extends Controller
{
    /**
     * Exibe a lista de candidatos com inscrição ativa.
     *
     * @return View
     */
    public function index(): View
    {
        // Agora apenas retorna a view, os dados virão via AJAX
        return view('admin.inscriptions.index');
    }

    /**
     * Retorna os dados paginados para o DataTables via AJAX
     *
     * Os dados serão exibidos na tabela de inscrições ativas no dashboard, na view "inscriptions.index".
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        $draw              = $request->input('draw');
        $start             = $request->input('start');
        $length            = $request->input('length');
        $searchValue       = $request->input('search.value');
        $orderColumnIndex  = $request->input('order.0.column', 0);
        $orderDir          = $request->input('order.0.dir', 'asc');

        // Mapeamento de colunas para ordenação
        $columns     = ['inscriptions.id', 'users.name', 'users.cpf', 'processes.year'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'inscriptions.id';

        // ─────────────────────────────────────────────────────────
        // 1) Query BASE (sem joins) — usada somente para contagem
        // ─────────────────────────────────────────────────────────
        $baseQuery = User::whereHas('inscription');

        // Aplica a busca na base (para o totalFiltered respeitar o filtro)
        if (!empty($searchValue)) {
            $baseQuery->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', "%{$searchValue}%")
                    ->orWhere('users.cpf', 'like', "%{$searchValue}%")
                    ->orWhereHas('inscription', function ($qi) use ($searchValue) {
                        $qi->where('inscriptions.id', 'like', "%{$searchValue}%");
                    })
                    ->orWhereHas('inscription.process', function ($qp) use ($searchValue) {
                        $qp->where('processes.year', 'like', "%{$searchValue}%");
                    });
            });
        }

        // Totais — como $baseQuery NÃO tem join, o COUNT(*) nunca duplica.
        // (clone garante que o count não "consuma" a query para o próximo uso)
        $totalRecords  = User::whereHas('inscription')->count();
        $totalFiltered = (clone $baseQuery)->count();

        // ─────────────────────────────────────────────────────────
        // 2) Query de DADOS — clona a base e adiciona joins + select
        // ─────────────────────────────────────────────────────────
        $query = (clone $baseQuery)
            ->with(['inscription.process'])
            ->join('inscriptions', 'users.id', '=', 'inscriptions.user_id')
            ->leftJoin('processes', 'inscriptions.process_id', '=', 'processes.id')
            ->select(
                'users.*',
                'inscriptions.id as inscription_id',
                'processes.year as process_year'
            );

        $users = $query->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // ─────────────────────────────────────────────────────────
        // 3) Formatação para o DataTables
        // ─────────────────────────────────────────────────────────
        $data = $users->map(function ($user) {
            $name = ($user?->lgbt && $user?->lgbt?->status == 'accepted')
                ? $user?->lgbt?->name . ' <i class="bi bi-gender-trans text-success" data-bs-toggle="popover" data-bs-trigger="hover"
                                data-bs-content="LGBTQIA+"></i>'
                : $user->name;

            return [
                'inscription_id' => $user->inscription?->id ?? $user->inscription_id,
                'name'           => $name,
                'cpf'            => $user->cpf,
                'process_year'   => $user->process_year,
                'user_id'        => $user->id,
                'actions'        => view('admin.inscriptions.inscription-actions', compact('user'))->render()
            ];
        });

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    /**
     * Candidatos com deficiência (PcD).
     */
    public function pcds(): View
    {
        return view('admin.inscriptions.pcds', [
            'users' => $this->candidatesWith('pne', 'pne'),
        ]);
    }

    /**
     * Candidatos com solicitação de nome social.
     */
    public function lgbts(): View
    {
        return view('admin.inscriptions.lgbts', [
            'users' => $this->candidatesWith('lgbt', 'lgbt'),
        ]);
    }

    /**
     * Usuários com inscrição e com a relação informada, já com tudo que a view usa.
     *
     * @param  string  $relation  Relação principal (pne ou lgbt)
     * @param  string  $appealType  Tipo de recurso correspondente
     */
    private function candidatesWith(string $relation, string $appealType): Collection
    {
        return User::whereHas($relation)
            ->whereHas('inscription')
            ->with([
                'inscription',
                $relation,
                'lgbt', // a view de PcD mostra o nome social quando deferido
                'appeals' => fn($query) => $query->where('type', $appealType),
            ])
            ->get();
    }

    public function resources(): View
    {
        $users = User::whereHas('inscription')->get();

        return view('admin.inscriptions.resources', [
            'users' => $users
        ]);
    }

    public function pdf(string $type, int $id)
    {
        $relation = $type === 'pcd' ? 'pne' : 'lgbt';

        $inscription = Inscription::with("user.$relation")
            ->where('user_id', $id)
            ->firstOrFail();

        $title = match ($type) {
            'pcd'  => 'Pessoa com Deficiência',
            'lgbt' => 'Candidato com Nome Social',
        };

        $pdf = Pdf::loadView('admin.inscriptions.pdf', compact('inscription', 'title', 'type'));

        return $pdf->stream("{$inscription->user->name}.pdf");
    }
}
