@extends('layouts.site')

@section('title', 'Convocação para Matrícula')

@push('styles')
    <style>
        .calls-accordion .accordion-item {
            border: 1px solid #dce5ee;
            border-radius: .85rem;
            overflow: hidden;
            box-shadow: 0 .25rem .8rem rgb(25 55 85 / 6%);
        }

        .calls-accordion .accordion-button {
            gap: 1rem;
            padding: 1rem 1.15rem;
        }

        .calls-accordion .accordion-button:not(.collapsed) {
            color: #123f62;
            background: #f0f7fc;
            box-shadow: none;
        }

        .call-number-badge {
            display: inline-flex;
            min-width: 2.6rem;
            height: 2.6rem;
            align-items: center;
            justify-content: center;
            border-radius: .7rem;
            color: #fff;
            background: #176b91;
            font-weight: 700;
            flex: 0 0 auto;
        }

        .call-summary {
            display: flex;
            flex: 1 1 auto;
            align-items: center;
            justify-content: space-between;
            gap: .5rem 1rem;
            flex-wrap: wrap;
        }

        .call-summary-date {
            color: #5c6b78;
            font-size: .92rem;
            font-weight: 400;
        }

        .call-search {
            max-width: 34rem;
        }

        .calls-table th,
        .calls-table td {
            text-align: center;
            vertical-align: middle;
        }

        @media (max-width: 575.98px) {
            .calls-accordion .accordion-button {
                gap: .7rem;
                padding: .8rem;
            }

            .call-number-badge {
                min-width: 2.25rem;
                height: 2.25rem;
            }

            .call-summary {
                display: grid;
                gap: .2rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $calls = $calls->sortBy(function ($convocados) {
            $callList = $convocados->first()?->callList;

            return [
                (int) ($callList?->number ?? 0),
                $callList?->date?->toDateString() ?? '',
                $callList?->time?->format('H:i:s') ?? '',
                (int) ($callList?->id ?? 0),
            ];
        });
    @endphp

    <section class="breadcrumb-section">
        <div class="container-lg">
            <nav class="breadcrumb-nav" aria-label="Navegação estrutural">
                <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
                <span> / </span>
                <a href="{{ route('site.faqs.index') }}" class="breadcrumb-link">
                    Vestibulinho LF {{ $process?->year ?? '' }}
                </a>
                <span> / </span>
                <span aria-current="page">Convocação para Matrícula</span>
            </nav>
        </div>
    </section>

    <section class="calls-wrapper p-0 mb-5">
        <div class="container">
            <h2 class="section-title text-center">
                Vestibulinho LF {{ $process?->year }} - Convocação para Matrícula
            </h2>

            @if ($calls->isNotEmpty())
                <p class="text-center text-muted mb-4">
                    {{ $calls->count() }} {{ $calls->count() === 1 ? 'chamada publicada' : 'chamadas publicadas' }}
                    <span class="d-block small">Selecione uma chamada para consultar os convocados.</span>
                </p>

                <div class="accordion calls-accordion d-grid gap-3" id="callsAccordion">
                    @foreach ($calls as $key => $convocados)
                        @php
                            [$callListId, $callNumber, $date, $time] = explode('|', $key);
                            $panelId = 'call-panel-' . $callListId;
                            $tableId = 'calls-' . $callListId;
                        @endphp

                        <article class="accordion-item">
                            <h3 class="accordion-header" id="heading-{{ $callListId }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#{{ $panelId }}" aria-expanded="false"
                                    aria-controls="{{ $panelId }}">
                                    <span class="call-number-badge" aria-hidden="true">{{ $callNumber }}</span>
                                    <span class="call-summary">
                                        <span>
                                            <strong>Chamada nº {{ $callNumber }}</strong>
                                            <span class="call-summary-date d-block">
                                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} às
                                                {{ \Carbon\Carbon::parse($time)->format('H:i') }}
                                            </span>
                                        </span>
                                        <span class="badge text-bg-light border">
                                            {{ $convocados->count() }}
                                            {{ $convocados->count() === 1 ? 'convocado' : 'convocados' }}
                                        </span>
                                    </span>
                                </button>
                            </h3>

                            <div id="{{ $panelId }}" class="accordion-collapse collapse"
                                aria-labelledby="heading-{{ $callListId }}" data-bs-parent="#callsAccordion">
                                <div class="accordion-body">
                                    <div class="input-group input-group-sm call-search mb-3">
                                        <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                                        <input type="search" class="form-control search-input"
                                            placeholder="Pesquisar classificação ou inscrição..."
                                            aria-label="Pesquisar por classificação ou número de inscrição nesta chamada"
                                            data-table="{{ $tableId }}" autocomplete="off">
                                    </div>

                                    <div class="table-responsive">
                                        <table id="{{ $tableId }}" class="table table-sm table-striped mb-0 calls-table">
                                            <thead class="table-success">
                                                <tr>
                                                    <th scope="col">Classificação</th>
                                                    <th scope="col">Número de inscrição</th>
                                                    <th scope="col">Pontuação</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-group-divider">
                                                @foreach ($convocados as $call)
                                                    <tr>
                                                        <td>{{ $call->result->ranking }}º</td>
                                                        <td>{{ $call->result->inscription_id }}</td>
                                                        <td>{{ $call->result->score }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center" role="status">
                    Ainda não há chamadas publicadas para este processo seletivo.
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/site/calls/index.js') }}"></script>
@endpush
