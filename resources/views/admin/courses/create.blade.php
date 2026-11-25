@extends('layouts.admin')

@section('page-title', 'Novo Curso')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Cursos', 'url' => route('admin.courses.index')],
        ['label' => 'Novo curso'],
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
                    <h4>Novo Curso</h4>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <form id="courseForm" action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    {{-- Curso --}}
                    <div class="form-group mb-3">
                        <label for="name" class="form-label required">Nome do curso:</label>
                        <input type="text" name="name" id="name" class="form-control @error ('name') is-invalid @enderror" value="{{ old('name') }}" aria-label="Nome do curso">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Descrição do curso --}}
                    <div class="form-group mb-3">
                        <label for="description" class="form-label required">Descrição do curso:</label>
                        <textarea name="description" id="description" class="form-control @error ('description') is-invalid @enderror" aria-label="Descrição do curso">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Duração do curso --}}
                    <div class="form-group mb-3">
                        <label for="duration" class="form-label required">Duração do curso:</label>
                        <input type="number" min="0" name="duration" id="duration" class="form-control @error ('duration') is-invalid @enderror" value="{{ old('duration') }}" aria-label="Duração do curso">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Informações do curso --}}
                    <div class="form-group mb-3">
                        <label for="info" class="form-label">Informações sobre o curso:</label>
                        <textarea name="info" id="info" class="form-control @error ('info') is-invalid @enderror" aria-label="Informações sobre o curso">{{ old('info') }}</textarea>
                        @error('info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vagas --}}
                    <div class="form-group mb-3">
                        <label for="vacancies" class="form-label">Número de vagas:</label>

                        <input type="number" class="form-control @error('vacancies') is-invalid @enderror" id="vacancies" aria-label="Número de vagas"
                            name="vacancies" value="{{ old('vacancies') }}" min="0">

                        @error('vacancies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i> Salvar
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
    <script src="{{ asset('assets/js/admin/courses/create.js') }}"></script>
@endpush
