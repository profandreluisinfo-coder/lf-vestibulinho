@extends('layouts.admin')

@section('page-title', 'Editar Curso')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-book text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Editar Curso</h6>
            </div>
        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form id="courseForm" action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT')

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

                    {{-- Vagas --}}
                    <div class="form-group mb-3">
                        <label for="vacancies" class="form-label required">Vagas:</label>

                        <input type="number" class="form-control @error('vacancies') is-invalid @enderror" id="vacancies"
                            name="vacancies" value="{{ old('vacancies', $vacancies) }}" min="0" required>

                        @error('vacancies')
                            <div class="invalid-feedback">{{ $message }}</div>
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

                    {{-- Processo Seletivo --}}
                    <div class="form-group mb-3">
                        <label for="process_id" class="form-label required">
                            Vestibulinho:
                        </label>

                        <select name="process_id" id="process_id"
                            class="form-select @error('process_id') is-invalid @enderror" required>

                            <option value="">Selecione</option>

                            @foreach ($processes as $item)
                                <option value="{{ $item->id }}" @selected(old('process_id', $selectedProcessId) == $item->id)>
                                    {{ $item->year }}
                                </option>
                            @endforeach
                        </select>

                        @error('process_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Salvar
                    </button>
                    {{-- prettier-ignore --}}
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Voltar</a>

                </form>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/rules/courses/edit.js') }}"></script>
@endpush
