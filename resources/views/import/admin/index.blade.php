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
        </div>

        <div class="progress mb-3 d-none" id="progress-wrapper">
            <div class="progress-bar progress-bar-striped progress-bar-animated"
                id="progress-bar"
                role="progressbar"
                style="width: 0%">0%</div>
        </div>

        @php
            $count = \App\Models\ExamResult::count('ranking');
        @endphp
        @if ($count > 0)
        <div class="d-flex flex-row align-items-center mb-3">
            <small class="text-success fw-semibold">
                <i class="bi bi-check-circle me-2"></i>
                {{ $count }} notas importadas com sucesso! Clique no <a href="{{ route('result.index') }}">
                aqui
            </a> para ver os resultados.
            </small>
        </div>
        @endif

        {{-- Card Principal --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <strong><i class="bi bi-file-earmark-excel me-2 text-success"></i>Planilha de Notas</strong>
            </div>

            <div class="card-body">
                <div class="mb-5">
                    {{-- ALERTA --}}
                    <div class="mb-3 shadow-sm d-flex flex-column mb-3">
                        <div class="d-flex align-items-center bg-warning py-2 px-3 text-white">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <strong>Atenção! </strong>
                        </div>

                        <div class="p-3 mb-3">O arquivo deve conter obrigatoriamente os seguintes cabeçalhos, na seguinte
                            ordem:
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-sm">
                                <caption class="py-2 px-3 text-muted" style="font-size:12px;"><i
                                        class="bi bi-info-circle me-2"></i>Modelo de cabeçalhos</caption>
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

                        <div class="d-flex flex-column gap-2 py-2 px-3 bg-light">
                            <span class="fw-semibold me-1">Observações:</span>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item p-2"><i class="bi bi-check2-circle me-2"></i>Os cabeçalhos devem
                                    estar sem acentos ou espaços extras.</li>
                                <li class="list-group-item p-2"><i class="bi bi-check2-circle me-2"></i>Cada importação
                                    substituirá os dados anteriores.</li>
                                <li class="list-group-item p-2"><i class="bi bi-check2-circle me-2"></i>Formato aceito:
                                    .xlsx (máx. 10MB)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form id="import-results" class="mb-3"
                    action="{{ route('import.results') }}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="file" class="form-label fw-semibold">
                            Selecione o arquivo <span class="text-danger">*</span>
                        </label>

                        <input type="file" name="file" id="file"
                            class="form-control"
                            required>
                    </div>

                    <div class="progress mb-3 d-none" id="progress-wrapper">
                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                            id="progress-bar"
                            role="progressbar"
                            style="width: 0%">0%</div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">
                        <i class="bi bi-upload me-2"></i> Importar
                    </button>

                </form>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/rules/admin/import/index.js') }}"></script>
@endpush
