@extends('layouts.home.master')

@push('metas')
    <meta name="description" content="Área de perguntas frequentes sobre {{ config('app.name') }} {{ $calendar?->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Convocação para Matrícula')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/calls/styles.css') }}">
    <style>
        .latest-call-icon {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: .4;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
@endpush

@section('body-class', 'bg-light')

@section('has-footer', 'has-footer')

@section('content')
    @php
        $latestKey = collect($calls->keys())
            ->sortByDesc(function ($key) {
                [, $date, $time] = explode('|', $key);

                $date = \Carbon\Carbon::parse($date)->format('Y-m-d');
                $time = \Carbon\Carbon::parse($time)->format('H:i:s');

                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "$date $time");
            })
            ->first();
        $calls = $calls->sortByDesc(fn($_, $key) => $key === $latestKey);

    @endphp


    @include('guest.home.navbar')

    <section id="faq" class="my-5 py-5">

        <div class="container">

            <h2 class="section-title text-center">
                {{ config('app.name') }} {{ $calendar->year }} | Convocação para Matrícula
            </h2>

            <div class="row">
                <div class="col-lg-8 mx-auto">

                    <p>Total de chamadas: <strong>{{ $calls->count() }}</strong></p>

                    @if ($calls->isNotEmpty())

                        @foreach ($calls as $key => $convocados)
                            @php
                                $isLatest = $key === $latestKey;
                                [$callNumber, $date, $time] = explode('|', $key);
                                $tableId = 'calls-' . $callNumber . '-' . $loop->index;
                            @endphp

                            <div class="pt-5 {{ $isLatest ? 'shadow-lg p-3 bg-white rounded' : '' }}">


                                <h4 class="text-center">
                                    Chamada nº {{ $callNumber }}

                                    @if ($isLatest)
                                        <span class="badge bg-danger ms-2">
                                            <i class="bi bi-star-fill latest-call-icon"></i> Mais recente
                                        </span>
                                    @endif

                                    <br>

                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} às
                                        {{ \Carbon\Carbon::parse($time)->format('H:i') }}
                                    </small>
                                </h4>

                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control search-input"
                                        placeholder="Pesquisar nesta chamada..." data-table="{{ $tableId }}"
                                        autocomplete="off">
                                </div>
                                
                                <div
                                    class="table-responsive mt-3 calls {{ $isLatest ? 'border border-3 border-danger rounded' : '' }}">

                                    <table id="{{ $tableId }}" class="table-sm table-striped mb-0 table caption-top">

                                        <caption class="bg-warning text-light px-4">
                                            <strong>
                                                {{ config('app.name') }} {{ $calendar->year }}
                                                - Chamada nº {{ $callNumber }}
                                                ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                                às {{ \Carbon\Carbon::parse($time)->format('H:i') }})
                                            </strong>
                                        </caption>

                                        <thead class="table-success">
                                            <tr>
                                                <th>Inscrição</th>
                                                <th>Classificação</th>
                                                <th>Nome</th>
                                                <th>Data</th>
                                                <th>Hora</th>
                                            </tr>
                                        </thead>

                                        <tbody class="table-group-divider">

                                            @forelse ($convocados as $call)
                                                <tr>
                                                    <td>{{ $call->examResult->inscription->id }}</td>
                                                    <td>{{ $call->examResult->ranking }}º</td>
                                                    <td>
                                                        {{ $call->examResult->inscription->user->social_name ?? $call->examResult->inscription->user->name }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($call->date)->format('d/m/Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($call->time)->format('H:i') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        Nenhum resultado encontrado.
                                                    </td>
                                                </tr>
                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>
                            </div>

                        @endforeach
                    @else
                        <div class="alert alert-info text-center">
                            Nenhuma chamada encontrada.
                        </div>

                    @endif

                </div>
            </div>

        </div>

    </section>

    @include('guest.home.footer')

@endsection

@push('scripts')
    <script src="{{ asset('assets/filters/calls.js') }}"></script>
@endpush
