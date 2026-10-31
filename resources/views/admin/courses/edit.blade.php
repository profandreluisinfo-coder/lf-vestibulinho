@extends('layouts.admin')

@section('page-title', 'Editar Curso')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-book text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Editar Vagas de Curso por Vestibulinho</h6>
            </div>
        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form id="courseProcessForm" action="{{ route('admin.course-processes.update', $courseProcess->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    {{-- Curso --}}
                    <div class="form-group mb-3">
                        <label for="course" class="form-label">Curso:</label>

                        <input type="text" id="course" class="form-control" value="{{ $courseProcess->course->name }}"
                            readonly>
                    </div>

                    {{-- Processo Seletivo --}}
                    <div class="form-group mb-3">
                        <label for="process_id" class="form-label required">
                            Vestibulinho:
                        </label>

                        <select name="process_id" id="process_id"
                            class="form-select @error('process_id') is-invalid @enderror" required>

                            <option value="">Selecione o vestibulinho</option>

                            @foreach ($processes as $item)
                                <option value="{{ $item->id }}" @selected(old('process_id', $courseProcess->process_id) == $item->id)>
                                    {{ $item->year }}
                                </option>
                            @endforeach
                        </select>

                        @error('process_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vagas --}}
                    <div class="form-group mb-3">
                        <label for="vacancies" class="form-label required">
                            Número de vagas:
                        </label>

                        <input type="number" class="form-control @error('vacancies') is-invalid @enderror" id="vacancies"
                            name="vacancies" value="{{ old('vacancies', $courseProcess->vacancies) }}" min="0"
                            required>

                        @error('vacancies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Salvar alterações
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
    <script src="{{ asset('assets/js/admin/courses/edit.js') }}"></script>
@endpush
