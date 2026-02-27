<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Call;
use App\Models\CallList;
use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Notice;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
     * Atualiza todos os registros da tabela de settings.
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

    public function calendar(Request $request): RedirectResponse
    {
        $settings = [
            'calendar' => $request->filled('calendar')
        ];

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'calendar' => $settings['calendar']
            ]
        );

        Cache::forget('global_settings'); // MUITO IMPORTANTE!

        if (Setting::first()->calendar) {
            // return redirect()->back()->with('success', 'Acesso ao calendário liberado com sucesso!');
            return alertSuccess('Acesso ao calendário liberado com sucesso!', 'app.calendar.index');
        }

        // return redirect()->back()->with('success', 'Acesso ao calendário bloqueado com sucesso!');
        return alertSuccess('Acesso ao calendário bloqueado com sucesso!', 'app.calendar.index');
    }

    /**
     * Altera o status de um arquivo de edital.
     *
     * Este método alterna o status de um arquivo de edital da pasta 'notices' no banco de dados.
     * Se o arquivo estiver publicado, ele será despublicado e vice-versa.
     *
     * @param \App\Models\Notice $notice Arquivo de edital a ser publicado/despublicado.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notice(): RedirectResponse
    {
        $setting = Setting::firstOrCreate(['id' => 1]);

        $setting->notice = !$setting->notice; // alterna o valor
        $setting->save();

        Cache::forget('global_settings'); // MUITO IMPORTANTE!

        return alertSuccess('Status alterado com sucesso!', 'app.notices.index');
    }

    /**
     * Atualiza o status de acesso ao local de prova e dispara e-mails em fila.
     *
     * Caso o acesso seja liberado (location = true),
     * cria um Job para cada candidato com local de prova definido.
     */
    public function location(Request $request): RedirectResponse
    {
        // Define se acesso foi liberado
        $status = $request->filled('location');

        // Atualiza a configuração
        Setting::updateOrCreate(
            ['id' => 1],
            ['location' => $status]
        );

        Cache::forget('global_settings'); // MUITO IMPORTANTE!

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
    public function result(Request $request): RedirectResponse
    {
        $settings = [
            'result' => $request->filled('result')
        ];

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'result' => $settings['result']
            ]
        );

        Cache::forget('global_settings'); // MUITO IMPORTANTE!

        if (Setting::first()->result) {
            return redirect()->back()->with('success', 'Acesso ao resultado liberado com sucesso!');
        }

        return redirect()->back()->with('success', 'Acesso ao resultado bloqueado com sucesso!');
    }
}
