@extends('layouts.admin')

@section('page-title', 'Lista de Cursos')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-book text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Lista de Cursos e Vagas</h6>
            </div>

            <a href="{{ route('admin.course-processes.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Definir
            </a>
        </div>

        <div class="table-responsive">

            <table id="courses" class="table table-striped table-hover caption-top">
                <caption>Lista de Cursos e Vagas por Vestibulinho</caption>

                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">Vestibulinho</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Vagas</th>
                        <th scope="col" class="w-25">Ações</th>
                    </tr>
                </thead>

                <tbody class="table-group-divider">

                    @forelse ($courses as $course)
                        @foreach ($course->processes as $item)
                            <tr>
                                <td class="text-center">{{ $item->year }}</td>

                                <td>{{ $course->name }}</td>

                                <td class="text-center">
                                    {{ $item->pivot->vacancies }}
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('admin.course-processes.edit', $item->pivot->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>

                                        {{-- Excluir --}}
                                        <form id="delete-course-form-{{ $item->pivot->id }}"
                                            action="{{ route('admin.course-processes.destroy', $item->pivot->id) }}"
                                            method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                            onclick="confirmCourseDelete(
                                    {{ $item->pivot->id }},
                                    {{ Js::from($course->name) }}
                                )">
                                            <i class="bi bi-trash"></i>
                                            Excluir
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Nenhum curso cadastrado.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/courses/delete.js') }}"></script>
@endpush
