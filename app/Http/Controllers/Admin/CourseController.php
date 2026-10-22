<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Process;
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
        $courses = Course::with('processes')->get();
        $processes = Process::orderByDesc('year')->get();

        return view('admin.courses.index', compact('courses', 'processes'));
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
