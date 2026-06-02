@extends('layouts.admin')

@section('page-title', 'Editar Comunicado')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endpush

@section('dash-content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Comunicado</h5>
        </div>

        <form id="communicateForm" action="{{ route('app.communicates.update', $communicate) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Título --}}
            <div class="form-group mb-3">
                <label for="titulo" class="form-label required">Título:</label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo"
                    name="titulo" value="{{ $communicate->titulo }}">
                @error('titulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Resumo --}}
            <div class="form-group mb-3">
                <label for="resumo" class="form-label required">Resumo:</label>
                <textarea class="form-control summernote @error('resumo') is-invalid @enderror" id="resumo" name="resumo"
                    rows="6">{{ $communicate->resumo }} </textarea>
                @error('resumo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tipo --}}
            <div class="form-group mb-3">
                <label for="tipo" class="form-label required">Tipo:</label>
                <input type="text" class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo"
                    placeholder="ex: info, alerta, urgente" value="{{ $communicate->tipo }}">
                @error('tipo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- URL --}}
            <div class="form-group mb-3">
                <label for="url" class="form-label">Link (URL):</label>
                <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url"
                    value="{{ $communicate->url }}">
                @error('url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Anexos atuais --}}
            @if ($communicate->attachments->isNotEmpty())
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        <i class="bi bi-paperclip me-1"></i>
                        Anexos atuais
                    </label>

                    <div class="list-group">

                        @foreach ($communicate->attachments as $attachment)
                            <div class="list-group-item d-flex justify-content-between align-items-center border px-4">

                                <div class="d-flex align-items-center">

                                    <i class="bi bi-file-earmark-text text-primary me-2"></i>

                                    <a href="{{ Storage::url($attachment->path) }}" target="_blank"
                                        class="text-decoration-none">
                                        {{ $attachment->name }}
                                    </a>

                                </div>

                                <div class="d-flex align-items-center gap-2">

                                    <a href="{{ Storage::url($attachment->path) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="confirmAttachmentDelete({{ $attachment->id }},'{{ addslashes($attachment->name) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>
            @endif

            {{-- Attachments --}}
            <div class="form-group mb-3">
                <label for="attachments" class="form-label">Anexos:</label>
                <input type="file" class="form-control @error('attachments') is-invalid @enderror" id="attachments"
                    name="attachments[]" multiple>
                @error('attachments.*')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="form-group mb-3">
                <label for="status" class="form-label required">
                    Status:
                </label>

                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">

                    <option value="rascunho" {{ $communicate->status === 'rascunho' ? 'selected' : '' }}>
                        Rascunho
                    </option>

                    <option value="publicado" {{ $communicate->status === 'publicado' ? 'selected' : '' }}>
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
        {{-- FIM DO FORMULÁRIO PRINCIPAL --}}

    </div>
@endsection

{{-- FORMULÁRIOS DE DELETE - FORA DO FORMULÁRIO PRINCIPAL --}}
@if ($communicate->attachments->isNotEmpty())
    @foreach ($communicate->attachments as $attachment)
        <form id="delete-attachment-form-{{ $attachment->id }}"
            action="{{ route('app.communicates.attachments.destroy', $attachment) }}"
            method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-pt-BR.min.js"></script>
@endpush

@push('scripts')
    {{-- jQuery Validate (OBRIGATÓRIO - carregar PRIMEIRO) --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validate@1.19.5/dist/jquery.validate.min.js"></script>

    {{-- Seu arquivo (DEPOIS que tudo está pronto) --}}
    <script src="{{ asset('assets/js/rules/communicates/edit.js') }}"></script>
@endpush
