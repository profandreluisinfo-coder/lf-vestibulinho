<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class CalendarController extends Controller
{
    /**
     * Exibe o formulário do calendário (criar ou editar).
     * Como só existe um calendário no sistema, busca o primeiro ou cria um vazio.
     * 
     * @return \Illuminate\View\View
     */
    public function edit(): View
    {
        $calendar = Calendar::latest('id')->first() ?? new Calendar();

        return view('admin.calendar.edit', compact('calendar'));
    }

    /**
     * Salva ou atualiza o calendário.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request)
    {
        try {

            $validated = $request->validate(
                [
                    'reference' => 'required|integer',
                    'year' => 'required|integer',
                    'edital' => 'required|file|mimetypes:application/pdf',
                    'inscription_start' => 'required|date',
                    'inscription_end' => 'required|date|after_or_equal:inscription_start',
                    'exam_location_publish' => 'required|date|after_or_equal:inscription_end',
                    'exam_date' => 'required|date|after_or_equal:inscription_end',
                    'answer_key_publish' => 'required|date|after_or_equal:exam_date',
                    'exam_revision_start' => 'required|date|after_or_equal:exam_date',
                    'exam_revision_end' => 'required|date|after_or_equal:exam_revision_start',
                    'final_result_publish' => 'required|date|after_or_equal:answer_key_publish',
                    'enrollment_start' => 'required|date|after_or_equal:final_result_publish',
                    'enrollment_end' => 'required|date|after_or_equal:enrollment_start'
                ],
                [
                    'reference.required' => 'O campo ano do processo seletivo é obrigatório.',
                    'reference.integer' => 'O campo ano do processo seletivo deve ser um número inteiro.',
                    'year.required' => 'O campo ano do vestibulinho é obrigatório.',
                    'year.integer' => 'O campo ano do vestibulinho deve ser um número inteiro.',
                    'edital.required' => 'O campo edital do calendário é obrigatório.',
                    'edital.file' => 'O campo edital do calendário deve ser um arquivo.',
                    'edital.mimetypes' => 'O campo edital do calendário deve ser um PDF.',
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
                    'enrollment_end.after_or_equal' => 'O campo período de matrículas (2ª chamada) deve ser posterior ou igual à 1ª chamada.'
                ]
            );

            $file = $request->file('edital');

            if (!$file) {
                return alertError('Envie um arquivo válido.');
            }

            $originalName = str_replace(' ', '_', $file->getClientOriginalName());

            $fileName = pathinfo($originalName, PATHINFO_FILENAME)
                . '_' . time()
                . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('notices', $fileName, 'public');

            $validated['edital'] = $path;
        } catch (ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Existem erros no formulário. Verifique os campos destacados.');
        }

        $calendar = Calendar::latest('id')->first(); // Busca o calendário mais recente

        if ($calendar) {
            $calendar->update($validated);
            return alertSuccess('Calendário definido com sucesso!', 'admin.calendar.show');
        }

        Calendar::create($validated);
        return alertSuccess('Calendário definido com sucesso!', 'admin.calendar.show');
    }

    /**
     * Exibe uma lista com as datas do vestibulinho.
     * 
     * @return \Illuminate\View\View
     */
    public function show(): View
    {
        return view('admin.calendar.show');
    }

    public function activate(Calendar $calendar): RedirectResponse
    {
        // Inverte: se é true, vira false; se é false, vira true
        $calendar->is_active = !$calendar->is_active;
        $calendar->save();

        Cache::forget('global_settings');

        $mensagem = $calendar->is_active
            ? 'Calendário ativado com sucesso!'
            : 'Calendário desativado com sucesso!';

        return alertSuccess($mensagem, 'admin.calendar.show');
    }
}
