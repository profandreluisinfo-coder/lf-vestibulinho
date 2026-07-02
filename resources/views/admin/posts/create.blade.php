@extends('layouts.admin')

@section('page-title', 'Novo Post | ' . config('app.name'))

{{-- =====================================================================
     ESTILOS
====================================================================== --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/site/posts/create.css') }}">
@endpush

{{-- =====================================================================
     CONTEÚDO
====================================================================== --}}
@section('content')

<div class="container-fluid py-4 px-3 px-md-4">

    {{-- Breadcrumb --}}
    <nav class="page-breadcrumb">
        <a href="{{ route('admin.posts.index') }}">Notícias e Comunicados</a>
        <i class="bi bi-chevron-right"></i>
        <span>Novo post</span>
    </nav>

    <form action="{{ route('admin.posts.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="post-form"
          novalidate>
        @csrf

        <div class="row g-4">

            {{-- ============================================================
                 COLUNA PRINCIPAL
            ============================================================= --}}
            <div class="col-12 col-lg-8">

                {{-- Conteúdo editorial --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-pencil-square"></i>
                        <h6>Conteúdo</h6>
                    </div>
                    <div class="form-card-body">

                        {{-- Título --}}
                        <div class="mb-3">
                            <label for="title" class="form-label required">Título</label>
                            <input type="text"
                                id="title"
                                name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}"
                                placeholder="Digite o título do post..."
                                maxlength="255"
                                required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Resumo --}}
                        <div class="mb-3">
                            <label for="resume" class="form-label">Resumo</label>
                            <input type="text"
                                id="resume"
                                name="resume"
                                class="form-control @error('resume') is-invalid @enderror"
                                value="{{ old('resume') }}"
                                placeholder="Breve descrição exibida na listagem..."
                                maxlength="255">
                            @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Opcional. Máximo 255 caracteres.</div>
                        </div>

                        {{-- Conteúdo --}}
                        <div class="mb-0">
                            <label for="content" class="form-label required">Conteúdo</label>
                            <textarea
                                id="content"
                                name="content"
                                class="form-control @error('content') is-invalid @enderror"
                                rows="10"
                                placeholder="Escreva o conteúdo completo aqui..."
                                required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Imagem de capa --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-image"></i>
                        <h6>Imagem de Capa</h6>
                    </div>
                    <div class="form-card-body">

                        <input type="file"
                            id="image"
                            name="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mb-3">JPG, PNG ou WebP. Máximo 2 MB.</div>

                        <div id="image-preview-wrap">
                            <img id="image-preview" src="" alt="Preview da capa">
                            <button type="button" id="remove-image" title="Remover imagem">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                    </div>
                </div>

                {{-- Anexos --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-paperclip"></i>
                        <h6>Anexos</h6>
                    </div>
                    <div class="form-card-body">

                        <div class="drop-zone" id="drop-zone">
                            <i class="bi bi-cloud-arrow-up"></i>
                            Arraste arquivos aqui ou <strong>clique para selecionar</strong>
                        </div>

                        <input type="file"
                            id="attachments"
                            name="attachments[]"
                            class="d-none @error('attachments.*') is-invalid @enderror"
                            multiple>
                        @error('attachments.*')
                            <div class="text-danger mt-1" style="font-size: var(--font-size-sm);">{{ $message }}</div>
                        @enderror
                        <div class="form-text mb-2">Múltiplos arquivos permitidos. Máximo 10 MB por arquivo.</div>

                        <div id="attachment-list"></div>

                    </div>
                </div>

            </div>

            {{-- ============================================================
                 COLUNA LATERAL
            ============================================================= --}}
            <div class="col-12 col-lg-4">

                {{-- Publicação --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-send"></i>
                        <h6>Publicação</h6>
                    </div>
                    <div class="form-card-body">

                        {{-- Tipo --}}
                        <div class="mb-3">
                            <label class="form-label required">Tipo</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="type" id="type-noticia"
                                        value="noticia"
                                        {{ old('type', 'noticia') === 'noticia' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type-noticia">
                                        <i class="bi bi-newspaper me-1 text-primary"></i> Notícia
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        name="type" id="type-comunicado"
                                        value="comunicado"
                                        {{ old('type') === 'comunicado' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type-comunicado">
                                        <i class="bi bi-megaphone me-1 text-warning"></i> Comunicado
                                    </label>
                                </div>
                            </div>
                            @error('type')
                                <div class="text-danger mt-1" style="font-size:var(--font-size-sm)">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    role="switch"
                                    id="published"
                                    name="published"
                                    value="1"
                                    {{ old('published') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="published">
                                    Publicar imediatamente
                                </label>
                            </div>
                        </div>

                        {{-- Data de publicação --}}
                        <div class="mb-0" id="published-at-wrap">
                            <label for="published_at" class="form-label">Data de publicação</label>
                            <input type="date"
                                id="published_at"
                                name="published_at"
                                class="form-control @error('published_at') is-invalid @enderror"
                                value="{{ old('published_at', now()->toDateString()) }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Classificação --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="bi bi-tag"></i>
                        <h6>Classificação</h6>
                    </div>
                    <div class="form-card-body">

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Categoria</label>
                            <select name="category_id"
                                id="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">— Sem categoria —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="url" class="form-label">URL externa</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url"
                                    id="url"
                                    name="url"
                                    class="form-control @error('url') is-invalid @enderror"
                                    value="{{ old('url') }}"
                                    placeholder="https://...">
                            </div>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Opcional. Link externo relacionado ao post.</div>
                        </div>

                    </div>
                </div>

                {{-- Ações --}}
                <div class="d-flex flex-column gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-floppy me-1"></i> Salvar post
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg me-1"></i> Cancelar
                    </a>
                </div>

            </div>
        </div>

    </form>
</div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-pt-BR.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

{{-- =====================================================================
     SCRIPTS
====================================================================== --}}
@push('scripts')
<script src="{{ asset('assets/js/admin/site/posts/create.js') }}"></script>
@endpush