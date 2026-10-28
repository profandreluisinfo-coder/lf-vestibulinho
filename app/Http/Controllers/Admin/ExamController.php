<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\ExamAllocationService;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExamController extends Controller
{
    /**
     * Mostra a lista de alocação dos candidatos por sala.
     *
     * @return \Illuminate\View\View
     */
    public function show(): View
    {
        $candidates = Exam::with(['location', 'inscription.user.lgbt', 'inscription.user.pne'])
            ->orderBy('location_id')
            ->orderBy('room_number')
            ->get()
            ->map(function ($exam) {
                $user = $exam->inscription->user;

                return (object) [
                    'location'       => $exam->location->name,
                    'room'           => $exam->room_number,
                    'inscription_id' => $exam->inscription_id,
                    'name'           => $user->name,
                    'social_name'    => $user->lgbt?->status === 'accepted' ? $user->lgbt->name : null,
                    'pne'            => $user->pne?->status === 'accepted',
                ];
            })
            ->groupBy('location')
            ->map(
                fn($rooms) => $rooms
                    ->groupBy('room')
                    ->map(
                        fn($list) => $list
                            ->sortBy(fn($c) => Str::lower(Str::ascii($c->social_name ?? $c->name)))
                            ->values()
                    )
            );


        return view('admin.exam.show', [
            'candidates' => $candidates
        ]);
    }

    /**
     * Mostra a página de configurações de agendamento de provas.
     *
     * @return View Retorna a view com as informações de configuração de agendamento de provas.
     */
    public function create(): View
    {
        return view('admin.exam.create');
    }

    /**
     * Grava as configurações de agendamento de provas e aloca os candidatos.
     *
     * As regras de capacidade (salas necessárias, limite por local, PNE no primeiro local)
     * ficam no service; aqui só validamos o formulário e exibimos o erro, se houver.
     */
    public function store(Request $request, ExamAllocationService $service)
    {
        $data = $request->validate([
            'candidates_per_room' => 'required|integer|min:1|max:50',
            'split_locations'     => 'required|in:yes,no',
            'split_from_room'     => 'required_if:split_locations,yes|nullable|integer|min:2',
        ], [
            'candidates_per_room.required' => 'O campo candidatos por sala é obrigatório.',
            'candidates_per_room.integer'  => 'O campo candidatos por sala deve ser um número inteiro.',
            'candidates_per_room.min'      => 'O campo candidatos por sala deve ser maior do que zero.',
            'candidates_per_room.max'      => 'O campo candidatos por sala deve ser menor ou igual a :max.',
            'split_locations.required'     => 'O campo dividir locais é obrigatório.',
            'split_locations.in'           => 'O campo dividir locais deve ser sim ou não.',
            'split_from_room.required_if'  => 'Informe a partir de qual sala os locais serão divididos.',
            'split_from_room.integer'      => 'O campo dividir locais a partir da sala deve ser um número inteiro.',
            'split_from_room.min'          => 'O campo dividir locais a partir da sala deve ser no mínimo :min.',
        ]);

        try {
            $service->allocate($data);
        } catch (DomainException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return alertSuccess('Prova agendada com sucesso!');
    }
}
