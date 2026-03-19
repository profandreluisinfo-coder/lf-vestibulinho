<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
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
        if (ExamResult::exists()) {
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

    public function rejectAuthorization(Request $request, User $user): JsonResponse
    {
        if (ExamResult::exists()) {
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
}
