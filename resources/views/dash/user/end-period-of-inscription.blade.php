@extends('layouts.user.master')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Área do Candidato')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/user/inscription.css') }}">
@endpush

@section('dash-content')

    <div class="alert alert-danger border-0 rounded-3">
        <h6 class="fw-semibold mb-2">Prezado(a) Candidato(a),</h6>
        <p class="mb-0">
            <i class="bi bi-exclamation-circle me-1"></i>
            O período de inscrições para o {{ config('app.name') }} {{ $calendar->year }} foi encerrado em
            <strong>{{ $calendar?->inscription_end->format('d/m/Y') }}</strong>.
        </p>
    </div>

    <div class="text-center mt-4 pt-3 border-top">
        <p class="text-muted small mb-0">
            <strong>E. M. Dr. Leandro Franceschini</strong><br>
            Prefeitura Municipal de Sumaré
        </p>
    </div>

@endsection
