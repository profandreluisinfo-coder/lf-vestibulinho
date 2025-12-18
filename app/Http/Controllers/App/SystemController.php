<?php

namespace App\Http\Controllers\App;

use App\Models\Call;
use App\Models\User;
use App\Models\Notice;
use App\Models\Calendar;
use App\Models\CallList;
use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    public function index()
    {
        return view('system.admin.index');
    }

    public function reset()
    {
        try {
            // Garante que só admin possa resetar o sistema
            if (!auth()->user() || (!(Gate::allows('admin')))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado. Somente administradores podem redefinir o sistema.'
                ], 403);
            }

            // DESABILITA CHAVES ESTRANGEIRAS
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Apaga todos os usuários que não são admin
            User::where('role', '!=', 'admin')->delete();
            // Isso já apaga as inscriptions (e suas dependências) via CASCADE

            // Ajusta AUTO_INCREMENT dos users
            $maxUserId = User::max('id');
            $nextUserId = $maxUserId ? $maxUserId + 1 : 1;
            DB::statement("ALTER TABLE users AUTO_INCREMENT = " . (int) $nextUserId);

            // Ajusta AUTO_INCREMENT das inscriptions
            $maxInscriptionId = DB::table('inscriptions')->max('id');
            $nextInscriptionId = $maxInscriptionId ? $maxInscriptionId + 1 : 1;
            DB::statement("ALTER TABLE inscriptions AUTO_INCREMENT = " . (int) $nextInscriptionId);

            // Zera vagas dos cursos
            Course::whereNotNull('vacancies')->update(['vacancies' => 0]);

            // Apaga todos os registros da tabela de calendarios 
            Calendar::truncate();

            // Apaga todos os registros da tabela de edital
            Notice::truncate();

            // Apaga todos os registros da tabela de resultados de exames
            ExamResult::truncate();

            // Apaga todos os registros da tabela de chamadas
            CallList::truncate();

            // Apaga todos os registros da tabela de chamadas
            Call::truncate();

            // Apaga todos os registros da tabela de settings
            Setting::truncate();

            // Apagar os dados de autenticação
            session()->flush();

            // REATIVA CHAVES ESTRANGEIRAS
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json([
                'success' => true,
                'message' => 'Sistema redefinido com sucesso. Por favor, faça login novamente.'
            ]);
        } catch (\Throwable $e) {

            // GARANTE que as chaves serão reativadas mesmo se der erro
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json([
                'success' => false,
                'message' => 'Erro ao redefinir o sistema.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}