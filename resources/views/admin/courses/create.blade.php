@extends('layouts.admin')

@section('page-title', 'Editar Curso')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-book text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Definição de vagas por curso</h6>
            </div>
        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form id="courseProcessForm" action="{{ route('admin.course-processes.store') }}" method="POST">
                    @csrf

                    {{-- Curso --}}
                    <div class="form-group mb-3">
                        <label for="course_id" class="form-label required">Curso:</label>

                        <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror"
                            required>

                            <option value="">Selecione o curso</option>

                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Processo Seletivo --}}
                    <div class="form-group mb-3">
                        <label for="process_id" class="form-label required">Vestibulinho:</label>

                        <select name="process_id" id="process_id"
                            class="form-select @error('process_id') is-invalid @enderror" required>

                            <option value="">Selecione o vestibulinho</option>

                            @foreach ($processes as $process)
                                <option value="{{ $process->id }}" @selected(old('process_id') == $process->id)>
                                    {{ $process->year }}
                                </option>
                            @endforeach
                        </select>

                        @error('process_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vagas --}}
                    <div class="form-group mb-3">
                        <label for="vacancies" class="form-label required">Número de vagas:</label>

                        <input type="number" class="form-control @error('vacancies') is-invalid @enderror" id="vacancies"
                            name="vacancies" value="{{ old('vacancies') }}" min="0" required>

                        @error('vacancies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i> Registrar vagas
                    </button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Voltar
                    </a>
                </form>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/rules/courses/create.js') }}"></script>
@endpush
