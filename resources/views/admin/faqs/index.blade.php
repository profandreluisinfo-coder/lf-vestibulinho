@extends('layouts.admin')

@section('page-title', 'Perguntas Frequentes')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endpush

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Perguntas Frequentes'],
    ]])
@endsection

@section('content')
    @php
        $results = \App\Models\Faq::paginate(10);
    @endphp
    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-question-circle"></i>
                </div>
                <div>
                    <h4>Perguntas Frequentes</h4>
                    <span>
                        {{ $results->total() }}
                        {{ $results->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Novo
            </a>
        </div>

        @if ($faqs->isNotEmpty())

            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Dica:</strong> Clique e arraste o ícone <i class="bi bi-grip-vertical"></i> para reordenar as
                perguntas.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            {{-- Pesquisas... --}}
            <div class="row mb-3">                
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Pesquisar por..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="filterCategory">
                        <option value="">Todas as categorias</option>

                        @foreach ($categories as $category)
                            <option value="{{ Str::lower($category->name) }}">
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="filterPublished" checked>
                        <label class="form-check-label" for="filterPublished">
                            Publicadas
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="0" id="filterUnpublished" checked>
                        <label class="form-check-label" for="filterUnpublished">
                            Não Publicadas
                        </label>
                    </div>
                </div>
            </div>

            <div class="accordion accordion-flush" id="faqAccordion">

                @foreach ($faqs as $faq)
                    <div class="accordion-item" data-faq-id="{{ $faq->id }}" data-status="{{ $faq->status }}"
                        data-category="{{ Str::lower($faq->category?->name) }}">

                        <h2 class="accordion-header" id="heading{{ $faq->id }}">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $faq->id }}">
                                <i class="bi bi-grip-vertical drag-handle me-2" title="Arraste para reordenar"></i>
                                {{ $faq->question }}
                            </button>
                        </h2>

                        <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqAccordion">

                            <div class="accordion-body">

                                {!! $faq->answer !!}

                                <div
                                    class="d-flex justify-content-end border-bottom small text-muted mt-2 mb-2 p-2 border border-top-1 bg-light">
                                    <span class="me-2"><i class="bi bi-person me-1"></i> {{ $faq->user->name }}</span>
                                    <span class="mx-2"><i class="bi bi-tag me-1"></i>
                                        {{ $faq->category?->category }}</span>
                                    <span class="mx-2"><i class="bi bi-floppy me-1"></i>
                                        {{ $faq->created_at->format('d/m/Y H:i:s') }}</span>
                                    <span class="mx-2"><i class="bi bi-pencil-square me-1"></i>
                                        {{ $faq->updated_at->format('d/m/Y H:i:s') }}</span>

                                    <span class="ms-2">
                                        <span class="badge bg-{{ $faq->status ? 'success' : 'warning' }}">
                                            {{ $faq->status ? 'Publicado' : 'Oculto' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-end gap-2">

                                        <form id="publish-faq-form-{{ $faq->id }}"
                                            action="{{ route('admin.faqs.publish', $faq->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-{{ $faq->status ? 'warning' : 'success' }}"
                                            title="{{ $faq->status ? 'Não Publicar' : 'Publicar' }}"
                                            onclick="confirmFaqPublish({{ $faq->id }}, '{{ addslashes($faq->question) }}')">
                                            <i class="bi bi-{{ $faq->status ? 'eye-slash' : 'eye' }}"></i> {{ $faq->status ? 'Ocultar' : 'Publicar' }}
                                        </button>

                                        <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square" title="Editar"></i> Editar
                                        </a>

                                        <form id="delete-faq-form-{{ $faq->id }}"
                                            action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="confirmFaqDelete({{ $faq->id }}, '{{ addslashes($faq->question) }}')">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                @endforeach

            </div>
        @else
            <p class="text-danger">
                <i class="bi bi-info-circle me-1"></i> Nenhuma FaQ cadastrada.
            </p>
        @endif
    </div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-pt-BR.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/admin/faqs/filters.js') }}"></script>
    <script src="{{ asset('assets/js/admin/faqs/sortable.js') }}"></script>
    <script src="{{ asset('assets/js/admin/faqs/delete.js') }}"></script>  
    <script src="{{ asset('assets/js/admin/faqs/publish.js') }}"></script>
@endpush
