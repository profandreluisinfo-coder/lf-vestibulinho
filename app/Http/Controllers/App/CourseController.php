<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    /**
     * Exibe a lista de cursos cadastrados.
     */
    public function index()
    {
        // Obter todos os cursos
        $courses = Course::all();

        return view('app.courses.index', compact('courses'));
    }

    /**
     * Exibe o formulário para criar um novo curso.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request): RedirectResponse
    {
        Course::create($request->validated());

        // return redirect()->route('app.courses.index')->with('success', 'Curso registrado com sucesso!');
        return alertSuccess('Curso registrado com sucesso!', 'app.courses.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('app.courses.edit', ['course' => Course::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, string $id)
    {
        Course::where('id', $id)->update($request->only(['name', 'description', 'duration', 'info', 'vacancies']));

        // return redirect()->route('app.courses.index')->with('success', 'Curso editado com sucesso!');
        return alertSuccess('Curso editado com sucesso!', 'app.courses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Excluir o curso com uma consulta única
        Course::where('id', $id)->delete();

        // return redirect()->route('app.courses.index')->with('success', 'Curso excluido com sucesso!');
        return alertSuccess('Curso excluído com sucesso!', 'app.courses.index');
    }
}