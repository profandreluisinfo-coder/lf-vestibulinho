<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Exibe a lista de cursos cadastrados.
     */
    public function index(): View
    {
        // Obter todos os cursos
        $courses = Course::all();

        return view('admin.vestibulinho.courses.index', compact('courses'));
    }

    /**
     * Exibe o formulário para criar um novo curso.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CourseRequest $request): RedirectResponse
    {
        Course::create($request->validated());

        return alertSuccess('Curso registrado com sucesso!', 'admin.courses.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        return view('admin.vestibulinho.courses.edit', ['course' => Course::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, string $id): RedirectResponse
    {
        Course::where('id', $id)->update($request->only(['vacancies']));

        return alertSuccess('Curso editado com sucesso!', 'admin.courses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        // Excluir o curso com uma consulta única
        Course::where('id', $id)->delete();

        return alertSuccess('Curso excluído com sucesso!', 'admin.courses.index');
    }
}