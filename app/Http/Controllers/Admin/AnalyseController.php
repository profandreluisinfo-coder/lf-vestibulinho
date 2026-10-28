<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTransactionalEmailJob;
use App\Models\Inscription;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyseController extends Controller
{
    public function showAcceptReport(User $user): View|RedirectResponse
    {
        $user->load(['pne']);

        if (! $user->pne) {
            return redirect()
                ->route('admin.inscriptions.pcds')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        return view('admin.analyses.report', [
            'user' => $user,
            'action' => 'accept',
        ]);
    }

    /**
     * Exibe a tela de confirmação de indeferimento do relatório/laudo (com campo de motivo).
     */
    public function showRejectReport(User $user): View|RedirectResponse
    {
        $user->load(['pne']);

        if (! $user->pne) {
            return redirect()
                ->route('admin.inscriptions.pcds')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        return view('admin.analyses.report', [
            'user' => $user,
            'action' => 'reject',
        ]);
    }

    /**
     * Processa o deferimento do relatório/laudo.
     */
    public function acceptReport(string $id): RedirectResponse
    {
        $user = User::find($id);
        $user->load(['pne']);

        if (! $user->pne) {
            return redirect()
                ->route('admin.inscriptions.pcds')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        $user->pne->update([
            'status' => 'accepted',
            'observations' => 'Autorização deferida pelo administrador',
        ]);

        $this->sendEmail(
            to: $user->email,
            subject: 'Vestibulinho LF - Deferimento de Relatório/Laudo Médico',
            data: [
                'name' => ($user?->lgbt?->status === 'accepted') ? $user?->lgbt?->name : $user->name
            ],
            view: 'emails.analyses.pne.accepted',
        );

        return redirect()
            ->route('admin.inscriptions.pcds')
            ->with('success', 'Relatório/laudo deferido com sucesso!');
    }

    /**
     * Processa o indeferimento do relatório/laudo.
     */
    public function rejectReport(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $user = User::find($id);
        $user->load(['pne']);

        if (! $user->pne) {
            return redirect()
                ->route('admin.inscriptions.pcds')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        $user->pne->update([
            'status' => 'rejected',
            'observations' => $request->input('reason'),
        ]);

        $this->sendEmail(
            to: $user->email,
            subject: 'Vestibulinho LF - Indeferimento de Relatório/Laudo Médico',
            data: [
                'name' => ($user?->lgbt?->status === 'accepted') ? $user?->lgbt?->name : $user->name,
                'observations' => $user?->pne?->observations
            ],
            view: 'emails.analyses.pne.rejected',
        );

        return redirect()
            ->route('admin.inscriptions.pcds')
            ->with('success', 'Relatório/laudo indeferido com sucesso. O candidato será notificado por e-mail.');
    }

    public function showAcceptAuthorization(User $user): View|RedirectResponse
    {
        $user->load(['lgbt']);

        if (! $user->lgbt) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        return view('admin.analyses.authorization', [
            'user' => $user,
            'action' => 'accept',
        ]);
    }

    public function showRejectAuthorization(User $user): View|RedirectResponse
    {
        $user->load(['lgbt']);

        if (! $user->lgbt) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        return view('admin.analyses.authorization', [
            'user' => $user,
            'action' => 'reject',
        ]);
    }

    public function acceptAuthorization(User $user): RedirectResponse
    {
        $user->load(['lgbt']);

        if (! $user->lgbt) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        try {
            $user->lgbt->update([
                'status' => 'accepted',
                'observations' => 'Autorização deferida pelo administrador',
            ]);

            $this->sendEmail(
                to: $user->email,
                subject: 'Vestibulinho LF - Autorização de Nome Social',
                data: [
                    'name' => $user->lgbt->name,
                ],
                view: 'emails.analyses.lgbt.accepted',
            );

            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('success', 'Autorização de nome social deferida com sucesso. O candidato será notificado por e-mail.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Erro ao deferir a autorização: '.$e->getMessage());
        }
    }

    public function rejectAuthorization(Request $request, User $user): RedirectResponse
    {
        $user->load(['lgbt']);

        if (! $user->lgbt) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        try {
            $user->lgbt->update([
                'status' => 'rejected',
                'observations' => $request->input('reason'),
            ]);

            $this->sendEmail(
                to: $user->email,
                subject: 'Vestibulinho LF - Autorização de Nome Social',
                data: [
                    'name' => $user->name,
                    'observations' => $user?->lgbt?->observations,
                ],
                view: 'emails.analyses.lgbt.rejected',
            );

            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('success', 'Autorização de nome social indeferida com sucesso. O candidato será notificado por e-mail.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Erro ao indeferir a autorização: '.$e->getMessage());
        }
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

        $pdf = Pdf::loadView('admin.analyses.pdf', compact('inscription', 'title', 'type'));

        return $pdf->stream("{$inscription->user->name}.pdf");
    }
}
