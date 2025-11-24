@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' | Calendário')

@section('dash-content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="bi bi-calendar4-week me-2"></i>Calendário do Processo Seletivo
        </h5>

        @php
            $calendar = \App\Models\Calendar::first();
        @endphp

        <a href="{{ route('calendar.edit', $calendar?->id) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil-square me-1"></i>
            {{ $calendar ? 'Editar Calendário' : 'Cadastrar Calendário' }}
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($calendar)
        <div class="row g-4">
            {{-- Card de Período de Inscrições --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary">
                            <i class="bi bi-pencil-square me-2"></i>Inscrições
                        </h6>
                        <p class="mb-0">
                            {{ Carbon\Carbon::parse($calendar->inscription_start)->format('d/m/Y') }}
                            <span class="text-muted">até</span>
                            {{ Carbon\Carbon::parse($calendar->inscription_end)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Locais de Prova --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-info">
                            <i class="bi bi-geo-alt me-2"></i>Locais de Prova
                        </h6>
                        <p class="mb-0">{{ Carbon\Carbon::parse($calendar->exam_location_publish)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Aplicação das Provas --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-danger">
                            <i class="bi bi-journal-text me-2"></i>Aplicação das Provas
                        </h6>
                        <p class="mb-0">{{ Carbon\Carbon::parse($calendar->exam_date)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Divulgação do Gabarito --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-success">
                            <i class="bi bi-check2-circle me-2"></i>Divulgação do Gabarito
                        </h6>
                        <p class="mb-0">{{ Carbon\Carbon::parse($calendar->answer_key_publish)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Revisão das Questões --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-warning">
                            <i class="bi bi-search me-2"></i>Revisão das Questões
                        </h6>
                        <p class="mb-0">
                            {{ Carbon\Carbon::parse($calendar->exam_revision_start)->format('d/m/Y') }}
                            <span class="text-muted">até</span>
                            {{ Carbon\Carbon::parse($calendar->exam_revision_end)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Resultado Final --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark">
                            <i class="bi bi-trophy me-2"></i>Resultado Final
                        </h6>
                        <p class="mb-0">{{ Carbon\Carbon::parse($calendar->final_result_publish)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Chamamento 1ª Chamada --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary">
                            <i class="bi bi-megaphone me-2"></i>1ª Chamada
                        </h6>
                        <p class="mb-0">{{ Carbon\Carbon::parse($calendar->enrollment_start)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Chamamento de Vagas Remanescentes --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-secondary">
                            <i class="bi bi-people me-2"></i>Vagas Remanescentes
                        </h6>
                        <p class="mb-0">{{ Carbon\Carbon::parse($calendar->enrollment_end)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info shadow-sm text-center py-4">
            <i class="bi bi-info-circle me-2"></i>
            Nenhum calendário cadastrado até o momento.<br>
            <small class="text-muted">Clique no botão acima para iniciar o cadastro.</small>
        </div>
    @endif
</div>
@endsection