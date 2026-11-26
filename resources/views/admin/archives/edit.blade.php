@extends('layouts.admin')

@section('page-title', 'Editar Arquivos')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Arquivos', 'url' => route('admin.archives.index')],
        ['label' => 'Editar'],
    ]])
@endsection

@section('content')

    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <h4>Prova</h4>
                    <span>Edição da prova referente ao ano {{ $archive->year }}</span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form id="form-file-edit" action="{{ route('admin.archives.update', $archive) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- Ano em que a prova foi aplicada --}}
                    <div class="form-group mb-3">
                        <label for="year" class="form-label required">Ano de referência da prova</label>
                        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                            id="year" value="{{ $archive->year }}" min="2020" aria-label="Ano de referência da prova">
                        @error('year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Arquivo relacionado --}}
                    <div class="form-group mb-3">
                        <label for="file" class="form-label required">Arquivo relacionado à prova</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                            id="file" aria-label="Arquivo relacionado à prova">
                        @error('file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Gabarito relacionado --}}
                    <div class="form-group mb-3">
                        <label for="answer" class="form-label">Gabarito relacionado à prova</label>
                        <input type="file" name="answer"
                            class="form-control @error('answer') is-invalid @enderror" id="answer"
                            value="{{ old('answer') }}" aria-label="Gabarito relacionado à prova">
                        @error('answer')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Salvar
                    </button>
                </form>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/archives/edit.js') }}"></script>
@endpush