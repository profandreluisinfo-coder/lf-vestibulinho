@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Editar FaQ')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endpush

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar</h5>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('faq.edit', $faq->id) }}" class="edit-form" method="POST"
                    id="edit-faq-form-{{ $faq->id }}">
                    @csrf
                    <div class="mb-3">
                        <label for="question" class="form-label required">Pergunta:</label>
                        <input type="text" class="form-control" id="question" name="question"
                            value="{{ $faq->question }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="answer" class="form-label required">Resposta:</label>
                        <textarea class="form-control summernote" id="answer" name="answer" rows="6" required>{{ $faq->answer }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        {{-- prettier-ignore --}}
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-circle me-1"></i>Salvar
                        </button>
                        <a href="{{ route('faq.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            
            </div>
        </div>

    </div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-pt-BR.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/rules/admin/faqs/edit.js') }}"></script>
@endpush