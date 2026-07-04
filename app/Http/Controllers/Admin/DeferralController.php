<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTransactionalEmailJob;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeferralController extends Controller
{
    public function showAcceptReport(User $user): View|RedirectResponse
    {
        $user->load(['inscription.exam_result', 'pne']);

        if ($user->inscription?->exam_result) {
            return redirect()
                ->route('admin.inscriptions.pcd')
                ->with('error', 'Não é possível deferir o relatório/laudo, pois este candidato já está inscrito em uma prova agendada.');
        }

        if (! $user->pne) {
            return redirect()
                ->route('admin.inscriptions.pcd')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        return view('admin.deferrals.report', [
            'user' => $user,
            'action' => 'accept',
        ]);
    }

    /**
     * Exibe a tela de confirmação de indeferimento do relatório/laudo (com campo de motivo).
     */
    public function showRejectReport(User $user): View|RedirectResponse
    {
        $user->load(['inscription.exam_result', 'pne']);

        if ($user->inscription?->exam_result) {
            return redirect()
                ->route('admin.inscriptions.pcd')
                ->with('error', 'Não é possível indeferir o relatório/laudo, pois o candidato já está inscrito em uma prova.');
        }

        if (! $user->pne) {
            return redirect()
                ->route('admin.inscriptions.pcd')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        return view('admin.deferrals.report', [
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
        $user->load(['inscription.exam_result', 'pne']);

        if ($user?->inscription?->exam_result) {
            return redirect()
                ->route('admin.inscriptions.pcd')
                ->with('error', 'Não é possível deferir o relatório/laudo, pois este candidato já está inscrito em uma prova agendada.');
        }

        if (! $user->pne) {
            return redirect()
                ->route('admin.inscriptions.pcd')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        $user->pne->update([
            'status' => 'accepted',
            'observations' => null,
        ]);

        $this->sendEmail(
            to: $user->email,
            subject: 'Vestibulinho LF - Deferimento de Relatório/Laudo Médico',
            data: ['name' => $user->name],
            view: 'emails.deferral.accepted',
        );

        return redirect()
            ->route('admin.inscriptions.pcd')
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
        $user->load(['inscription.exam_result', 'pne']);

        if ($user->inscription?->exam_result) {
            return redirect()
                ->route('admin.inscriptions.pcd')
                ->with('error', 'Não é possível indeferir o relatório/laudo, pois o candidato já está inscrito em uma prova.');
        }

        if (! $user->pne) {
            return redirect()
                ->route('admin.inscriptions.pcd')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        $user->pne->update([
            'status' => 'rejected',
            'observations' => $request->input('reason'),
        ]);

        $this->sendEmail(
            to: $user->email,
            subject: 'Vestibulinho LF - Indeferimento de Relatório/Laudo Médico',
            data: ['name' => $user->name, 'observations' => $user?->pne?->observations],
            view: 'emails.deferral.rejected',
        );

        return redirect()
            ->route('admin.inscriptions.pcd')
            ->with('success', 'Relatório/laudo indeferido com sucesso. O candidato será notificado por e-mail.');
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

    // acceptAuthorization / rejectAuthorization permanecem como estão,
    // ou podem seguir o mesmo padrão depois se fizer sentido.
}
