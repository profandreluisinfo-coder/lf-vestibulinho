@extends('layouts.admin')

@section('page-title', 'Editar Curso')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Cursos', 'url' => route('admin.courses.index')],
        ['label' => 'Editar'],
    ]])
@endsection

@section('content')

    <div class="container">

        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-pencil"></i>
                </div>
                <div>
                    <h4>{{ $course->name }}</h4>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form id="courseEditForm" action="{{ route('admin.courses.update', ['course' => $course]) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    {{-- Curso --}}
                    <div class="form-group mb-3">
                        <label for="course" class="form-label required">Curso:</label>
                        <input type="text" name="name" id="course" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $course->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Descrição --}}
                    <div class="form-group mb-3">
                        <label for="description" class="form-label required">
                            Descrição:
                        </label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Duração --}}
                    <div class="form-group mb-3">
                        <label for="duration" class="form-label required">
                            Duração (anos):
                        </label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration"
                            name="duration" value="{{ old('duration', $course->duration) }}" min="0">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vagas --}}
                    <div class="form-group mb-3">
                        <label for="vacancies" class="form-label">
                            Número de vagas:
                        </label>

                        <input type="number" class="form-control @error('vacancies') is-invalid @enderror" id="vacancies"
                            name="vacancies" value="{{ old('vacancies', $course->vacancies) }}" min="0">

                        @error('vacancies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Informações do curso --}}
                    <div class="form-group mb-3">
                        <label for="info" class="form-label">
                            Informações do curso:
                        </label>
                        <textarea name="info" id="info" class="form-control @error('info') is-invalid @enderror">{{ old('info', $course->info) }}</textarea>
                        @error('info')
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
