@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF')

@push('styles')
<style>
        .edit-section-label {
            font-size: var(--font-size-sm);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-muted);
            margin-bottom: 0.75rem;
        }

        .edit-divider {
            border-color: var(--border-color);
            margin: 0 0 1.5rem 0;
            opacity: 1;
        }

        .form-label {
            font-size: var(--font-size-sm);
            font-weight: 600;
            color: var(--text-dark);
        }
    </style>
@endpush

@section('content')
    @php
        $event = $process?->latestEvent;
    @endphp

    <div class="container">

        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-calendar4-week text-muted"></i>
            <h6 class="mb-0 text-muted fw-normal">Editar Processo Seletivo — Vestibulinho</h6>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0" style="box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
                    <div class="card-body p-4">
                        <form id="form-calendar" action="{{ route('admin.process.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Seção: Informações Básicas --}}
                            <div class="edit-section mb-4">
                                <p class="edit-section-label">Informações Básicas</p>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="year" class="form-label">Ano de Referência</label>
                                        <input type="number" class="form-control @error('year') is-invalid @enderror"
                                            id="year" name="year" min="2027"
                                            value="{{ old('year', $process?->year ?? '') }}">
                                        @error('year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="edit-divider">

                            {{-- Seção: Edital --}}
                            <div class="edit-section mb-4">
                                <p class="edit-section-label">Edital do Processo Seletivo</p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="edital" class="form-label required">Arquivo relacionado</label>
                                        <input type="file" name="edital"
                                            class="form-control @error('edital') is-invalid @enderror" id="edital">
                                        @error('edital')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="edit-divider">

                            {{-- Seção: Período de Inscrições --}}
                            <div class="edit-section mb-4">
                                <p class="edit-section-label">Período de Inscrições</p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="start" class="form-label">Data de Início</label>
                                        <input type="date" class="form-control @error('start') is-invalid @enderror"
                                            id="start" name="start"
                                            value="{{ old('start', $event?->start?->format('Y-m-d') ?? '') }}">
                                        @error('start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end" class="form-label">Data de Término</label>
                                        <input type="date" class="form-control @error('end') is-invalid @enderror"
                                            id="end" name="end"
                                            value="{{ old('end', $event?->end?->format('Y-m-d') ?? '') }}">
                                        @error('end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="edit-divider">

                            {{-- Seção: Aplicação das Provas --}}
                            <div class="edit-section mb-4">
                                <p class="edit-section-label">Aplicação das Provas</p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="location_publish" class="form-label">Divulgação dos Locais de Prova</label>
                                        <input type="date"
                                            class="form-control @error('location_publish') is-invalid @enderror"
                                            id="location_publish" name="location_publish"
                                            value="{{ old('location_publish', $event?->location_publish?->format('Y-m-d') ?? '') }}">
                                        @error('exam_location_publish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exam_date" class="form-label">Data de Aplicação das Provas</label>
                                        <input type="date" class="form-control @error('exam_date') is-invalid @enderror"
                                            id="exam_date" name="exam_date"
                                            value="{{ old('exam_date', $event?->exam_date?->format('Y-m-d') ?? '') }}">
                                        @error('exam_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="answer_publish" class="form-label">Divulgação do Gabarito</label>
                                        <input type="date"
                                            class="form-control @error('answer_publish') is-invalid @enderror"
                                            id="answer_publish" name="answer_publish"
                                            value="{{ old('answer_publish', $event?->answer_publish?->format('Y-m-d') ?? '') }}">
                                        @error('answer_publish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="edit-divider">

                            {{-- Seção: Revisão de Questões --}}
                            <div class="edit-section mb-4">
                                <p class="edit-section-label">Revisão de Questões</p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="revision_start" class="form-label">Início do Prazo para Revisão</label>
                                        <input type="date"
                                            class="form-control @error('revision_start') is-invalid @enderror"
                                            id="revision_start" name="revision_start"
                                            value="{{ old('revision_start', $event?->revision_start?->format('Y-m-d') ?? '') }}">
                                        @error('revision_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="revision_end" class="form-label">Término do Prazo para Revisão</label>
                                        <input type="date"
                                            class="form-control @error('revision_end') is-invalid @enderror"
                                            id="revision_end" name="revision_end"
                                            value="{{ old('revision_end', $event?->revision_end?->format('Y-m-d') ?? '') }}">
                                        @error('revision_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="edit-divider">

                            {{-- Seção: Resultados --}}
                            <div class="edit-section mb-4">
                                <p class="edit-section-label">Resultados</p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="result_publish" class="form-label">Divulgação da Classificação Final</label>
                                        <input type="date"
                                            class="form-control @error('result_publish') is-invalid @enderror"
                                            id="result_publish" name="result_publish"
                                            value="{{ old('result_publish', $event?->result_publish?->format('Y-m-d') ?? '') }}">
                                        @error('result_publish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="edit-divider">

                            {{-- Seção: Matrículas --}}
                            <div class="edit-section mb-4">
                                <p class="edit-section-label">Matrículas</p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="enrol_start" class="form-label">Cronograma de Matrícula — 1ª Chamada</label>
                                        <input type="date"
                                            class="form-control @error('enrol_start') is-invalid @enderror"
                                            id="enrol_start" name="enrol_start"
                                            value="{{ old('enrol_start', $event?->enrol_start?->format('Y-m-d') ?? '') }}">
                                        @error('enrol_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="enrol_remaining" class="form-label">Cronograma de Vagas Remanescentes</label>
                                        <input type="date"
                                            class="form-control @error('enrol_remaining') is-invalid @enderror"
                                            id="enrol_remaining" name="enrol_remaining"
                                            value="{{ old('enrol_remaining', $event?->enrol_remaining?->format('Y-m-d') ?? '') }}">
                                        @error('enrol_remaining')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Botões --}}
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle"></i>Salvar
                                </button>
                                <a href="{{ route('admin.process.show') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i>Cancelar
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
    <script src="{{ asset('assets/js/admin/process/edit.js') }}"></script>
@endpush