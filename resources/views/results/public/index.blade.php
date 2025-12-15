@extends('layouts.home.master')

@push('metas')
    <meta name="description" content="Classificação geral do {{ config('app.name') }} {{ $calendar?->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Classificação Geral')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/results/styles.css') }}">
@endpush

@section('body-class')

@section('content')

    @include('home.navbar')

    <section id="results-wrapper">

        <div class="container">

            <h2 class="section-title text-center">
                {{ config('app.name') }} {{ $calendar->year }} | Classificação Geral
            </h2>

            <div class="row">

                <div class="col-lg-8 mx-auto mb-3 border-bottom">
                    <p>A <strong>Escola Municipal Dr. Leandro Franceschini</strong>, em conformidade com o item
                        <strong>5.10</strong> do <a href="{{ asset('storage/' . $notice->file) }}"
                            class="text-decoration-none" title="Leia o edital na íntegra" target="_blank">Edital</a> do
                        Processo Seletivo {{ $calendar->year }}, torna pública a classificação geral dos candidatos na prova
                        objetiva, adotando como critério de desempate a menor idade, conforme disposto no item
                        <strong>5.11</strong> do mesmo Edital.
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
                                        <td>{{ $result->social_name ? $result->social_name : $result->name }}</td>
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
                                    <tr>
                                        <td colspan="6" class="text-center">Nenhum resultado encontrado.</td>
                                    </tr>
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
    <script src="{{ asset('assets/filters/results/public.js') }}"></script>
@endpush
