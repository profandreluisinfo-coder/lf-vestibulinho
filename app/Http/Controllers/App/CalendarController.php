<?php

namespace App\Http\Controllers\App;

use App\Models\Calendar;
use App\Models\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    /**
     * Exibe uma lista com as datas do vestibulinho.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view(
'app.calendar.index');
    }
  
    /**
     * Salva ou atualiza o calendário.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'inscription_start' => 'required|date',
            'inscription_end' => 'required|date|after_or_equal:inscription_start',
            'exam_location_publish' => 'required|date|after_or_equal:inscription_end',
            'exam_date' => 'required|date|after_or_equal:inscription_end',
            'answer_key_publish' => 'required|date|after_or_equal:exam_date',
            'exam_revision_start' => 'required|date|after_or_equal:exam_date',
            'exam_revision_end' => 'required|date|after_or_equal:exam_revision_start',
            'final_result_publish' => 'required|date|after_or_equal:answer_key_publish',
            'enrollment_start' => 'required|date|after_or_equal:final_result_publish',
            'enrollment_end' => 'required|date|after_or_equal:enrollment_start',
            'year' => 'required|integer|min:2026|max:2100',
        ], [
            'inscription_start.required' => 'O campo período de inscrições (início) é obrigatório.',
            'inscription_start.date' => 'O campo período de inscrições deve ser uma data.',
            'inscription_end.required' => 'O campo período de inscrições (fim) é obrigatório.',
            'inscription_end.date' => 'O campo período de inscrições deve ser uma data.',
            'inscription_end.after_or_equal' => 'O campo período de inscrições (fim) deve ser posterior ou igual ao início.',
            'exam_location_publish.required' => 'O campo data de publicação do local do exame é obrigatório.',
            'exam_location_publish.date' => 'O campo data de publicação do local do exame deve ser uma data.',
            'exam_location_publish.after_or_equal' => 'O campo data de publicação do local do exame deve ser posterior ou igual ao fim das inscrições.',
            'exam_date.required' => 'O campo data do exame é obrigatório.',
            'exam_date.date' => 'O campo data do exame deve ser uma data.',
            'exam_date.after_or_equal' => 'O campo data do exame deve ser posterior ou igual ao fim das inscrições.',
            'answer_key_publish.required' => 'O campo data de publicação do gabarito é obrigatório.',
            'answer_key_publish.date' => 'O campo data de publicação do gabarito deve ser uma data.',
            'answer_key_publish.after_or_equal' => 'O campo data de publicação do gabarito deve ser posterior ou igual à data do exame.',
            'exam_revision_start.required' => 'O campo período de revisão de prova (início) é obrigatório.',
            'exam_revision_start.date' => 'O campo período de revisão de prova deve ser uma data.',
            'exam_revision_start.after_or_equal' => 'O campo período de revisão de prova deve ser posterior ou igual à data do exame.',
            'exam_revision_end.required' => 'O campo período de revisão de prova (fim) é obrigatório.',
            'exam_revision_end.date' => 'O campo período de revisão de prova deve ser uma data.',
            'exam_revision_end.after_or_equal' => 'O campo período de revisão de prova (fim) deve ser posterior ou igual ao início.',
            'final_result_publish.required' => 'O campo data de publicação do resultado final é obrigatório.',
            'final_result_publish.date' => 'O campo data de publicação do resultado final deve ser uma data.',
            'final_result_publish.after_or_equal' => 'O campo data de publicação do resultado final deve ser posterior ou igual à publicação do gabarito.',
            'enrollment_start.required' => 'O campo período de matrículas (1ª chamada) é obrigatório.',
            'enrollment_start.date' => 'O campo período de matrículas (1ª chamada) deve ser uma data.',
            'enrollment_start.after_or_equal' => 'O campo período de matrículas (1ª chamada) deve ser posterior ou igual à publicação do resultado final.',
            'enrollment_end.required' => 'O campo período de matrículas (2ª chamada) é obrigatório.',
            'enrollment_end.date' => 'O campo período de matrículas (2ª chamada) deve ser uma data.',
            'enrollment_end.after_or_equal' => 'O campo período de matrículas (2ª chamada) deve ser posterior ou igual à 1ª chamada.',
            'year.required' => 'O campo ano do processo seletivo é obrigatório.',
            'year.integer' => 'O campo ano do processo seletivo deve ser um número inteiro.',
            'year.min' => 'O campo ano do processo seletivo deve ser maior ou igual a 2026.',
            'year.max' => 'O campo ano do processo seletivo deve ser menor ou igual a 2100.',
        ]);

        $notice = Notice::first() ?? new Notice();
        
         // Verifica se existe um edital cadastrado
        // if (!$notice->exists()) {
        //     return redirect()->back()->withErrors(['error' => 'É necessário ter um edital antes de configurar o calendário do vestibulinho.']);
        // }

        // Se já existe um calendário, atualiza. Senão, cria novo.
        $calendar = Calendar::first();
        
        if ($calendar) {
            $calendar->update($validated);
            $message = 'Calendário atualizado com sucesso!';
        } else {
            Calendar::create($validated);
            $message = 'Calendário criado com sucesso!';
        }

        return redirect()->route(
'app.calendar.index')->with('success', $message);
    }

    /**
     * Exibe o formulário do calendário (criar ou editar).
     * Como só existe um calendário no sistema, busca o primeiro ou cria um vazio.
     * 
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Busca o único registro ou cria um objeto vazio
        //$calendar = Calendar::first() ?? new Calendar();
        
        return view(
'app.calendar.edit');
    }
}