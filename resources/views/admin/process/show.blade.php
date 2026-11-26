@extends('layouts.admin')

@section('page-title', 'Eventos do Processo Seletivo')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Eventos'],
    ]])
@endsection

@section('content')
    @php
        $results = \App\Models\Process::paginate(10);
    @endphp
    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div>
                    <h4>Eventos do Processo Seletivo</h4>
                    <span>
                        {{ $results->total() }}
                        {{ $results->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.process.edit', $process?->id) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil-square"></i>
                {{ $process?->exists() ? 'Editar Eventos' : 'Definir Eventos' }}
            </a>
        </div>

        @if ($process?->exists())
            {{-- Status do Processo --}}
            <form id="process-access-form" class="mb-4" action="{{ route('admin.process.activate', $process) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card border-0" style="box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
                        <div>
                            <p class="show-section-label mb-1">Status do Processo Seletivo</p>
                            <p class="mb-0" style="font-size: var(--font-size-sm); color: var(--text-muted);">
                                Controla se os candidatos podem se inscrever no processo seletivo no momento.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="status-pill {{ $process?->status === 'open' ? 'status-open' : 'status-closed' }}">
                                <i class="bi bi-{{ $process?->status === 'open' ? 'unlock' : 'lock' }}"></i>
                                {{ $process?->status === 'open' ? 'Aberto' : 'Fechado' }}
                            </span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="process" name="process"
                                    onchange="activateProcessSelective(this)"
                                    {{ $process?->status === 'open' ? 'checked' : '' }}
                                    style="width: 2.5em; height: 1.3em; cursor: pointer;">
                                <label class="form-check-label visually-hidden" for="process">Ativar processo</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card border-0" style="box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
                <div class="card-body p-4">
                    <div class="row g-0">
                        {{-- Informações Básicas --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Informações Básicas</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <span class="show-field-label">Processo Seletivo</span>
                                    <span class="show-field-value">{{ $process?->year }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">
                        {{-- Inscrições --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Período de Inscrições</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <span class="show-field-label">Início</span>
                                    <span class="show-field-value">{{ $process?->start?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Término</span>
                                    <span class="show-field-value">{{ $process?->end?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">
                        {{-- Divulgação das Inscrições Deferidas e Indeferidas --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Divulgação das Inscrições Deferidas e Indeferidas, PCDs e Nome Social</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <span class="show-field-label">Divulgação Preliminar</span>
                                    <span class="show-field-value">{{ $process?->deferrals?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">
                        {{-- Abertura e Fechamento do Prazo de Recursos --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Abertura e Fechamento do Prazo de Recursos</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <span class="show-field-label">Abertura do Prazo</span>
                                    <span class="show-field-value">{{ $process?->appeals_start?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <span class="show-field-label">Fechamento do Prazo</span>
                                    <span class="show-field-value">{{ $process?->appeals_end?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">
                        {{-- Divulgação do Resultado da Análise dos Recursos --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Divulgação do Resultado da Análise dos Recursos</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <span class="show-field-label">Data de Divulgação</span>
                                    <span class="show-field-value">{{ $process?->appeals_results?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">                        
                        {{-- Divulgação da Lista Definitiva --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Divulgação da relação definitiva de candidatos inscritos após a análise dos recursos</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <span class="show-field-label">Data de Divulgação</span>
                                    <span class="show-field-value">{{ $process?->definitive_list?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">
                        {{-- Aplicação das Provas --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Aplicação das Provas</p>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <span class="show-field-label">Divulgação dos Locais de Prova</span>
                                    <span
                                        class="show-field-value">{{ $process?->location_publish?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="show-field-label">Data de Aplicação</span>
                                    <span class="show-field-value">{{ $process?->exam_date?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="show-field-label">Horário de Aplicação</span>
                                    <span class="show-field-value">{{ $process?->exam_time?->format('H:i') ?? '—' }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span class="show-field-label">Divulgação do Gabarito</span>
                                    <span
                                        class="show-field-value">{{ $process?->answer_publish?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">

                        {{-- Revisão --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Revisão de Questões</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <span class="show-field-label">Início do Prazo</span>
                                    <span
                                        class="show-field-value">{{ $process?->revision_start?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Término do Prazo</span>
                                    <span
                                        class="show-field-value">{{ $process?->revision_end?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">
                        {{-- Resultados --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Resultados</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <span class="show-field-label">Divulgação da Classificação Final</span>
                                    <span
                                        class="show-field-value">{{ $process?->result_publish?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">
                        {{-- Matrículas --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Matrículas</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <span class="show-field-label">1ª Chamada</span>
                                    <span
                                        class="show-field-value">{{ $process?->enrol_start?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Vagas Remanescentes</span>
                                    <span
                                        class="show-field-value">{{ $process?->enrol_remaining?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Limite de novas convocações</span>
                                    <span class="show-field-value">{{ $process?->enrol_deadline?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-divider">
                        {{-- Edital --}}
                        <div class="col-12">
                            <p class="show-section-label">Edital</p>
                            <a href="{{ Storage::url($process?->edital) }}" target="_blank"
                                class="d-inline-flex align-items-center gap-2 text-decoration-none show-field-value"
                                style="color: var(--color-teal);">
                                <i class="bi bi-file-text"></i>
                                Visualizar Edital do Processo Seletivo
                                <i class="bi bi-box-arrow-up-right" style="font-size: 0.72rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0" style="box-shadow: var(--shadow-sm); border-radius: var(--radius-md);">
                <div class="card-body p-4 d-flex align-items-start gap-3">
                    <i class="bi bi-info-circle text-muted fs-5 mt-1 flex-shrink-0"></i>
                    <div>
                        <p class="fw-semibold mb-1" style="color: var(--text-dark); font-size: var(--font-size-sm);">
                            Nenhuma grade de eventos foi definida.
                        </p>
                        <p class="mb-0 text-muted" style="font-size: var(--font-size-sm);">
                            Use o botão <strong>Definir Eventos</strong> acima para configurar o calendário deste processo
                            seletivo.
                        </p>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/process/activate.js') }}"></script>
@endpush
