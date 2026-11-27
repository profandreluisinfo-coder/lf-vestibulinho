@extends('layouts.admin')

@section('page-title', 'Editar Pergunta')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endpush

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Perguntas Frequentes', 'url' => route('admin.faqs.index')],
        ['label' => 'Editar Pergunta']
    ]])
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
                    <h4>Editar Pergunta</h4>
                    <span>
                        Preencha os campos abaixo para editar
                    </span>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="faqForm" action="{{ route('admin.faqs.edit', ['faq' => $faq ]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="category" class="form-label required">
                            Categoria:
                        </label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ $faq->category->name }}"
                            list="categoriesList" aria-describedby="categoryHelp" aria-label="Categoria">
                        <datalist id="categoriesList">
                            @foreach ($categories as $category)
                                <option value="{{ $category->name }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </datalist>
                        <small id="categoryHelp" class="text-primary fst-italic">
                            <i class="bi bi-info-circle me-1"></i> Clique para escolher uma categoria já cadastrada ou digite uma nova.
                        </small>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="question" class="form-label required">Pergunta:</label>
                        <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" name="question"
                            value="{{ $faq->question }}" aria-label="Pergunta">                            
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="answer" class="form-label required">Resposta:</label>
                        <textarea class="form-control summernote @error('answer') is-invalid @enderror" id="answer" name="answer" rows="6" aria-label="Resposta">{{ $faq->answer }}</textarea>                        
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-lg"></i>Salvar
                        </button>
                    </div>
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
    <script src="{{ asset('assets/js/admin/faqs/edit.js') }}"></script>
@endpush
