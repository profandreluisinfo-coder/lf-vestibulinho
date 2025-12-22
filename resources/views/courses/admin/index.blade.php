@extends('layouts.admin.master')

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
                        <th scope="col">Vagas</th>
                        <th scope="col" class="w-25">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">

                    @forelse ($courses as $course)
                        <tr>
                            <td class="w-25">{{ $course->name }}</td>
                            <td>{{ $course->vacancies }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Detalhes -->
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#viewCourse"
                                        onclick="showCourseDetails({{ $course->id }}, '{{ addslashes($course->name) }}', '{{ addslashes($course->description) }}', '{{ $course->duration }}', '{{ addslashes($course->info) }}', {{ $course->vacancies }})">
                                        <i class="bi bi-eye" title="Ver Detalhes"></i>
                                    </a>
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

                        @include('components.no-records', [
                            'message' => 'Causas de problemas com cursos:',
                            'submessage' => 'Provavelmente nenhum curso foi cadastrado até o momento.',
                            'action' => true,
                            'actionMessage' =>
                                'Solução: Clique no botão "Novo" para iniciar o cadastro. Se o problema persistir, entre em contato com o suporte.',
                        ])
                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- Modal para cadastro de novo curso --}}
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

                                <form id="courseForm" action="{{ route('courses.store') }}" method="POST">
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
                                    {{-- Duration --}}
                                    <div class="form-group mb-3">
                                        <label for="duration" class="form-label required">Duração:</label>
                                        <input type="text" class="form-control @error('duration') is-invalid @enderror"
                                            id="duration" name="duration" value="{{ old('duration') }}">
                                        @error('duration')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    {{-- Info --}}
                                    <div class="form-group mb-3">
                                        <label for="information" class="form-label required">Perfil profissional do
                                            egresso:</label>
                                        <input type="text" class="form-control @error('info') is-invalid @enderror"
                                            id="info" name="info" value="{{ old('info') }}">
                                        @error('info')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    {{-- Vacancies --}}
                                    <div class="form-group mb-3">
                                        <label for="vacancies" class="form-label required">Vagas:</label>
                                        <input type="number" min="1" max="120"
                                            class="form-control @error('vacancies') is-invalid @enderror" id="vacancies"
                                            name="vacancies" value="{{ old('vacancies') }}">
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

        {{-- Modal para visualizar detalhes de um curso --}}
        <div class="modal fade" id="viewCourse" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-eye me-2"></i>Detalhes do Curso
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <p><strong>Nome:</strong> <span id="view-name"></span></p>
                                <p><strong>Descrição:</strong> <span id="view-description"></span></p>
                                <p><strong>Duração:</strong> <span id="view-duration"></span></p>
                                <p><strong>Perfil do Egresso:</strong> <span id="view-info"></span></p>
                                <p><strong>Vagas:</strong> <span id="view-vacancies"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/rules/admin/courses/create.js') }}"></script>
    <script src="{{ asset('assets/swa/courses/delete.js') }}"></script>
    <script>
        function showCourseDetails(id, name, description, duration, info, vacancies) {
            document.getElementById('view-name').textContent = name;
            document.getElementById('view-description').textContent = description;
            document.getElementById('view-duration').textContent = duration;
            document.getElementById('view-info').textContent = info;
            document.getElementById('view-vacancies').textContent = vacancies;
        }
    </script>
@endpush