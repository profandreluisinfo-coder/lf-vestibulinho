@extends('layouts.guest.master')

@push('metas')
    <meta name="description" content="Classificação geral do {{ config('app.name') }} {{ $calendar?->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Classificação Geral')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/results/styles.css') }}">
@endpush

@section('body-class', 'bg-light')

@section('content')

    @include('guest.home.navbar')

    <section id="results-wrapper">

        <div class="container">

            <h2 class="section-title text-center">
                Classificação Geral
            </h2>

            <div class="row">

                <div id="results-disclaimer" class="col-lg-8 mx-auto mb-3 border-bottom">
                    <p>A <strong>Escola Municipal Dr. Leandro Franceschini</strong> torna pública a classificação geral dos
                        candidatos na prova
                        objetiva, adotando como critério de desempate a menor idade.
                    </p>
                </div>

                <div class="col-lg-8 mx-auto">

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
                    <div class="search-wrapper">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Pesquisar por nome ou inscrição" autocomplete="off">
                        </div>
                    </div>

                    {{-- Contador de resultados --}}
                    <div id="results-counter" class="text-end small mb-2 mt-2"></div>

                    {{-- Tabela de resultados --}}
                    <div class="table-responsive mt-3" style="max-height: 500px; overflow-y: auto;">
                        <table id="classification" class="table-sm table-striped mb-0 table caption-top">
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

                            <tbody id="results-tbody" class="table-group-divider">
                                @forelse ($results as $index => $result)
                                    @php
                                        $isDirectClassified = $result->ranking <= $limit;
                                        $isTieClassified = !$isDirectClassified && $result->score == $cutoffScore;
                                        $isClassified = $isDirectClassified || $isTieClassified;
                                    @endphp
                                    <tr
                                        data-status="{{ $isDirectClassified ? 'classificado' : ($isTieClassified ? 'empate' : 'desclassificado') }}">
                                        <th scope="col">{{ $result->ranking }}º</th>
                                        <td>{{ $result->id }}</td>
                                        <td>{{ $result->authorization_accepted == 1 ? $result->social_name : $result->name }}
                                        </td>
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
                                @empty
                                    <div id="meu-alert"
                                        class="alert alert-info d-flex align-items-start border-0 rounded-3 p-3"
                                        role="alert">

                                        <div class="me-3 fs-3" aria-hidden="true">
                                            <i class="bi bi-info-circle-fill"></i>
                                        </div>

                                        <div class="flex-grow-1">
                                            <h5 class="alert-heading mb-2">Informação Importante</h5>
                                            <p class="mb-0">
                                                A tabela de classificação geral ainda está vazia.
                                            </p>
                                            <p class="mb-0 mt-2 small opacity-75">
                                                Em caso de dúvidas, entre em contato com o suporte técnico.
                                            </p>
                                        </div>

                                        <button type="button" class="btn-close ms-3" aria-label="Fechar alerta"
                                            data-bs-dismiss="alert"></button>

                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </section>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/filters/results/public.js') }}"></script>
@endpush
