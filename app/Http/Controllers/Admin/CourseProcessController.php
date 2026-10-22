<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseProcess;
use App\Models\Process;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseProcessController extends Controller
{
    public function create(): View
    {
        $courses = Course::orderBy('name')->get();
        $processes = Process::orderByDesc('year')->get();

        return view('admin.courses.create', compact(
            'courses',
            'processes'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'process_id' => [
                'required',
                'exists:processes,id',
                Rule::unique('course_process', 'process_id')
                    ->where('course_id', $request->input('course_id')),
            ],
            'vacancies' => ['required', 'integer', 'min:0'],
        ], [
            'course_id.required' => 'Selecione um curso.',
            'course_id.exists' => 'O curso selecionado não existe.',
            'process_id.required' => 'Selecione o vestibulinho.',
            'process_id.exists' => 'O processo seletivo selecionado não existe.',
            'process_id.unique' => 'Este curso já está associado a esse vestibulinho.',
            'vacancies.required' => 'Informe o número de vagas.',
            'vacancies.integer' => 'O número de vagas deve ser um inteiro.',
            'vacancies.min' => 'O número de vagas não pode ser negativo.',
        ]);

        CourseProcess::create($data);

        return alertSuccess(
            'Registro efetuado com sucesso!',
            'admin.courses.index'
        );
    }

    /**
     * Exibe o formulário para editar a associação entre curso e processo.
     */
    public function edit(CourseProcess $courseProcess): View
    {
        $processes = Process::orderByDesc('year')->get();

        return view('admin.courses.edit', compact(
            'courseProcess',
            'processes'
        ));
    }

    /**
     * Atualiza a associação entre curso e processo.
     */
    public function update(
        Request $request,
        CourseProcess $courseProcess
    ): RedirectResponse {
        $data = $request->validate([
            'process_id' => [
                'required',
                'exists:processes,id',
                Rule::unique('course_process', 'process_id')
                    ->where('course_id', $courseProcess->course_id)
                    ->ignore($courseProcess->id),
            ],
            'vacancies' => [
                'required',
                'integer',
                'min:0',
            ],
        ], [
            'process_id.required' => 'Selecione o vestibulinho.',
            'process_id.exists' => 'O processo seletivo selecionado não existe.',
            'process_id.unique' => 'Este curso já está associado a esse vestibulinho.',
            'vacancies.required' => 'Informe o número de vagas.',
            'vacancies.integer' => 'O número de vagas deve ser um inteiro.',
            'vacancies.min' => 'O número de vagas não pode ser negativo.',
        ]);

        $courseProcess->update($data);

        return alertSuccess(
            'Associação do curso atualizada com sucesso!',
            'admin.courses.index'
        );
    }

    /**
     * Remove a associação entre curso e processo seletivo.
     */
    public function destroy(CourseProcess $courseProcess): RedirectResponse
    {
        $courseProcess->delete();

        return alertSuccess(
            'Associação do curso excluída com sucesso!',
            'admin.courses.index'
        );
    }
}
