@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF')

@section('content')
    @php 
        $event = $selection_process->latestEvent;
    @endphp

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-calendar4-week me-2"></i>Editar Processo Seletivo - Vestibulinho
            </h5>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form id="form-calendar" action="{{ route('admin.process.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Seção: Informações Básicas --}}
                            <div class="border-start border-primary border-4 ps-3 mb-4">
                                <h5 class="text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Informações Básicas</h5>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="year" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar3 me-1"></i>Ano Referência do Processo Seletivo
                                        </label>
                                        <input type="number" class="form-control @error('year') is-invalid @enderror"
                                            id="year" name="year" min="2027"
                                            value="{{ old('year', $selection_process->year ?? '') }}">
                                        @error('year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Seção: Edital do Processo Seletivo --}}
                            <div class="border-start border-info border-4 ps-3 mb-4">
                                <h5 class="text-info mb-3"><i class="bi bi-file-text me-2"></i>Edital do Processo Seletivo
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="path" class="form-label required">Arquivo relacionado</label>
                                        <input type="file" name="edital"
                                            class="form-control @error('edital') is-invalid @enderror" id="edital"
                                            placeholder="Endereço">
                                        @error('edital')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Seção: Período de Inscrições (eventos) --}}
                            <div class="border-start border-success border-4 ps-3 mb-4">
                                <h5 class="text-success mb-3"><i class="bi bi-pencil-square me-2"></i>Período de Inscrições
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="inscription_start" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-check me-1"></i>Data de Início
                                        </label>
                                        <input type="date" class="form-control @error('start') is-invalid @enderror"
                                            id="start" name="start"
                                            value="{{ old('start', $event?->start?->format('Y-m-d') ?? '') }}">
                                        @error('start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-x me-1"></i>Data de Término
                                        </label>
                                        <input type="date" class="form-control @error('end') is-invalid @enderror"
                                            id="end" name="end"
                                            value="{{ old('end', $event?->end?->format('Y-m-d') ?? '') }}">
                                        @error('end')
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
                                        <label for="location_publish" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-geo-alt me-1"></i>Divulgação dos Locais de Prova
                                        </label>
                                        <input type="date"
                                            class="form-control @error('location_publish') is-invalid @enderror"
                                            id="location_publish" name="location_publish"
                                            value="{{ old('location_publish', $event?->location_publish?->format('Y-m-d') ?? '') }}">
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
                                            value="{{ old('exam_date', $event?->exam_date?->format('Y-m-d') ?? '') }}">
                                        @error('exam_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="answer_publish" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-key me-1"></i>Divulgação do Gabarito
                                        </label>
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

                            {{-- Seção: Revisão de Questões --}}
                            <div class="border-start border-info border-4 ps-3 mb-4">
                                <h5 class="text-info mb-3"><i class="bi bi-arrow-repeat me-2"></i>Revisão de Questões</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="revision_start" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-check me-1"></i>Início do Prazo para Revisão
                                        </label>
                                        <input type="date"
                                            class="form-control @error('revision_start') is-invalid @enderror"
                                            id="revision_start" name="revision_start"
                                            value="{{ old('revision_start', $event?->revision_start?->format('Y-m-d') ?? '') }}">
                                        @error('revision_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="revision_end" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-calendar-x me-1"></i>Término do Prazo para Revisão
                                        </label>
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

                            {{-- Seção: Resultados e Matrículas --}}
                            <div class="border-start border-danger border-4 ps-3 mb-4">
                                <h5 class="text-danger mb-3"><i class="bi bi-trophy me-2"></i>Resultados e Matrículas</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="result_publish" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-list-ol me-1"></i>Divulgação da Classificação Final
                                        </label>
                                        <input type="date"
                                            class="form-control @error('result_publish') is-invalid @enderror"
                                            id="result_publish" name="result_publish"
                                            value="{{ old('result_publish', $event?->result_publish?->format('Y-m-d') ?? '') }}">
                                        @error('result_publish')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="enrol_start" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-person-check me-1"></i>Cronograma de Matrícula - 1ª Chamada
                                        </label>
                                        <input type="date"
                                            class="form-control @error('enrol_start') is-invalid @enderror"
                                            id="enrol_start" name="enrol_start"
                                            value="{{ old('enrol_start', $event?->enrol_start?->format('Y-m-d') ?? '') }}">
                                        @error('enrol_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="enrol_remaining" class="form-label fw-semibold text-secondary small">
                                            <i class="bi bi-people me-1"></i>Cronograma de Vagas Remanescentes
                                        </label>
                                        <input type="date"
                                            class="form-control @error('enrol_remaining') is-invalid @enderror" id="enrol_remaining"
                                            name="enrol_remaining"
                                            value="{{ old('enrol_remaining', $event?->enrol_remaining?->format('Y-m-d') ?? '') }}">
                                        @error('enrol_remaining')
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
                                <a href="{{ route('admin.process.show') }}" class="btn btn-secondary btn-sm">
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
    <script src="{{ asset('assets/js/admin/selection-process/edit.js') }}"></script>
@endpush
