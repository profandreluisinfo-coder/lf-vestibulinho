@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Cursos')

@section('dash-content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-book me-2"></i>Cursos</h5>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setNewCourse">
                <i class="bi bi-plus-circle me-1"></i> Novo
            </a>
        </div>

        <div class="table-responsive">

            <table id="courses" class="table-striped table-hover table caption-top">
                <caption>{{ config('app.name') }} {{ $calendar->year }} - Lista de Cursos</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">Cursos</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Vagas</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">

                    @forelse ($courses as $course)

                        <tr>
                            <td class="w-25">{{ $course->name }}</td>
                            <td class="w-50">{{ $course->description }}</td>
                            <td>{{ $course->vacancies }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Editar -->
                                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square" title="Editar"></i>
                                    </a>
                                    <!-- Excluir -->
                                    <form id="delete-course-form-{{ $course->id }}"
                                        action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                        onclick="confirmCourseDelete({{ $course->id }}, '{{ addslashes($course->name) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center">Nenhum curso cadastrado</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>

        <div class="modal fade" id="setNewCourse" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="setNewCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="setLocalModalLabel"><i class="bi bi-plus-circle me-2"></i>Novo</h5>
                    </div>
                    <div class="modal-body">

                        <div class="card shadow-sm">
                            <div class="card-body">

                                <form id="courseForm" action="{{ route('courses.create') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label required">Nome:</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label required">Descrição:</label>
                                        <input type="text"
                                            class="form-control @error('description') is-invalid @enderror" id="description"
                                            name="description" value="{{ old('description') }}">
                                        @error('description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="vacancies" class="form-label required">Vagas:</label>
                                        <input type="number" min="1" max="120"
                                            class="form-control @error('vacancies') is-invalid @enderror"
                                            id="vacancies" name="vacancies" value="{{ old('vacancies') }}">
                                        @error('vacancies')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i>Salvar
                                    </button>

                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/rules/admin/courses/create.js') }}"></script>
    <script src="{{ asset('assets/swa/courses/delete.js') }}"></script>
@endpush