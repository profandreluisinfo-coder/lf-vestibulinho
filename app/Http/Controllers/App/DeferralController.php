<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeferralController extends Controller
{
    /**
     * Defere o uso de nome social para um candidato.
     *
     * @param User $user O usuário que terá o uso de nome social deferido.
     * @return JsonResponse Com um JSON contendo o status da operação e uma mensagem.
     */
    public function acceptAuthorization(User $user): JsonResponse
    {
        if ($user->inscription?->exam_result) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível deferir o uso de o nome social, pois este candidato já está inscrito na prova agendada!'
            ]);
        }

        $user->update(['authorization_accepted' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Uso de nome social deferido com sucesso!',
            'data' => [
                'status' => '<span class="badge bg-success">Deferido</span>',
                'actions' => ''
            ]
        ]);
    }

    /**
     * Indefer o uso de nome social para um candidato.
     *
     * @param Request $request A requisição HTTP que contém o motivo do indeferimento.
     * @param User $user O usuário que terá o uso de nome social indeferido.
     * @return JsonResponse Com um JSON contendo o status da operação e uma mensagem.
     */
    public function rejectAuthorization(Request $request, User $user): JsonResponse
    {
        if ($user->inscription?->exam_result) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível indeferir o nome social, pois o candidato já está inscrito na prova.'
            ]);
        }

        $user->update([
            'authorization_accepted' => 2,
            'authorization_rejection_reason' => $request->reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Uso de nome social indeferido com sucesso.',
            'data' => [
                'status' => '<span class="badge bg-danger">Indeferido</span>',
                'actions' => ''
            ]
        ]);
    }

    /**
     * Defer o relatório/laudo para um candidato.
     *
     * Caso o candidato já esteja inscrito em uma prova agendada, retorna um JSON com status false e uma mensagem de erro.
     * Caso contrário, atualiza o status do relatório/laudo para "deferido" e retorna um JSON com status true e uma mensagem de sucesso.
     *
     * @param User $user O usuário que terá o relatório/laudo deferido.
     * @return JsonResponse Com um JSON contendo o status da operação e uma mensagem.
     * @throws \Exception
     */
    public function acceptReport(User $user): JsonResponse
    {
        $user->load(['inscription.exam_result', 'user_detail']);

        if ($user->inscription?->exam_result) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível deferir o relatório/laudo, pois este candidato já está inscrito em uma prova agendada.'
            ]);
        }

        if (!$user->user_detail) {
            return response()->json([
                'success' => false,
                'message' => 'Detalhes do usuário não encontrados.'
            ]);
        }

        $user->user_detail->update([
            'pne_report_accepted' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Relatório/laudo deferido com sucesso!',
            'data' => [
                'status' => '<span class="badge bg-success">Deferido</span>',
                'actions' => ''
            ]
        ]);
    }

    /**
     * Indefer o relatório/laudo para um candidato.
     *
     * Caso o candidato já esteja inscrito em uma prova agendada, retorna um JSON com status false e uma mensagem de erro.
     * Caso contrário, atualiza o status do relatório/laudo para "deferido" e retorna um JSON com status true e uma mensagem de sucesso.
     *
     * @param Request $request A requisição HTTP que contém o motivo do indeferimento.
     * @param User $user O usuário que terá o relatório/laudo indeferido.
     * @return JsonResponse Com um JSON contendo o status da operação e uma mensagem.
     * @throws \Exception
     */
    public function rejectReport(Request $request, User $user): JsonResponse
    {
        $user->load(['inscription.exam_result', 'user_detail']);

        if ($user->inscription?->exam_result) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível indeferir o relatório/laudo, pois o candidato já está inscrito em uma prova.'
            ]);
        }

        if (!$user->user_detail) {
            return response()->json([
                'success' => false,
                'message' => 'Detalhes do usuário não encontrados.'
            ]);
        }

        $user->user_detail->update([
            'pne_report_accepted' => 2,
            'pne_report_rejection_reason' => $request->input('reason')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Relatório/laudo indeferido com sucesso.',
            'data' => [
                'status' => '<span class="badge bg-danger">Indeferido</span>',
                'actions' => ''
            ]
        ]);
    }
}