<?php

namespace App\Http\Controllers\App;

use App\Models\Call;
use App\Models\User;
use App\Models\Course;
use App\Models\Notice;
use App\Models\Setting;
use App\Models\Calendar;
use App\Models\CallList;
use Mockery\Matcher\Any;
use Illuminate\View\View;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Exibe a a view para a página de redefinição do sistema
     *
     * @return View
     */
    public function index(): View
    {
        return view('app.system.index');
    }

    /**
     * Redefine o sistema
     *
     * Garante que só admin possa resetar o sistema.
     * Apaga todos os usuários que não são admin.
     * Apaga todas as inscrições (e suas dependências) via CASCADE.
     * Ajusta AUTO_INCREMENT dos users.
     * Ajusta AUTO_INCREMENT das inscrições.
     * Zera vagas dos cursos.
     * Apaga todos os registros da tabela de calendários.
     * Apaga todos os registros da tabela de edital.
     * Apaga todos os registros da tabela de resultados de exames.
     * Apaga todos os registros da tabela de chamadas.
     * Apaga todos os registros da tabela de chamadas.
     * Apaga todos os registros da tabela de settings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(): JsonResponse
    {
        try {
            // Garante que só admin possa resetar o sistema
            if (!Auth::user()->role === 'admin') {
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

            // Alterar para 'false' campos 'calendar', 'result' e 'location' da tabela de settings
            Setting::query()->update([
                'calendar' => false,
                'result' => false,
                'location' => false,
            ]);

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

    public function calendar(Request $request)
    {
        $settings = [
            'calendar' => $request->filled('calendar')
        ];

        Setting::updateOrCreate(
            ['id' => 1], 
            ['calendar' => $settings['calendar']
        ]);

        if (Setting::first()->calendar) {
            return redirect()->back()->with('success', 'Acesso ao calendário liberado com sucesso!');
        }

        return redirect()->back()->with('success', 'Acesso ao calendário bloqueado com sucesso!');
    }   

    /**
     * Atualiza o status de acesso ao local de prova e dispara e-mails em fila.
     *
     * Caso o acesso seja liberado (location = true),
     * cria um Job para cada candidato com local de prova definido.
     */
    public function location(Request $request)
    {
        // Define se acesso foi liberado
        $status = $request->filled('location');

        // Atualiza a configuração
        Setting::updateOrCreate(
            ['id' => 1],
            ['location' => $status]
        );

        // Se bloqueou, simplesmente retorna
        if (!$status) {
            return redirect()->back()->with('success', 'Acesso ao local bloqueado com sucesso!');
        }

        // Se liberou, também não enviamos nada aqui.
        // Apenas permitimos que o CRON diurno processe o envio
        // de forma segura e em lotes (300/dia, por exemplo).

        return redirect()->back()->with(
            'success',
            'Acesso ao Local liberado! Os e-mails serão enviados automaticamente pelo sistema.'
        );
    }

    /**
     * Atualiza o status de acesso ao resultado e dispara e-mails em fila.
     *
     * Caso o acesso seja liberado (result = true),
     * cria um Job para cada candidato com resultado definido.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function result(Request $request)
    {
        $settings = [
            'result' => $request->filled('result')
        ];

        Setting::updateOrCreate(
            ['id' => 1], 
            ['result' => $settings['result']
        ]);

        if (Setting::first()->result) {
            return redirect()->back()->with('success', 'Acesso ao resultado liberado com sucesso!');
        }

        return redirect()->back()->with('success', 'Acesso ao resultado bloqueado com sucesso!');
    }
}
