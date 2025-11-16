<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Calendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SystemController extends Controller
{
    public function index()
    {
        return view('system.private.index');
    }

    public function reset()
    {
        try {
            // 🔒 Garante que só admin possa resetar o sistema
            if (!auth()->user() || (!(Gate::allows('admin')))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado. Somente administradores podem redefinir o sistema.'
                ], 403);
            }

            // 🧹 Apaga todos os usuários que não são admin
            User::where('role', '!=', 'admin')->delete();
            // Isso já apaga as inscriptions (e suas dependências) via CASCADE

            // 🔢 Ajusta AUTO_INCREMENT dos users
            $maxUserId = User::max('id');
            $nextUserId = $maxUserId ? $maxUserId + 1 : 1;
            DB::statement("ALTER TABLE users AUTO_INCREMENT = " . (int) $nextUserId);

            // 🔢 Ajusta AUTO_INCREMENT das inscriptions
            $maxInscriptionId = DB::table('inscriptions')->max('id');
            $nextInscriptionId = $maxInscriptionId ? $maxInscriptionId + 1 : 1;
            DB::statement("ALTER TABLE inscriptions AUTO_INCREMENT = " . (int) $nextInscriptionId);

            // Apaga todos os registros da tabela de calendarios 
            Calendar::truncate();

            return response()->json([
                'success' => true,
                'message' => 'Sistema redefinido com sucesso.'
            ]);
        } catch (\Throwable $e) {            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao redefinir o sistema.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}