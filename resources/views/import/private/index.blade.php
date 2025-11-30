@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Importar Notas')

@section('dash-content')

    <div class="container">

        {{-- Título da Página --}}
        <div class="mb-4 pb-2 border-bottom">
            <h4 class="mb-0 d-flex align-items-center">
                <i class="bi bi-upload me-2 text-primary"></i>
                Importar Notas
            </h4>
            <small class="text-muted">Envie um arquivo .xlsx contendo as notas dos candidatos.</small>
        </div>

        {{-- Card Principal --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <strong><i class="bi bi-file-earmark-excel me-2 text-success"></i>Envio da Planilha</strong>
            </div>

            <div class="card-body">

                <form id="import-results" action="{{ route('import.results') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Campo: Seleção de arquivo --}}
                    <div class="mb-4">
                        <label for="file" class="form-label fw-semibold">
                            Arquivo da planilha <span class="text-danger">*</span>
                        </label>

                        {{-- ALERTA --}}
                        <div class="alert alert-warning border-warning mb-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Atenção:</strong> o arquivo deve conter obrigatoriamente os seguintes
                            cabeçalhos, nesta ordem:
                            <br>
                            <code>inscription_id | user_id | user_cpf | user_name | user_birth | points</code>
                            <br>
                            <small class="text-muted">
                                Os nomes devem estar exatamente assim, sem acentos ou espaços extras.
                            </small>
                        </div>

                        {{-- Botão Modal --}}
                        <button type="button" class="btn btn-outline-primary btn-sm mb-3 float-end" data-bs-toggle="modal"
                            data-bs-target="#exampleSheet">
                            <i class="bi bi-search me-2"></i> Ver Modelo
                        </button>

                        {{-- Input File --}}
                        <input type="file" name="file" id="file"
                            accept=".xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            class="form-control @error('file') is-invalid @enderror" required>

                        <small class="form-text text-muted">
                            Formato aceito: <strong>.xlsx</strong> (máx. 10MB)
                        </small>

                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Botão Importar --}}
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">
                        <i class="bi bi-upload me-2"></i> Importar Notas
                    </button>

                </form>
            </div>
        </div>


        {{-- Modal com modelo da planilha --}}
        <div class="modal fade" id="exampleSheet" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content shadow">

                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title">
                            <i class="bi bi-file-earmark-excel me-2 text-success"></i>
                            Modelo de cabeçalho da planilha de importação
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <img src="{{ asset('assets/img/modelo_importacao_notas.png') }}" class="img-fluid rounded shadow-sm"
                            alt="Modelo de cabeçalho da planilha de importacao_notas">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Fechar
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/rules/admin/import/index.js') }}"></script>
@endpush
