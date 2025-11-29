@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Perguntas Frequentes')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/faqs/styles.css') }}">
    <!-- Summernote -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">

    <!-- Estilos para drag and drop -->
    <style>
        .drag-handle {
            cursor: move;
            padding: 0 10px;
            color: #6c757d;
        }

        .drag-handle:hover {
            color: #0d6efd;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #f8f9fa;
        }

        .sortable-chosen {
            background: #e7f3ff;
        }

        .accordion-button {
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@section('dash-content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Perguntas Frequentes</h5>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setNewFAQ">
                <i class="bi bi-plus-circle me-2"></i> Nova
            </a>
        </div>

        @if ($faqs->isNotEmpty())
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Dica:</strong> Clique e arraste o ícone <i class="bi bi-grip-vertical"></i> para reordenar as
                perguntas.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Pesquisar por.." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-center gap-3">
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
                    <div class="accordion-item" data-faq-id="{{ $faq->id }}">
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
                                    class="d-flex justify-content-end border-bottom small text-muted mt-2 mb-2 p-2 border border-top-1">
                                    <span class="me-2">Autor: {{ $faq->user->name }}</span> |
                                    <span class="mx-2">Criado em: {{ $faq->created_at->format('d/m/Y H:i:s') }}</span> |
                                    <span class="mx-2">Alterado em: {{ $faq->updated_at->format('d/m/Y H:i:s') }}</span>
                                    |
                                    <span class="ms-2">Status:
                                        <span class="badge bg-{{ $faq->status ? 'success' : 'warning' }}">
                                            {{ $faq->status ? 'Publicado' : 'Não Publicado' }}
                                        </span>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Botão de publicar (alterar status) --}}
                                    @can('manage-faq', $faq)
                                        <form id="publish-faq-form-{{ $faq->id }}"
                                            action="{{ route('faq.publish', $faq->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                        <button type="button"
                                            class="btn btn-sm btn-{{ $faq->status ? 'warning' : 'success' }}"
                                            onclick="confirmFaqPublish({{ $faq->id }}, '{{ addslashes($faq->question) }}')">
                                            <i class="bi bi-{{ $faq->status ? 'eye-slash' : 'eye' }} me-1"></i>
                                            {{ $faq->status ? 'Não Publicar' : 'Publicar' }}
                                        </button>
                                    @endcan

                                    {{-- Botão de editar --}}
                                    @can('manage-faq', $faq)
                                        <a href="{{ route('faq.edit', $faq->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil-square me-1" title="Editar"></i> Editar
                                        </a>
                                    @endcan

                                    {{-- Botão de excluir --}}
                                    @can('manage-faq', $faq)
                                        <form id="delete-faq-form-{{ $faq->id }}"
                                            action="{{ route('faq.destroy', $faq->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmFaqDelete({{ $faq->id }}, '{{ addslashes($faq->question) }}')">
                                            <i class="bi bi-trash me-1"></i> Excluir
                                        </button>
                                    @endcan
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @include('components.no-records', [
                'message' => 'Causas de problemas com as perguntas e respostas:',
                'submessage' => 'Provavelmente nenhuma pergunta ainda foi cadastrada.',
                'action' => true,
                'actionMessage' => 'Solução: Tente cadastrar uma nova pergunta. Se o problema persistir, entre em contato com o suporte.',
            ])
        @endif

        {{-- Modal de lançar nova pergunta --}}
        <div class="modal fade" id="setNewFAQ" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="setNewFAQModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="setLocalModalLabel"><i class="bi bi-question-circle me-2"></i>Gravar
                            Nova FaQ</h5>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <form action="{{ route('faq.store') }}" method="POST" id="faqForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="question" class="form-label required">Pergunta:</label>
                                        <input type="text" class="form-control" id="question" name="question"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="answer" class="form-label required">Resposta:</label>
                                        <textarea class="form-control summernote" id="answer" name="answer" rows="6" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Gravar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-pt-BR.min.js"></script>
    <!-- SortableJS para drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/rules/admin/faqs/create.js') }}"></script>
    <script src="{{ asset('assets/swa/faqs/publish.js') }}"></script>
    <script src="{{ asset('assets/swa/faqs/delete.js') }}"></script>
    <script src="{{ asset('assets/filters/faqs.js') }}"></script>
    <script src="{{ asset('assets/js/faqs/sortable.js') }}"></script>
@endpush
