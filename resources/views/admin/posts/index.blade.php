@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF ' . $process?->year . ' - Notícias e Comunicados')

{{-- =====================================================================
     ESTILOS DA PÁGINA
====================================================================== --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/site/posts/index.css') }}">
@endpush

{{-- =====================================================================
     CONTEÚDO
====================================================================== --}}
@section('content')

<div class="container-fluid py-4 px-3 px-md-4">

    {{-- Cabeçalho da página --}}
    <div class="page-header">
        <div class="page-header-title">
            <div class="icon-wrap">
                <i class="bi bi-newspaper"></i>
            </div>
            <div>
                <h4>Notícias e Comunicados</h4>
                <span>{{ $posts->total() }} {{ Str::plural('registro', $posts->total()) }} encontrado{{ $posts->total() !== 1 ? 's' : '' }}</span>
            </div>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Novo post
        </a>
    </div>

    {{-- Filtros --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="row g-2 align-items-end">

            <div class="col-12 col-sm-5 col-md-4">
                <label class="form-label mb-1" style="font-size: var(--font-size-sm); font-weight:600;">Buscar</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                        placeholder="Título ou resumo..."
                        value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-6 col-sm-3 col-md-2">
                <label class="form-label mb-1" style="font-size: var(--font-size-sm); font-weight:600;">Tipo</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="noticia"   @selected(request('type') === 'noticia')>Notícia</option>
                    <option value="comunicado" @selected(request('type') === 'comunicado')>Comunicado</option>
                </select>
            </div>

            <div class="col-6 col-sm-3 col-md-2">
                <label class="form-label mb-1" style="font-size: var(--font-size-sm); font-weight:600;">Status</label>
                <select name="published" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="1" @selected(request('published') === '1')>Publicado</option>
                    <option value="0" @selected(request('published') === '0')>Rascunho</option>
                </select>
            </div>

            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>
                @if(request()->hasAny(['search', 'type', 'published']))
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Grid de posts --}}
    @if($posts->isEmpty())
        <div class="empty-state">
            <i class="bi bi-file-earmark-x"></i>
            <h5 class="mb-1">Nenhum post encontrado</h5>
            <p class="mb-3">Tente ajustar os filtros ou crie um novo post.</p>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Criar post
            </a>
        </div>
    @else
        <div class="row g-3">
            @foreach($posts as $post)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="post-card">

                        {{-- Cabeçalho do card --}}
                        <div class="post-card-header">
                            <h6 class="post-card-title">{{ $post->title }}</h6>
                            @if($post->image)
                                <img src="{{ Storage::url($post->image) }}"
                                    alt="Capa"
                                    class="post-thumb">
                            @else
                                <div class="post-thumb-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Resumo --}}
                        @if($post->resume)
                            <p class="post-card-resume">{{ $post->resume }}</p>
                        @endif

                        {{-- Meta --}}
                        <div class="post-card-meta">
                            <span>
                                <i class="bi bi-person"></i>
                                {{ $post->author->name ?? '—' }}
                            </span>
                            <span>
                                <i class="bi bi-calendar3"></i>
                                {{ $post->created_at->format('d/m/Y') }}
                            </span>
                            @if($post->category)
                                <span>
                                    <i class="bi bi-tag"></i>
                                    {{ $post->category->name }}
                                </span>
                            @endif
                            @if($post->attachments_count ?? $post->attachments->count())
                                <span>
                                    <i class="bi bi-paperclip"></i>
                                    {{ $post->attachments_count ?? $post->attachments->count() }} anexo(s)
                                </span>
                            @endif
                        </div>

                        {{-- Rodapé: badges + ações --}}
                        <div class="post-card-footer">
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge-type badge-{{ $post->type }}">
                                    {{ $post->type === 'noticia' ? 'Notícia' : 'Comunicado' }}
                                </span>
                                <span class="badge-type {{ $post->published ? 'badge-published' : 'badge-unpublished' }}">
                                    {{ $post->published ? 'Publicado' : 'Rascunho' }}
                                </span>
                            </div>

                            <div class="post-actions">
                                {{-- Toggle publicação --}}
                                <form action="{{ route('admin.posts.toggle', $post) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm {{ $post->published ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $post->published ? 'Despublicar' : 'Publicar' }}">
                                        <i class="bi bi-{{ $post->published ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>

                                {{-- Editar --}}
                                <a href="{{ route('admin.posts.edit', $post) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Excluir --}}
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Excluir"
                                    onclick="confirmDelete('{{ route('admin.posts.destroy', $post) }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginação --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    @endif

</div>

{{-- Form oculto para DELETE --}}
<form id="delete-form" method="POST" style="display:none">
    @csrf @method('DELETE')
</form>

@endsection

{{-- =====================================================================
     SCRIPTS
====================================================================== --}}
@push('scripts')
<script src="{{ asset('assets/js/admin/site/posts/index.js') }}"></script>
@endpush