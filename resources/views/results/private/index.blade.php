@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Classificação Geral')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/results/styles.css') }}">
@endpush

@section('dash-content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-list-ol me-2"></i>Classificação Geral</h5>
        </div>
    {{-- Verificar se $results é verdadeiro --}}
    @if ($results?->isNotEmpty())
        <div class="row">
            <div class="col mx-auto">            
                <form id="result-access-form" class="mb-3" action="{{ route('setAccessToResult') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="result" name="result"
                            onchange="confirmResultAccess(this)" {{ $status->result != 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="result">
                            Acesso ao resultado:
                            <span class="badge bg-{{ $status->result != 0 ? 'success' : 'danger' }} ms-2">
                                {{ $status->result != 0 ? 'Liberado' : 'Bloqueado' }}
                            </span>
                        </label>
                    </div>
                </form>
                {{-- Filtro e legenda --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <div class="mb-2 mb-md-0">
                        <label for="filter-status" class="form-label fw-semibold me-2">Filtrar por situação:</label>
                        <select id="filter-status" class="form-select form-select-sm w-auto d-inline-block">
                            <option value="all" selected>Todos</option>
                            <option value="classificado">Classificados</option>
                            <option value="empate">Empatados</option>
                            <option value="desclassificado">Desclassificados</option>
                        </select>
                    </div>

                    {{-- Legenda --}}
                    <div class="small text-center text-md-end">
                        <span class="badge bg-success me-2">Classificado</span>
                        <span class="badge bg-warning text-dark me-2">Empate</span>
                        <span class="badge bg-danger">Desclassificado</span>
                    </div>
                </div>
                {{-- Campo de busca --}}
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search"
                        placeholder="Pesquisar por nome ou inscrição" autocomplete="off">
                </div>                
                {{-- Tabela de resultados --}}
                <div class="table-responsive mt-3" style="max-height: 500px; overflow-y: auto;">
                    <table id="classification" class="table table-striped mb-0 caption-top">
                        <caption>{{ config('app.name') }} {{ $calendar->year }} - Lista de Classificação Geral
                        </caption>
                        <thead class="table-success">
                            <tr>
                                <th>Classificação</th>
                                <th>Inscrição</th>
                                <th>Nome</th>
                                <th>Nascimento</th>
                                <th>Nota</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody id="results" class="table-group-divider">
                            @foreach ($results as $index => $result)
                                @php
                                    $isDirectClassified = $result->ranking <= $limit;
                                    $isTieClassified = !$isDirectClassified && $result->score == $cutoffScore;
                                    $isClassified = $isDirectClassified || $isTieClassified;

                                    // Define a cor da linha conforme a situação
                                    $rowClass = $isDirectClassified
                                        ? 'table-success'
                                        : ($isTieClassified
                                            ? 'table-warning'
                                            : 'table-light');
                                @endphp
                                <tr class="{{ $rowClass }}"
                                    data-status="{{ $isDirectClassified ? 'classificado' : ($isTieClassified ? 'empate' : 'desclassificado') }}">
                                    <th scope="col">{{ $result->ranking }}º</th>
                                    <td>{{ $result->id }}</td>
                                    <th>{{ $result->social_name ? $result->social_name : $result->name }}</th>
                                    <td>{{ \Carbon\Carbon::parse($result->birth)->format('d/m/Y') }}</td>
                                    <td>{{ $result->score }}</td>
                                    <td>
                                        @if ($isDirectClassified)
                                            <span class="badge bg-success">CLASSIFICADO</span>
                                        @elseif ($isTieClassified)
                                            <span class="badge bg-warning text-dark">CLASSIFICADO</span>
                                            <small class="text-muted d-block">(Empate na nota de corte)</small>
                                        @else
                                            <span class="badge bg-danger">DESCLASSIFICADO</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
    @include('components.no-records', [
                'message' => 'Causas de problemas com as classificações:',
                'submessage' => 'Provavelmente nenhuma nota foi importada da planilha de classificação.',
                'action' => true,
                'actionMessage' =>
                    'Solução: Tente imporrtar uma nova planilha de classificação. Se o problema persistir, entre em contato com o suporte.',
            ])
    @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/filters/results.js') }}"></script>
    <script src="{{ asset('assets/swa/ranking/results.js') }}"></script>
@endpush