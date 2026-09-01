@extends('layouts.admin')

@section('page-title', config('app.name') . ' ' . $process?->year . ' | Editar Arquivo de Prova')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square"></i>
                <h6 class="mb-0 text-muted fw-normal">Editar Arquivos</h6>
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
                        <label for="year" class="form-label required">Ano de referência da prova:</label>
                        <input type="text" name="year" class="form-control @error('year') is-invalid @enderror"
                            id="year" placeholder="Ano em que a prova foi realizada" value="{{ $archive->year }}">
                        @error('year')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Arquivo relacionado --}}
                    <div class="form-group mb-3">
                        <label for="file" class="form-label required">Arquivo relacionado à prova:</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                            id="file" placeholder="Endereço">
                        @error('file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Gabarito relacionado --}}
                    <div class="form-group mb-3">
                        <label for="answer" class="form-label">Gabarito relacionado à prova (Se houver)</label>
                        <input type="file" name="answer"
                            class="form-control @error('answer') is-invalid @enderror" id="answer"
                            value="{{ old('answer') }}">
                        @error('answer')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Salvar
                    </button>
                    <a href="{{ route('admin.archives.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </a>
                </form>

            </div>
        </div>

    </div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>    
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/rules/archives/edit.js') }}"></script>
@endpush