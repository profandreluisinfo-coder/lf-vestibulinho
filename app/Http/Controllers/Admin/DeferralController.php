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
            'observations' => 'Autorização deferida pelo administrador',
        ]);

        $this->sendEmail(
            to: $user->email,
            subject: 'Vestibulinho LF - Deferimento de Relatório/Laudo Médico',
            data: [
                'name' => ($user?->lgbt?->status === 'accepted') ? $user?->lgbt?->name : $user->name
            ],
            view: 'emails.deferral.pne.accepted',
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
            data: [
                'name' => ($user?->lgbt?->status === 'accepted') ? $user?->lgbt?->name : $user->name,
                'observations' => $user?->pne?->observations
            ],
            view: 'emails.deferral.pne.rejected',
        );

        return redirect()
            ->route('admin.inscriptions.pcd')
            ->with('success', 'Relatório/laudo indeferido com sucesso. O candidato será notificado por e-mail.');
    }

    public function showAcceptAuthorization(User $user): View|RedirectResponse
    {
        $user->load(['inscription.exam_result', 'lgbt']);

        if ($user->inscription?->exam_result) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Não é possível deferir a autorização, pois este candidato já está inscrito em uma prova agendada.');
        }

        if (! $user->lgbt) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        return view('admin.deferrals.authorization', [
            'user' => $user,
            'action' => 'accept',
        ]);
    }

    public function showRejectAuthorization(User $user): View|RedirectResponse
    {
        $user->load(['inscription.exam_result', 'lgbt']);

        if ($user->inscription?->exam_result) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Não é possível indeferir a autorização, pois o candidato já está inscrito em uma prova agendada.');
        }

        if (! $user->lgbt) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Detalhes do usuário não encontrados.');
        }

        return view('admin.deferrals.authorization', [
            'user' => $user,
            'action' => 'reject',
        ]);
    }

    public function acceptAuthorization(User $user): RedirectResponse
    {
        $user->load(['inscription.exam_result', 'lgbt']);

        if ($user->inscription?->exam_result) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Não é possível deferir a autorização, pois o candidato já está inscrito em uma prova agendada.');
        }

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
                view: 'emails.deferral.lgbt.accepted',
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
        $user->load(['inscription.exam_result', 'lgbt']);

        if ($user->inscription?->exam_result) {
            return redirect()
                ->route('admin.inscriptions.lgbts')
                ->with('error', 'Não é possível indeferir a autorização, pois o candidato já está inscrito em uma prova agendada.');
        }

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
                view: 'emails.deferral.lgbt.rejected',
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

    // acceptAuthorization / rejectAuthorization permanecem como estão,
    // ou podem seguir o mesmo padrão depois se fizer sentido.
}
