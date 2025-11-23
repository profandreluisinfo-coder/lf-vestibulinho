<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;
use App\Models\ExamResult;
use App\Models\Inscription;
use App\Models\ExamLocation;
use Illuminate\Http\Request;
use App\Services\MailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\ExamAllocationService;

class ExamController extends Controller
{
    /**
     * Recupere todos os locais de exames e compartilhe-os com a visualização.
     *
     * @return View A view que exibe a lista de locais de exames.
     */

    public function examLocations(): View
    {
        // Obter todos as locais de prova
        $examLocations = ExamLocation::all();

        // Passar para a view
        view()->share('examLocations', $examLocations);

        return view('locations.private.list');
    }

    /**
     * Armazene um novo local de exame.
     *
     * Essa ação valida os dados do formulário e armazena um novo local de exame.
     * A view compartilha os dados válidos com o formulário para que possam ser
     * reutilizados em caso de erro.
     *
     * @param Request $request A solicitação HTTP com os dados do formulário.
     *
     * @return RedirectResponse Uma resposta de redirecionamento para a página anterior.
     */
    public function storeLocations(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'address' => 'required|max:200',
            'rooms_available' => 'required|numeric|min:1|max:40',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome deve ter no máximo :max caracteres.',
            'address.required' => 'O campo endereço é obrigatório.',
            'address.max' => 'O campo endereço deve ter no máximo :max caracteres.',
            'rooms_available.required' => 'O campo salas disponíveis é obrigatório.',
            'rooms_available.numeric' => 'O campo salas disponíveis deve ser numérico.',
            'rooms_available.min' => 'O campo salas disponíveis deve ser maior do que zero.',
            'rooms_available.max' => 'O campo salas disponíveis deve menor ou igual a :max.',
        ]);

        $examLocation = new ExamLocation();
        $examLocation->name = $this->stringToUpper($data['name']); // Converter para maiúsculas e UTF-8 para evitar problemas com acentuações e caracteres especiais.
        $examLocation->address = $this->stringToUpper($data['address']);
        $examLocation->rooms_available = $data['rooms_available'];
        $examLocation->save();

        // Verificar se a operação foi bem-sucedida
        if ($examLocation->wasRecentlyCreated) {
            return redirect()->back()->with(
                'success',
                'Local de exame cadastrado com sucesso!'
            );
        }

        return redirect()->back()->with(
            'danger',
            'Ocorreu um erro ao cadastrar o local de exame.'
        );
    }

    /**
     * Mostra a página de configurações de agendamento de provas.
     *
     * @return View Retorna a view com as informações de configuração de agendamento de provas.
     */
    public function scheduleSettings(): View
    {
        $accesStatus = Setting::first() ?? new Setting();

        $rooms = DB::table('exam_results')
            ->join('exam_locations', 'exam_results.exam_location_id', '=', 'exam_locations.id')
            ->select(
                'exam_results.exam_location_id',
                'exam_locations.name as location_name',
                'exam_results.room_number',
                DB::raw('COUNT(*) as qtd')
            )
            ->groupBy('exam_results.exam_location_id', 'exam_results.room_number', 'exam_locations.name')
            ->orderBy('exam_results.room_number')
            ->get();

        $examInfo = DB::table('exam_results')
            ->select('exam_date as date', 'exam_time as time')
            ->first();

        // Passar para a view
        view()->share([
            'accesStatus' => $accesStatus,
            'rooms' => $rooms,
            'examInfo' => $examInfo,
            'examDate' => DB::table('calendars')->where('id', 1)->value('exam_date'),
        ]);

        return view('schedule.private.create');
    }

    /**
     * Grava as configurações de agendamento de provas.
     *
     * Verifica se existem inscrições e locais cadastrados, e se o número de salas cadastradas é suficiente para alocar os inscritos.
     * Em seguida, verifica o valor de 'split_locations' e faz a alocação dos inscritos nas salas.
     *
     * @param  Request  $request
     * @param  ExamAllocationService  $service
     * @return RedirectResponse
     */
    public function storeSchedule(Request $request, ExamAllocationService $service)
    {
        $data = $request->validate([
            'candidates_per_room' => 'required|numeric|min:1|max:50',
            'split_locations' => 'required|in:yes,no',
            'split_from_room' => 'nullable|numeric|min:2',
            'exam_date' => 'required|date',
            'exam_time' => 'required|date_format:H:i',
        ], [
            'candidates_per_room.required' => 'O campo candidatos por sala é obrigatório.',
            'candidates_per_room.numeric' => 'O campo candidatos por sala deve ser numérico.',
            'candidates_per_room.min' => 'O campo candidatos por sala deve ser maior do que zero.',
            'candidates_per_room.max' => 'O campo candidatos por sala deve menor ou igual a :max.',
            'split_locations.required' => 'O campo dividir locais é obrigatório.',
            'split_locations.in' => 'O campo dividir locais deve ser sim ou não.',
            'split_from_room.numeric' => 'O campo dividir locais a partir da sala deve ser numérico.',
            'split_from_room.min' => 'O campo dividir locais a partir da sala deve ser maior do que zero.',
            'exam_date.required' => 'O campo data é obrigatório.',
            'exam_date.date' => 'O campo data deve ser uma data válida.',
            'exam_time.required' => 'O campo hora é obrigatório.',
            'exam_time.date_format' => 'O campo hora deve ser uma hora válida.',
        ]);

        // Verificar se existem inscriçõoes
        if (Inscription::count() === 0) {
            return redirect()->back()->with(
                'error',
                'Não foi possível agendar a prova, pois não existem inscrições.'
            );
        }

        // Verificar se existem locais cadastrados
        if (ExamLocation::count() === 0) {
            return redirect()->back()->with(
                'error',
                'Não foi possível agendar a prova, pois não existe nenhum local de prova cadastrado.'
            );
        }

        if (DB::table('exam_results')->count() > 0) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
            DB::table('exam_results')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        }

        // Obter o total de salas cadastradas
        $totalRooms = ExamLocation::sum('rooms_available');
        // Obter o total de inscritos
        $totalInscritos = Inscription::count();

        // Verificar o valor de 'split_locations'
        if ($data['split_locations'] === 'yes') {
            // Verificar se o número de locais cadastrados é menor que 2
            if ($totalRooms < 2) {
                return redirect()->back()->with(
                    'error',
                    'O número de locais cadastrados é insuficente para dividir as salas.'
                );
            }
        }

        // Verificar se o número de salas cadastradas é suficiente para alocar os inscritos
        if (($totalInscritos / $data['candidates_per_room']) > $totalRooms) {
            return redirect()->back()->with(
                'error',
                'O número de salas cadastradas é insuficiente para alocar os candidatos.'
            );
        }

        $service->allocate($data);

        return redirect()->route('exam.schedule')->with(
            'success',
            'Prova agendada com sucesso!'
        );
    }

    /**
     * Mostra a lista de alocação dos candidatos por sala.
     *
     * @return \Illuminate\View\View
     */
    public function listSchedule()
    {
        $candidates = DB::table('exam_results')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->join('exam_locations', 'exam_results.exam_location_id', '=', 'exam_locations.id')
            ->select(
                'exam_results.room_number',
                'exam_locations.name as location_name',
                'users.cpf as candidate_cpf',
                'users.name as candidate_name',
                'users.social_name as candidate_social_name',
                'users.pne as candidate_pne',
                'exam_results.exam_date as date',
                'exam_results.exam_time as time',
                'inscriptions.id as inscription_id' // 👈 aqui entra o número de inscrição
            )
            ->orderBy('exam_results.room_number')
            ->orderBy('users.name')
            ->get()
            ->groupBy(function ($item) {
                return $item->location_name . ' - Sala ' . $item->room_number;
            });

        // Passar para a view
        view()->share([
            'candidates' => $candidates,
        ]);

        return view('schedule.private.list');
    }

    /**
     * Mostra a view para atualizar um local de prova.
     *
     * @param int $id ID do local de prova.
     * @return \Illuminate\View\View
     */
    public function editLocation($id)
    {
        $location = ExamLocation::find($id);

        view()->share([
            'location' => $location,
        ]);

        return view('locations.private.edit');
    }

    /**
     * Atualiza um local de prova.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID do local de prova.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLocationPost(Request $request, $id)
    {
        $location = ExamLocation::find($id);
        $location->name = $this->stringToUpper($request->name);
        $location->address = $this->stringToUpper($request->address);
        $location->rooms_available = $request->rooms_available;
        $location->save();

        return redirect()->route('exam.locations')->with(
            'success',
            'Local atualizado com sucesso!'
        );
    }

    /**
     * Remove um local de prova.
     *
     * @param int $id ID do local de prova.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $examLocation = ExamLocation::findOrFail($id);

                ExamResult::where('exam_location_id', $examLocation->id)->delete();
                $examLocation->delete();
            });

            return redirect()->back()->with('success', 'Local excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Atualiza o status de acesso ao local de prova e dispara e-mails em fila.
     *
     * Caso o acesso seja liberado (location = true),
     * cria um Job para cada candidato com local de prova definido.
     */
    public function setAccessToLocation(Request $request)
    {
        set_time_limit(0);

        // Atualiza configuração
        $locationAccess = $request->filled('location');
        Setting::updateOrCreate(['id' => 1], ['location' => $locationAccess]);

        // Se bloqueou o acesso, só retorna
        if (!$locationAccess) {
            return redirect()->back()->with('success', 'Acesso ao local bloqueado com sucesso!');
        }

        Log::info('🚀 Iniciando liberação de acesso ao local de prova com envio de e-mails em fila...');

        $subject = 'CONVOCAÇÃO PARA PROVA – PROCESSO SELETIVO 2026 E.M. DR. LEANDRO FRANCESCHINI';
        $view = 'mail.exam_location_info';

        // Processa em lotes para evitar sobrecarga de memória
        ExamResult::with(['inscription.user', 'location'])
            ->chunk(100, function ($results) use ($subject, $view) {
                foreach ($results as $result) {
                    $user = $result->inscription->user ?? null;

                    if (!$user || !$user->email) {
                        Log::warning("⚠️ Usuário sem e-mail - inscrição ID {$result->inscription_id}");
                        continue;
                    }

                    $email = $user->email;
                    $content = [
                        'name' => $user->social_name ?? $user->name,
                        'date' => $result->exam_date,
                        'time' => $result->exam_time,
                        'location' => $result->location->name ?? 'Não informado',
                        'address' => $result->location->address ?? 'Endereço não cadastrado',
                        'room_number' => $result->room_number ?? 'Não definido'
                    ];

                    // Dispara o Job pra fila
                    dispatch(new \App\Jobs\SendExamLocationMailJob(
                        email: $email,
                        subject: $subject,
                        content: $content,
                        view: $view
                    ))->delay(now()->addSeconds(rand(0, 10))); // Pequeno delay ajuda o servidor

                    Log::info("📨 Job de e-mail criado para {$email}");
                }
            });

        return redirect()->back()->with('success', 'Acesso ao Local liberado e e-mails agendados para envio em fila!');
    }


    /**
     * Converte uma string para maiúsculas, considerando a codificação UTF-8.
     *
     * @param string $string String a ser convertida.
     *
     * @return string String convertida para maiúsculas.
     */
    private function stringToUpper($string)
    {
        return mb_strtoupper($string, 'UTF-8'); // Converter para maiúsculas e UTF-8 para evitar problemas com acentuações e caracteres especiais.
    }
}
