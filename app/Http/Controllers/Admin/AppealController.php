<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTransactionalEmailJob;
use App\Models\Appeal;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AppealController extends Controller
{
    // Tipos aceitos (também são os nomes dos relacionamentos no User)
    private const TYPES = [Appeal::TYPE_PNE, Appeal::TYPE_LGBT];

    // Observação gravada no pedido original quando o recurso é deferido
    private const ACCEPTED_NOTES = [
        Appeal::TYPE_PNE => 'Relatório/laudo deferido após recurso',
        Appeal::TYPE_LGBT => 'Autorização deferida após recurso',
    ];

    /**
     * Lista os recursos (pendentes primeiro). Filtros opcionais: type e status.
     */
    public function index(Request $request): View
    {
        $appeals = Appeal::with(['user.lgbt', 'user.inscription', 'decider'])
            ->when($request->filled('type'), fn($q) => $q->ofType($request->input('type')))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->input('status')))
            ->orderByRaw("status = 'pending' desc")
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.appeals.index', [
            'appeals' => $appeals,
        ]);
    }

    /**
     * Exibe o formulário para registrar o recurso (número do protocolo).
     */
    public function create(User $user, string $type): View|RedirectResponse
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        if ($error = $this->cannotRegister($user, $type)) {
            return redirect()
                ->route($this->listRoute($type))
                ->with('error', $error);
        }

        $protocols = Appeal::latest('id')
            ->limit(4)
            ->pluck('protocol');

        return view('admin.appeals.create', [
            'user' => $user,
            'type' => $type,
            'original' => $user->{$type},
            'protocols' => $protocols,
        ]);
    }

    /**
     * Registra o recurso entregue na secretaria.
     */
    public function store(Request $request, User $user, string $type): RedirectResponse
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        if ($error = $this->cannotRegister($user, $type)) {
            return redirect()
                ->route($this->listRoute($type))
                ->with('error', $error);
        }

        $data = $request->validate([
            'protocol' => 'required|string|max:30',
        ]);

        try {
            Appeal::create([
                'user_id' => $user->id,
                'type' => $type,
                'protocol' => $data['protocol'],
            ]);
        } catch (UniqueConstraintViolationException $e) {
            // Duplo clique: o recurso já foi gravado um instante antes
            return redirect()
                ->route($this->listRoute($type))
                ->with('error', 'Este candidato já possui recurso registrado para este pedido.');
        }

        return redirect()
            ->route('admin.appeals.index')
            ->with('success', 'Recurso registrado com sucesso!');
    }

    /**
     * Exibe a tela de decisão (deferir ou indeferir).
     */
    public function show(Appeal $appeal): View
    {
        $appeal->load(['user.pne', 'user.lgbt', 'decider']);

        return view('admin.appeals.show', [
            'appeal' => $appeal,
            'original' => $appeal->user->{$appeal->type},
        ]);
    }

    /**
     * Defere o recurso: o pedido original também passa a "accepted".
     */
    public function accept(Request $request, Appeal $appeal): RedirectResponse
    {
        $data = $request->validate([
            'observations' => 'nullable|string|max:2000',
        ]);

        return $this->decide($appeal, Appeal::STATUS_ACCEPTED, $data['observations'] ?? null);
    }

    /**
     * Indefere o recurso: o pedido original não muda.
     */
    public function reject(Request $request, Appeal $appeal): RedirectResponse
    {
        $data = $request->validate(
            ['observations' => 'required|string|max:2000'],
            ['observations.required' => 'Informe o motivo do indeferimento do recurso.']
        );

        return $this->decide($appeal, Appeal::STATUS_REJECTED, $data['observations']);
    }

    /**
     * Exclui um recurso que ainda está em análise (por exemplo, registrado por engano).
     * Recurso já decidido não pode ser excluído.
     */
    public function destroy(Appeal $appeal): RedirectResponse
    {
        // Apaga só se ainda estiver pendente (confere e apaga no mesmo comando)
        $deleted = Appeal::whereKey($appeal->id)
            ->where('status', Appeal::STATUS_PENDING)
            ->delete();

        if (! $deleted) {
            return redirect()
                ->route('admin.appeals.show', $appeal)
                ->with('error', 'Só é possível excluir um recurso que ainda está em análise.');
        }

        return redirect()
            ->route('admin.appeals.index')
            ->with('success', 'Recurso excluído com sucesso.');
    }

    /**
     * Grava a decisão. Tudo é feito em uma transação:
     * ou grava o recurso E o pedido original, ou não grava nada.
     */
    private function decide(Appeal $appeal, string $status, ?string $observations): RedirectResponse
    {
        $error = DB::transaction(function () use ($appeal, $status, $observations) {
            // Trava o recurso e confere de novo (evita decidir duas vezes)
            $appeal = Appeal::lockForUpdate()->findOrFail($appeal->id);

            if (! $appeal->isPending()) {
                return 'Este recurso já foi decidido.';
            }

            // Deferir muda o pedido original e quebraria a alocação já feita.
            // (Indeferir não muda nada, por isso continua permitido.)
            if ($status === Appeal::STATUS_ACCEPTED) {
                $appeal->user->loadMissing('inscription.exam_result');

                if ($appeal->user->inscription?->exam_result) {
                    return 'Não é possível deferir o recurso, pois o candidato já está alocado em uma prova.';
                }
            }

            $appeal->update([
                'status' => $status,
                'observations' => $observations,
                'decided_by' => auth()->id(),
                'decided_at' => now(),
            ]);

            // Recurso deferido: atualiza também o pedido original (pnes ou lgbts)
            if ($status === Appeal::STATUS_ACCEPTED) {
                $appeal->user->{$appeal->type}()->firstOrFail()->update([
                    'status' => Appeal::STATUS_ACCEPTED,
                    'observations' => self::ACCEPTED_NOTES[$appeal->type],
                ]);
            }

            return null; // sem erro
        });

        if ($error) {
            return redirect()
                ->route('admin.appeals.index')
                ->with('error', $error);
        }

        $this->notifyCandidate($appeal, $status, $observations);

        return redirect()
            ->route('admin.appeals.index')
            ->with('success', $status === Appeal::STATUS_ACCEPTED
                ? 'Recurso deferido com sucesso!'
                : 'Recurso indeferido com sucesso.');
    }

    /**
     * Avisa o candidato por e-mail sobre a decisão do recurso.
     */
    private function notifyCandidate(Appeal $appeal, string $status, ?string $observations): void
    {
        // Busca o candidato de novo, para já vir com o nome social atualizado
        $user = User::with('lgbt')->find($appeal->user_id);

        $accepted = $status === Appeal::STATUS_ACCEPTED;

        $this->sendEmail(
            to: $user->email,
            subject: 'Vestibulinho LF - ' . ($accepted ? 'Deferimento' : 'Indeferimento') . ' de Recurso',
            data: [
                'name' => ($user->lgbt?->status === 'accepted') ? $user->lgbt->name : $user->name,
                'appealType' => $appeal->type,
                'observations' => $observations,
            ],
            view: $accepted ? 'emails.appeal.accepted' : 'emails.appeal.rejected',
        );
    }

    private function sendEmail(
        string $to,
        string $subject,
        array $data,
        string $view,
        ?string $attachment = null
    ) {
        dispatch(
            new SendTransactionalEmailJob(
                $to,
                $subject,
                $data,
                $view,
                $attachment
            )
        )->delay(now()->addSeconds(10));
    }

    /**
     * Confere se dá para registrar o recurso. Retorna a mensagem de erro, ou null se estiver tudo certo.
     */
    private function cannotRegister(User $user, string $type): ?string
    {
        $user->load([$type, 'appeals', 'inscription.exam_result']);

        $original = $user->{$type};

        if (! $original) {
            return 'Pedido do candidato não encontrado.';
        }

        if ($original->status !== Appeal::STATUS_REJECTED) {
            return 'Só é possível registrar recurso de um pedido indeferido.';
        }

        if ($user->appealOf($type)) {
            return 'Este candidato já possui recurso registrado para este pedido.';
        }

        // O recurso vale só antes da alocação nas salas de prova
        if ($user->inscription?->exam_result) {
            return 'Não é possível registrar o recurso, pois este candidato já está alocado em uma prova.';
        }

        return null;
    }

    /**
     * Rota da lista de onde o funcionário partiu (para voltar em caso de erro).
     */
    private function listRoute(string $type): string
    {
        return $type === Appeal::TYPE_PNE
            ? 'admin.inscriptions.pcds'
            : 'admin.inscriptions.lgbts';
    }
}
