@extends('layouts.home.master')

@push('metas')
    <meta name="description" content="Área de provas anteriores do {{ config('app.name') }}">
@endpush

@section('page-title', config('app.name') . ' | Provas Anteriores')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/calendar/styles.css') }}">
@endpush

@section('body-class', 'bg-light')

@section('has-footer', 'has-footer')

@section('content')

    @include('guest.home.navbar')

    <section id="calendar">
        <div class="container">
            <div class="card shadow border border-0">
                <div class="card-header">
                    <i class="bi bi-exclamation-circle"></i> Informações do {{ config('app.name') }}
                    {{ $calendar?->year }}
                </div>
                <div class="card-body events pt-3">

                    <ul class="list-group list-group-flush">

                        @foreach ($calendar->events() as $event)
                            <li class="list-group-item">
                                <strong>{!! $event['icon'] !!} {{ $event['label'] }}</strong><br>

                                @if ($event['type'] === 'period')
                                    {{ $calendar->formatPeriod($event['start'], $event['end']) }}
                                @else
                                    {{ $calendar->formatDate($event['date']) }}
                                @endif
                            </li>
                        @endforeach

                    </ul>

                </div>
            </div>
        </div>
    </section>

    @include('guest.home.footer')

@endsection