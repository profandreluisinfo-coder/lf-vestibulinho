@extends('layouts.admin')

@section('page-title', 'Cursos')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Cursos'],
    ]])
@endsection

@section('content')

    <div class="container">

        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <h4>Cursos</h4>
                    <span>
                        {{ $courses->total() }}
                        {{ $courses->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Novo curso
            </a>
        </div>

        <div class="table-responsive">

            <table id="courses" class="table table-striped">

                <thead class="table-success">
                    <tr>
                        <th scope="col">Curso</th>
                        <th scope="col" class="text-center">Vagas</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>

                <tbody class="table-group-divider">

                    @forelse ($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td class="text-center">
                                {{ $course->vacancies ?? '-' }}
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.courses.edit', ['course' => $course]) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar curso" aria-label="Editar curso">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Excluir --}}
                                    <form id="delete-course-form-{{ $course->id }}"
                                        action="{{ route('admin.courses.destroy', ['course' => $course]) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Excluir curso" aria-label="Excluir curso"
                                        onclick="confirmCourseDelete(
                                    {{ $course->id }},
                                    {{ Js::from($course->name) }}
                                )">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
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
