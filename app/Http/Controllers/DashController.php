<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashController extends Controller
{
    /**
     * Página principal do painel de administração do candidato
     * 
     * Exibe as informações do usuário logado, do resultado da prova e da chamada para o resultado com lista finalizada.
     * 
     * Route: GET / candidato
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        // Se não tem inscrição, manda pra home
        if (!$user->inscription()->exists()) {
            return view('dashboard.home', compact('user'));
        }

        // Carrega tudo em *uma pancada só* com eager loading
        $user->load([
            'inscription.exam_result.examLocation',
            'inscription.exam_result.completedCall', // chamada finalizada
        ]);

        $inscription  = $user->inscription;
        $examResult   = $inscription->exam_result;
        $examLocation = $examResult?->examLocation;

        // Monta o array exam só se houver local
        $exam = $examLocation ? [
            'location_name' => $examLocation->name,
            'address'       => $examLocation->address,
            'room_number'   => $examResult->room_number,
            'exam_date'     => $examResult->exam_date,
            'exam_time'     => $examResult->exam_time,
            'user_id'       => $user->id,
            'pne'           => $user->pne,
        ] : null;

        // Chamada finalizada (ou null)
        $call = $examResult?->completedCall;

        return view('dashboard.perfil', compact('user', 'exam', 'examResult', 'call'));
    }
}
