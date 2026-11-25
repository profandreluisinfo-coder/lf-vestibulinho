<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class CourseController extends Controller
{
    /**
     * Exibe a lista de cursos cadastrados.
     */
    public function index(): View
    {
        $courses = Course::paginate(10); // 10 cursos por página

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('admin.courses.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required|integer',
            'vacancies' => 'nullable|integer',
            'info' => 'nullable',
        ], [
            'name.required' => 'O nome do curso é obrigatório.',
            'description.required' => 'A descrição do curso é obrigatória.',
            'duration.required' => 'A duração do curso é obrigatória.',
            'duration.integer' => 'A duração do curso deve ser um número inteiro.',
            'vacancies.integer' => 'O número de vagas deve ser um número inteiro.',
        ]);

        $course = new Course();
        $course->name = $request->name;
        $course->description = $request->description;
        $course->duration = $request->duration;
        $course->vacancies = $request->vacancies;
        $course->info = $request->info;
        $course->save();

        return alertSuccess('Curso criado com sucesso!', 'admin.courses.index');
    }   

    /**
     * Exibe o formulário para editar a associação entre curso e processo.
     */
    public function edit(Course $course) 
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Atualiza a associação entre curso e processo.
     */
    public function update(Request $request, Course $course) 
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required|integer',
            'vacancies' => 'nullable|integer',
            'info' => 'nullable',
        ], [
            'name.required' => 'O nome do curso é obrigatório.',
            'description.required' => 'A descrição do curso é obrigatória.',
            'duration.required' => 'A duração do curso é obrigatória.',
            'duration.integer' => 'A duração do curso deve ser um número inteiro.',
            'vacancies.integer' => 'O número de vagas deve ser um número inteiro.',
        ]);

        $course->update([
            'name' => $request->name,
            'description' => Purifier::clean($request->description, 'faq'),
            'duration' => $request->duration,
            'vacancies' => $request->vacancies,
            'info' => Purifier::clean($request->info, 'faq'),
        ]);

        return alertSuccess('Curso atualizado com sucesso!', 'admin.courses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return alertSuccess('Curso excluído com sucesso!');
    }
}
