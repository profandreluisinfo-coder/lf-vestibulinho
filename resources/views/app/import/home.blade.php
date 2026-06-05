@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF')

@section('content')

    <div class="container">

        {{-- Título da Página --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Importar Notas</h5>
        </div>

        <div class="progress mb-3 d-none" id="progress-wrapper">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" role="progressbar"
                style="width: 0%">0%</div>
        </div>

        @php
            $count = \App\Models\ExamResult::count('ranking');
        @endphp

        @if ($count > 0)
            <div class="alert alert-success d-flex flex-row align-items-center mb-3">
                <small class="text-success fw-semibold">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ $count }} notas importadas com sucesso! <a href="{{ route('app.results.index') }}">
                        Ver resultados →
                    </a>
                </small>
            </div>
        @endif


        {{-- ALERTA --}}
        <div class="alert alert-warning d-flex align-items-center bg-warning py-2 px-3 text-white">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            <strong>Atenção - leia antes de importar</strong>
        </div>

        <div class="mb-3 shadow-sm border border-warning-subtle rounded d-flex flex-column mb-3">

            <div class="p-3 mb-3">O arquivo deve conter obrigatoriamente os seguintes cabeçalhos, na seguinte
                ordem:
            </div>

            <div class="table-responsive">
                <table class="table table-sm">
                    <caption class="py-2 px-3 text-muted" style="font-size:12px;"><i
                            class="bi bi-info-circle me-2"></i>Modelo de cabeçalhos esperados</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">inscription_id</th>
                            <th scope="col">user_id</th>
                            <th scope="col">user_cpf</th>
                            <th scope="col">user_name</th>
                            <th scope="col">user_birth</th>
                            <th scope="col">points</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr>
                            <td>1</td>
                            <td>10</td>
                            <td>123.456.789-00</td>
                            <td>Maria Aparecida</td>
                            <td>01/01/2010</td>
                            <td>58</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="list-group gap-2 px-3 py-3">
                <span class="fw-semibold me-1">Observações:</span>
                <span><i class="bi bi-check2-circle text-success me-2"></i>Os cabeçalhos devem
                    estar sem acentos ou espaços extras.</span>
                <span><i class="bi bi-check2-circle text-success me-2"></i>Cada importação
                    substituirá os dados anteriores.</span>
                <span><i class="bi bi-check2-circle text-success me-2"></i>Formato aceito:
                    .xlsx (máx. 10MB)</span>
            </div>

        </div>

        <form id="import-results" class="mb-3" action="{{ route('app.import.home') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="file" class="form-label fw-semibold">
                    Selecione o arquivo <span class="text-danger">*</span>
                </label>

                <input type="file" name="file" id="file" class="form-control" required>
            </div>

            <div class="progress mb-3 d-none" id="progress-wrapper">
                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" role="progressbar"
                    style="width: 0%">0%</div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">
                <i class="bi bi-upload me-2"></i> Importar
            </button>

        </form>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/rules/admin/import/index.js') }}"></script>
@endpush
