@extends('layouts.admin')

@section('page-title', 'Comunicados')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endpush

@section('dash-content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-megaphone me-2"></i>Comunicados</h5>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setNewCommunicate">
                <i class="bi bi-plus-circle me-1"></i> Novo
            </a>
        </div>

        <form id="communicateForm" action="{{ route('app.communicates.store') }}" method="POST">
            @csrf

            {{-- Título --}}
            <div class="form-group mb-3">
                <label for="titulo" class="form-label required">Título:</label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror"
                    id="titulo" name="titulo" value="{{ old('titulo', $communicate->titulo) }}">
                @error('titulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Resumo --}}
            <div class="form-group mb-3">
                <label for="resumo" class="form-label required">Resumo:</label>
                <textarea class="form-control summernote @error('resumo') is-invalid @enderror" id="resumo" name="resumo"
                    rows="6">{{ old('resumo', $communicate->resumo) }} </textarea>
                @error('resumo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tipo --}}
            <div class="form-group mb-3">
                <label for="tipo" class="form-label required">Tipo:</label>
                <input type="text" class="form-control @error('tipo') is-invalid @enderror"
                    id="tipo" name="tipo" placeholder="ex: info, alerta, urgente"
                    value="{{ old('tipo', $communicate->tipo) }}">
                @error('tipo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- URL --}}
            <div class="form-group mb-3">
                <label for="url" class="form-label">Link (URL):</label>
                <input type="url" class="form-control @error('url') is-invalid @enderror"
                    id="url" name="url" value="{{ old('url', $communicate->url) }}">
                @error('url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="form-group mb-3">
                <label for="status" class="form-label required">Status:</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status"
                    name="status">
                    <option value="rascunho" {{ old('status') === 'rascunho' ? 'selected' : '' }}>
                        Rascunho
                    </option>
                    <option value="publicado"
                        {{ old('status') === 'publicado' ? 'selected' : '' }}>
                        Publicado
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success btn-sm">
                <i class="bi bi-check-circle me-1"></i>Salvar
            </button>

            <a href="{{ route('app.communicates.index') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-x-circle me-1"></i>Cancelar
            </a>

        </form>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-pt-BR.min.js"></script>
    <script src="{{ asset('assets/js/rules/communicates/index.js') }}"></script>
@endpush
