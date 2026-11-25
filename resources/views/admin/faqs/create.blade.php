@extends('layouts.admin')

@section('page-title', 'Editar Pergunta Frequente')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endpush

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [
            ['label' => 'Início', 'url' => route('admin.index')],
            ['label' => 'Perguntas Frequentes', 'url' => route('admin.faqs.index')],
            ['label' => 'Nova Pergunta'],
        ],
    ])
@endsection

@section('content')

    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-question-circle"></i>
                </div>
                <div>
                    <h4>Nova Pergunta</h4>
                    <span>
                        Preencha os campos abaixo para criar uma nova pergunta frequente
                    </span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">

            <div class="card-body">
                <form action="{{ route('admin.faqs.store') }}" method="POST" id="faqForm">
                    @csrf
                    <div class="form-group mb-3">

                        <label for="category" class="form-label required">
                            Categoria:
                        </label>

                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" list="categoriesList"
                            aria-describedby="categoryHelp" aria-label="Categoria">

                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <datalist id="categoriesList">
                            @foreach ($categories as $category)
                                <option value="{{ $category->name }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </datalist>

                        <small id="categoryHelp" class="text-primary fst-italic">
                            <i class="bi bi-info-circle me-1"></i> Clique para escolher uma categoria já
                            cadastrada ou digite uma nova.
                        </small>

                    </div>
                    <div class="form-group">
                        <label for="question" class="form-label required">Pergunta:</label>
                        <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" name="question" aria-label="Pergunta">
                        
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="answer" class="form-label required">Resposta:</label>
                        <textarea class="form-control summernote @error('answer') is-invalid @enderror" id="answer" name="answer" rows="6" aria-label="Resposta"></textarea>
                        
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Salvar
                    </button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Voltar
                    </a>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-pt-BR.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/admin/faqs/create.js') }}"></script>
@endpush
