<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Process;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $processes = Process::select('id', 'year')->get();

        return view('admin.courses.index', compact('courses', 'processes'));
    }

    /**
     * Cria um novo curso.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'duration'      => ['nullable', 'string', 'max:255'],
            'info'          => ['nullable', 'string'],
            'selective_id'  => ['required', 'exists:processes,id'],
            'vacancies'     => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($data) {
            $course = Course::create([
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'duration'    => $data['duration'] ?? null,
                'info'        => $data['info'] ?? null,
            ]);

            $course->processes()->attach($data['selective_id'], [
                'vacancies' => $data['vacancies'],
            ]);
        });

        return alertSuccess(
            'Curso registrado com sucesso!'
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course): View
    {
        $processes = Process::orderByDesc('year')->get();

        $selectedProcessId = $course->processes()
            ->orderByDesc('processes.year')
            ->value('processes.id');

        $vacancies = $selectedProcessId
            ? $course->processes()
            ->where('processes.id', $selectedProcessId)
            ->first()
            ?->pivot->vacancies
            : null;

        return view('admin.courses.edit', compact(
            'course',
            'processes',
            'selectedProcessId',
            'vacancies'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration'    => ['nullable', 'string', 'max:255'],
            'info'        => ['nullable', 'string'],
            'process_id'  => ['required', 'exists:processes,id'],
            'vacancies'   => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $course) {
            // Atualiza os dados permanentes do curso.
            $course->update([
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'duration'    => $data['duration'] ?? null,
                'info'        => $data['info'] ?? null,
            ]);

            // Atualiza a associação com o processo e a quantidade de vagas.
            $course->processes()->syncWithoutDetaching([
                $data['process_id'] => [
                    'vacancies' => $data['vacancies'],
                ],
            ]);
        });

        return alertSuccess(
            'Curso atualizado com sucesso!'
        );
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
