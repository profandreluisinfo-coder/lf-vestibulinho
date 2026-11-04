<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AnalysisType;
use App\Http\Controllers\Controller;
use App\Jobs\SendTransactionalEmailJob;
use App\Models\Inscription;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Collection;

class AnalyseController extends Controller
{
    public function pcds(): View
    {
        return $this->showList(AnalysisType::Pcd);
    }

    public function lgbts(): View
    {
        return $this->showList(AnalysisType::Lgbt);
    }

    private function showList(AnalysisType $type): View
    {
        return view('admin.analyses.index', [
            'type' => $type,
            'users' => $this->candidatesWith($type),
        ]);
    }

    /**
     * Usuários com inscrição e com a relação informada, já com tudo que a view usa.
     *
     * @param  AnalysisType  $type  Tipo de análise (PcD / Nome Social)
     * @return Collection  Usuários com inscrição e com a relação informada, já com tudo que a view usa.
     */
    private function candidatesWith(AnalysisType $type): Collection
    {
        $relation = $type->relation();

        return User::whereHas($relation)
            ->whereHas('inscription')
            ->with([
                'inscription',
                $relation,
                'lgbt', // a view de PcD mostra o nome social quando deferido
                'appeals' => fn($query) => $query->where('type', $relation),
            ])
            ->get();
    }

    public function showAccept(AnalysisType $type, User $user): View|RedirectResponse
    {
        return $this->showForm($type, $user, 'accept');
    }

    public function showReject(AnalysisType $type, User $user): View|RedirectResponse
    {
        return $this->showForm($type, $user, 'reject');
    }

    public function accept(AnalysisType $type, User $user): RedirectResponse
    {
        return $this->analyse($type, $user, 'accepted', 'Autorização deferida pelo administrador');
    }

    public function reject(Request $request, AnalysisType $type, User $user): RedirectResponse
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        return $this->analyse($type, $user, 'rejected', $request->input('reason'));
    }

    private function showForm(AnalysisType $type, User $user, string $action): View|RedirectResponse
    {
        $verb = $action === 'accept' ? 'deferir' : 'indeferir';

        if ($blocked = $this->allocationLock($type->listRoute(), $verb, $type->subject())) {
            return $blocked;
        }

        if (! $this->loadAnalysis($user, $type)) {
            return $this->detailsNotFound($type);
        }

        return view('admin.analyses.form', [
            'user' => $user,
            'type' => $type,
            'action' => $action,
        ]);
    }

    /**
     * Deferir ou indeferir. $status: 'accepted' ou 'rejected'.
     */
    private function analyse(
        AnalysisType $type,
        User $user,
        string $status,
        ?string $observations
    ): RedirectResponse {
        $verb = $status === 'accepted' ? 'deferir' : 'indeferir';

        if ($blocked = $this->allocationLock($type->listRoute(), $verb, $type->subject())) {
            return $blocked;
        }

        $analysis = $this->loadAnalysis($user, $type);

        if (! $analysis) {
            return $this->detailsNotFound($type);
        }

        try {
            $analysis->update([
                'status' => $status,
                'observations' => $observations,
            ]);

            $this->sendEmail(
                to: $user->email,
                subject: $type->emailSubject($status),
                data: $this->emailData($user, $type, $status, $observations),
                view: $type->emailView($status),
            );

            return redirect()
                ->route($type->listRoute())
                ->with('success', $type->successMessage($status));
        } catch (\Exception $e) {
            report($e);

            return redirect()
                ->route($type->listRoute())
                ->with('error', "Erro ao {$verb} {$type->subject()}. Tente novamente.");
        }
    }

    /**
     * Carrega a relação do tipo (pne / lgbt) e devolve o registro, ou null.
     */
    private function loadAnalysis(User $user, AnalysisType $type): ?Model
    {
        $user->load($type->relation());

        return $user->{$type->relation()};
    }

    private function detailsNotFound(AnalysisType $type): RedirectResponse
    {
        return redirect()
            ->route($type->listRoute())
            ->with('error', 'Detalhes do usuário não encontrados.');
    }

    /**
     * Dados do e-mail. Aqui ficam as regras que NÃO são simétricas entre os tipos:
     *  - PcD: usa o nome social se ele já estiver deferido, senão o nome civil;
     *  - Nome Social: deferido usa o nome social, indeferido usa o nome civil.
     */
    private function emailData(User $user, AnalysisType $type, string $status, ?string $observations): array
    {
        $name = match (true) {
            $type === AnalysisType::Pcd => $user->lgbt?->status === 'accepted' ? $user->lgbt->name : $user->name,
            $status === 'accepted' => $user->lgbt->name,
            default => $user->name,
        };

        $data = ['name' => $name];

        if ($status === 'rejected') {
            $data['observations'] = $observations;
        }

        return $data;
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
     * Depois da alocação nas salas, deferir/indeferir mudaria a sala e a ordem
     * dos candidatos. Devolve o redirecionamento com o erro, ou null se estiver liberado.
     */
    private function allocationLock(string $route, string $verb, string $subject): ?RedirectResponse
    {
        if (! Exam::allocationExists()) {
            return null;
        }

        return redirect()
            ->route($route)
            ->with('error', "Não é possível {$verb} {$subject}, pois a alocação dos candidatos nas salas de prova já foi feita.");
    }

    public function pdf(AnalysisType $type, int $id)
    {
        $inscription = Inscription::with("user.{$type->relation()}")
            ->where('user_id', $id)
            ->firstOrFail();

        $title = match ($type) {
            AnalysisType::Pcd => 'Pessoa com Deficiência',
            AnalysisType::Lgbt => 'Candidato com Nome Social',
        };

        $pdf = Pdf::loadView('admin.analyses.pdf', compact('inscription', 'title', 'type'));

        return $pdf->stream("{$inscription->user->name}.pdf");
    }
}
