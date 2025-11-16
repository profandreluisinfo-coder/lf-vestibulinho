@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Local da Prova')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/exam/card.css') }}">
@endpush

@section('dash-content')

    @if ($exam)
        <div class="card m-3 border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-card-header text-light">
                <i class="bi bi-geo-alt-fill"></i>
                <strong>Informações da Prova</strong>
            </div>

            <div class="card-body bg-light-subtle">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <p class="mb-1"><i class="bi bi-person me-2"></i>
                            <strong>{{ auth()->user()->social_name ? auth()->user()->social_name : auth()->user()->name }}</strong>
                        </p>
                        <p class="mb-1"><i class="bi bi-building me-2"></i> <strong>Local:</strong>
                            {{ $exam->location_name }}</p>
                        <p class="mb-1"><i class="bi bi-door-open me-2"></i> <strong>Sala:</strong>
                            {{ $exam->room_number }}</p>
                        <p class="mb-1"><i class="bi bi-calendar-event me-2"></i> <strong>Data:</strong>
                            {{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}</p>
                        <p class="mb-1"><i class="bi bi-clock me-2"></i> <strong>Horário:</strong>
                            {{ \Carbon\Carbon::parse($exam->exam_time)->format('H:i') }}</p>
                        @if ($exam->pne)
                            <div class="alert alert-warning mt-3 p-2">
                                <i class="bi bi-universal-access-circle"></i>
                                Sala de Atendimento Especializado.
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <strong><i class="bi bi-info-circle-fill"></i> Instruções:</strong>
                            <ul class="mb-0 small">
                                <li>Chegue com <strong>30 minutos de antecedência</strong>.</li>
                                <li>Leve documento com foto e caneta azul ou preta.</li>
                                <li class="text-danger fw-bold">Não é permitido usar dispositivos eletrônicos durante a prova.</li>
                            </ul>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <a href="{{ route('user.card.pdf') }}" class="btn btn-outline-primary">
                                <i class="bi bi-download"></i> Baixar PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @else
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle-fill"></i> Nenhuma alocação encontrada no momento.
        </div>
    @endif
@endsection