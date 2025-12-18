<?php

namespace App\Http\Controllers\App;

use App\Models\Setting;
use Illuminate\View\View;
use App\Models\ExamResult;
use App\Models\Inscription;
use App\Models\ExamLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ExamAllocationService;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    /**
     * Mostra a lista de alocação dos candidatos por sala.
     *
     * @return \Illuminate\View\View
     */
    // public function listSchedule()
    public function index()
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

        return view('exam.admin.index');
    }

    /**
     * Mostra a página de configurações de agendamento de provas.
     *
     * @return View Retorna a view com as informações de configuração de agendamento de provas.
     */
    public function create(): View
    {
        $pending = ExamResult::whereNull('email_sent_at')->count();

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
            'pending' => $pending,
            'accesStatus' => $accesStatus,
            'rooms' => $rooms,
            'examInfo' => $examInfo,
            'examDate' => DB::table('calendars')->where('id', 1)->value('exam_date'),
        ]);

        return view('exam.admin.create');
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
    public function store(Request $request, ExamAllocationService $service)
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

        return redirect()->route('exam.index')->with(
            'success',
            'Prova agendada com sucesso!'
        );
    }
}