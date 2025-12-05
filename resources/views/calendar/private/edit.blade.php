@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' | Calendário ' . $calendar->year)

@section('dash-content')

    {{-- @php
    $calendar = \App\Models\Calendar::first() ?? new \App\Models\Calendar();    
    @endphp --}}
    
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-calendar4-week me-2"></i>Editar
            </h5>
        </div>

        <div class="row">

            <div class="col-12">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-4">

                        {{-- @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif --}}

                        {{-- <form method="post" action="{{ route('calendar.index') }}"> --}}
                        <form id="form-calendar" action="{{ route('calendar.save') }}" method="POST">
                            @csrf

                            {{-- Seção: Informações Básicas --}}
                            <div class="border-start border-primary border-4 ps-3 mb-4">

                                <h5 class="text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Informações Básicas</h5>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="year" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar3 me-1"></i>Ano do Processo Seletivo
                                        </label>
                                        <input type="number" class="form-control @error('year') is-invalid @enderror"
                                            id="year" name="year" min="2026"
                                            value="{{ old('year', $calendar->year ?? '') }}">
                                        @error('year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                            </div>

                            {{-- Seção: Período de Inscrições --}}
                            <div class="border-start border-success border-4 ps-3 mb-4">

                                <h5 class="text-success mb-3"><i class="bi bi-pencil-square me-2"></i>Período de Inscrições
                                </h5>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="inscription_start" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-check me-1"></i>Data de Início
                                        </label>
                                        <input type="date"
                                            class="form-control @error('inscription_start') is-invalid @enderror"
                                            id="inscription_start" name="inscription_start"
                                            value="{{ old('inscription_start', \Carbon\Carbon::parse($calendar->inscription_start)->format('Y-m-d') ?? '') }}">
                                        @error('inscription_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="inscription_end" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-x me-1"></i>Data de Término
                                        </label>
                                        <input type="date"
                                            class="form-control @error('inscription_end') is-invalid @enderror"
                                            id="inscription_end" name="inscription_end"
                                            value="{{ old('inscription_end', \Carbon\Carbon::parse($calendar->inscription_end)->format('Y-m-d') ?? '') }}">
                                        @error('inscription_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                            </div>

                            {{-- Seção: Aplicação das Provas --}}
                            <div class="border-start border-warning border-4 ps-3 mb-4">

                                <h5 class="text-warning mb-3"><i class="bi bi-file-earmark-text me-2"></i>Aplicação das
                                    Provas</h5>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="exam_location_publish"
                                            class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-geo-alt me-1"></i>Divulgação dos Locais de Prova
                                        </label>
                                        <input type="date"
                                            class="form-control @error('exam_location_publish') is-invalid @enderror"
                                            id="exam_location_publish" name="exam_location_publish"
                                            value="{{ old('exam_location_publish', \Carbon\Carbon::parse($calendar->exam_location_publish)->format('Y-m-d') ?? '') }}">
                                        @error('exam_location_publish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exam_date" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-event me-1"></i>Data de Aplicação das Provas
                                        </label>
                                        <input type="date" class="form-control @error('exam_date') is-invalid @enderror"
                                            id="exam_date" name="exam_date"
                                            value="{{ old('exam_date', \Carbon\Carbon::parse($calendar->exam_date)->format('Y-m-d') ?? '') }}">
                                        @error('exam_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="answer_key_publish" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-key me-1"></i>Divulgação do Gabarito
                                        </label>
                                        <input type="date"
                                            class="form-control @error('answer_key_publish') is-invalid @enderror"
                                            id="answer_key_publish" name="answer_key_publish"
                                            value="{{ old('answer_key_publish', \Carbon\Carbon::parse($calendar->answer_key_publish)->format('Y-m-d') ?? '') }}">
                                        @error('answer_key_publish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                            </div>

                            {{-- Seção: Revisão de Questões --}}
                            <div class="border-start border-info border-4 ps-3 mb-4">

                                <h5 class="text-info mb-3"><i class="bi bi-arrow-repeat me-2"></i>Revisão de Questões</h5>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="exam_revision_start"
                                            class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-check me-1"></i>Início do Prazo para Revisão
                                        </label>
                                        <input type="date"
                                            class="form-control @error('exam_revision_start') is-invalid @enderror"
                                            id="exam_revision_start" name="exam_revision_start"
                                            value="{{ old('exam_revision_start', \Carbon\Carbon::parse($calendar->exam_revision_start)->format('Y-m-d') ?? '') }}">
                                        @error('exam_revision_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exam_revision_end"
                                            class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-x me-1"></i>Término do Prazo para Revisão
                                        </label>
                                        <input type="date"
                                            class="form-control @error('exam_revision_end') is-invalid @enderror"
                                            id="exam_revision_end" name="exam_revision_end"
                                            value="{{ old('exam_revision_end', \Carbon\Carbon::parse($calendar->exam_revision_end)->format('Y-m-d') ?? '') }}">
                                        @error('exam_revision_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Seção: Resultados e Matrículas --}}
                            <div class="border-start border-danger border-4 ps-3 mb-4">
                                <h5 class="text-danger mb-3"><i class="bi bi-trophy me-2"></i>Resultados e Matrículas</h5>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="final_result_publish"
                                            class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-list-ol me-1"></i>Divulgação da Classificação Final
                                        </label>
                                        <input type="date"
                                            class="form-control @error('final_result_publish') is-invalid @enderror"
                                            id="final_result_publish" name="final_result_publish"
                                            value="{{ old('final_result_publish', \Carbon\Carbon::parse($calendar->final_result_publish)->format('Y-m-d') ?? '') }}">
                                        @error('final_result_publish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="enrollment_start" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-person-check me-1"></i>Cronograma de Matrícula - 1ª Chamada
                                        </label>
                                        <input type="date"
                                            class="form-control @error('enrollment_start') is-invalid @enderror"
                                            id="enrollment_start" name="enrollment_start"
                                            value="{{ old('enrollment_start', \Carbon\Carbon::parse($calendar->enrollment_start)->format('Y-m-d') ?? '') }}">
                                        @error('enrollment_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="enrollment_end" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-people me-1"></i>Cronograma de Vagas Remanescentes
                                        </label>
                                        <input type="date"
                                            class="form-control @error('enrollment_end') is-invalid @enderror"
                                            id="enrollment_end" name="enrollment_end"
                                            value="{{ old('enrollment_end', \Carbon\Carbon::parse($calendar->enrollment_end)->format('Y-m-d') ?? '') }}">
                                        @error('enrollment_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                            </div>

                            {{-- Botão de Ação --}}
                            <div class="d-flex gap-2 mt-4 pt-3 border-top">

                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle me-1"></i>Salvar
                                </button>

                                <a href="{{ route('calendar.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-x-circle me-1"></i>Cancelar
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/rules/admin/calendar/edit.js') }}"></script>
@endpush