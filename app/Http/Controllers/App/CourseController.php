<?php

namespace App\Http\Controllers\App;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obter todos os cursos
        $courses = Course::all();

        return view('courses.admin.index', compact('courses'));
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
    public function store(CourseRequest $request)
    {
        Course::create($request->validated());

        return redirect()->route('courses.index')->with('success', 'Curso registrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
        
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('courses.admin.edit', ['course' => Course::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, string $id)
    {
        Course::where('id', $id)->update($request->only(['name', 'description', 'duration', 'info', 'vacancies']));

        return redirect()->route('courses.index')->with('success', 'Curso editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Excluir o curso com uma consulta única
        Course::where('id', $id)->delete();

        return redirect()->route('courses.index')->with('success', 'Curso excluido com sucesso!');
    }
}