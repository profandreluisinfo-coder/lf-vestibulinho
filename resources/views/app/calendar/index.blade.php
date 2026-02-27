@extends('layouts.admin.master')

@section('page-title', config('app.name') . ' | Calendário')

@section('dash-content')

    @php
        $notice = \App\Models\Notice::first();
        //$calendar = \App\Models\Calendar::first() ?? new \App\Models\Calendar();
    @endphp

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h5 class="mb-0">
                <i class="bi bi-calendar4-week me-2"></i>Calendário
            </h5>

            <a href="{{ route('app.calendar.edit', $calendar->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square me-1"></i>
                {{ $calendar->exists() ? 'Editar' : 'Novo' }}
            </a>
        </div>

        @if ($calendar->exists())

            <div class="row g-4 mb-4">

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
                            <p class="mb-0">{{ Carbon\Carbon::parse($calendar->exam_location_publish)->format('d/m/Y') }}
                            </p>
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
                            <p class="mb-0">{{ Carbon\Carbon::parse($calendar->answer_key_publish)->format('d/m/Y') }}
                            </p>
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
                            <p class="mb-0">{{ Carbon\Carbon::parse($calendar->final_result_publish)->format('d/m/Y') }}
                            </p>
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

            @if ($calendar->exists())

            <form id="calendar-access-form" action="{{ route('app.system.publish.calendar') }}" method="POST">
                @csrf
                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" id="calendar" name="calendar"
                        onchange="confirmCalendarAccess(this)" {{ $settings->calendar != 0 ? 'checked' : '' }}>
                    <label class="form-check-label" for="calendar">                        
                        <span class="badge bg-{{ $settings->calendar != 0 ? 'success' : 'danger' }} ms-2">
                            {!! $settings->calendar != 0 ? '<i class="bi bi-unlock"></i> Calendário Liberado' : '<i class="bi bi-lock"></i> Calendário Bloqueado' !!}
                        </span>
                    </label>
                </div>
            </form>

            <p class="text-muted mt-3">
                @if ($settings->calendar != 0)
                <span class="text-success">Os candidatos podem visualizar o calendário do vestibulinho.</span>
                @else
                <span class="text-danger">Os candidatos não podem visualizar o calendário do vestibulinho.</span>
                @endif
            </p>
            
            @endif

        @else

            <p class="text-danger">
                <i class="bi bi-info-circle me-1"></i> Nenhum calendário cadastrado.
            </p>

        @endif
        
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/swa/calendar/publish.js') }}"></script>
@endpush
