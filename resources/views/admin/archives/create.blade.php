@extends('layouts.admin')

@section('page-title', 'Prova')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Provas', 'url' => route('admin.archives.index')],
        ['label' => 'Novo'],
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
                    <span>Adicione uma nova prova</span>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="form-file" action="{{ route('admin.archives.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    {{-- Ano em que a prova foi aplicada --}}
                        <div class="form-group mb-3">
                            <label for="year" class="form-label required">Ano de referência da
                                prova</label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                id="year" value="{{ old('year') }}" min="2020" aria-label="Ano de referência da prova">
                            @error('year')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Arquivo relacionado --}}
                        <div class="form-group mb-3">
                            <label for="file" class="form-label required">Arquivo relacionado à
                                prova</label>
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
                            <label for="answer" class="form-label">Gabarito relacionado</label>
                            <input type="file" name="answer" class="form-control @error('answer') is-invalid @enderror"
                                id="answer" aria-label="Gabarito relacionado">
                            @error('answer')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-lg"></i>Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/archives/create.js') }}"></script>
@endpush
