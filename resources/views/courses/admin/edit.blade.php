@extends('layouts.admin.master')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Editar Curso')

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-book me-2"></i>Editar</h5>
        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form id="courseForm" action="{{ route('courses.edit', $course->id) }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name" class="form-label required">Nome:</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ $course->name }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label required">Descrição:</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" value="{{ $course->description }}">
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Duration --}}
                    <div class="form-group mb-3">
                        <label for="duration" class="form-label required">Duração:</label>
                        <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration"
                            name="duration" value="{{ $course->duration }}">
                        @error('duration')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="vacancies" class="form-label required">Vagas:</label>
                        <input type="number" class="form-control @error('vacancies') is-invalid @enderror" id="vacancies"
                            name="vacancies" value="{{ $course->vacancies }}">
                        @error('vacancies')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="info" class="form-label required">Perfil profissional do egresso:</label>
                        <input type="text" class="form-control @error('info') is-invalid @enderror" id="info"
                            name="info" value="{{ $course->info }}">
                        @error('info')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Salvar
                    </button>
                    {{-- prettier-ignore --}}
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-x-circle me-1"></i>Cancelar</a>

                </form>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/rules/admin/courses/edit.js') }}"></script>
    {{-- <script src="{{ asset('assets/swa/courses/delete.js') }}"></script> --}}
@endpush
