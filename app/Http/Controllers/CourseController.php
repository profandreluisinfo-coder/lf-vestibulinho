<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obter todos os cursos
        $courses = Course::all();

        // Passar para a view
        view()->share('courses', $courses);

        return view('courses.private.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'description' => 'required|max:100',
            'vacancies' => 'required|numeric|min:1'
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome deve ter no máximo :max caracteres.',
            'description.required' => 'O campo descrição é obrigatório.',
            'description.max' => 'O campo descrição deve ter no máximo :max caracteres.',
            'vacancies.required' => 'O campo vagas é obrigatório.',
            'vacancies.numeric' => 'O campo vagas deve ser numérico.',
            'vacancies.min' => 'O campo vagas deve ter no mínimo :min vaga.',
        ]);

        $course = new Course();
        $course->name = $request->name;
        $course->description = $request->description;
        $course->vacancies = $request->vacancies;
        $course->save();

        return redirect()->route('courses.index')->with('success', 'Curso registrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Obter o curso
        $course = Course::findOrFail($id);

        // Passar para a view
        view()->share('course', $course);

        return view('courses.private.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:50',
            'description' => 'required|max:100',
            'vacancies' => 'required|numeric|min:1'
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome deve ter no máximo :max caracteres.',
            'description.required' => 'O campo descrição é obrigatório.',
            'description.max' => 'O campo descrição deve ter no máximo :max caracteres.',
            'vacancies.required' => 'O campo vagas é obrigatório.',
            'vacancies.numeric' => 'O campo vagas deve ser numérico.',
            'vacancies.min' => 'O campo vagas deve ter no mínimo :min vaga.',
        ]);

        $course = Course::findOrFail($id);
        $course->name = $request->name;
        $course->description = $request->description;
        $course->vacancies = $request->vacancies;
        $course->save();

        return redirect()->route('courses.index')->with('success', 'Curso editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Obter o curso
        $course = Course::findOrFail($id);

        // Excluir o curso
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Curso excluido com sucesso!');
    }
}
