@extends('layouts.admin')

@section('page-title', 'Vestibulinho | Eventos')

@push('styles')
    <style>
        .show-section-label {
            font-size: var(--font-size-sm);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-muted);
            margin-bottom: 0.75rem;
        }

        .show-field-label {
            display: block;
            font-size: var(--font-size-xs);
            color: var(--text-muted);
            margin-bottom: 0.2rem;
        }

        .show-field-value {
            display: block;
            font-size: var(--font-size-sm);
            font-weight: 600;
            color: var(--text-dark);
        }

        .show-divider {
            border-color: var(--border-color);
            margin: 0 0 1.5rem 0;
            opacity: 1;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.75rem;
            border-radius: var(--radius-pill);
            font-size: var(--font-size-xs);
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .status-open {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--color-success-dark);
        }

        .status-closed {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--color-danger);
        }
    </style>
@endpush

@section('content')

    @php
        $event = $process?->latestEvent;
    @endphp

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar4-week text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Eventos do Processo Seletivo</h6>
            </div>
            <a href="{{ route('admin.process.edit', $process?->id) }}" class="btn btn-sm btn-outline-secondary">
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
                                Controla se os candidatos podem acessar o processo seletivo no momento.
                            </p>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <span class="status-pill {{ $process->status === 'open' ? 'status-open' : 'status-closed' }}">
                                <i class="bi bi-{{ $process->status === 'open' ? 'unlock' : 'lock' }}"></i>
                                {{ $process->status === 'open' ? 'Aberto' : 'Fechado' }}
                            </span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="process" name="process"
                                    onchange="activateProcessSelective(this)"
                                    {{ $process->status === 'open' ? 'checked' : '' }}
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
                                {{-- <div class="col-md-4">
                                    <span class="show-field-label">Ano de Referência</span>
                                    <span class="show-field-value">{{ $process?->year }}</span>
                                </div> --}}
                            </div>
                        </div>

                        <hr class="show-divider">

                        {{-- Inscrições --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Período de Inscrições</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <span class="show-field-label">Início</span>
                                    <span class="show-field-value">{{ $event?->start?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Término</span>
                                    <span class="show-field-value">{{ $event?->end?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="show-divider">

                        {{-- Aplicação das Provas --}}
                        <div class="col-12 mb-4">
                            <p class="show-section-label">Aplicação das Provas</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <span class="show-field-label">Divulgação dos Locais de Prova</span>
                                    <span
                                        class="show-field-value">{{ $event?->location_publish?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Data de Aplicação</span>
                                    <span class="show-field-value">{{ $event?->exam_date?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Divulgação do Gabarito</span>
                                    <span
                                        class="show-field-value">{{ $event?->answer_publish?->format('d/m/Y') ?? '—' }}</span>
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
                                        class="show-field-value">{{ $event?->revision_start?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Término do Prazo</span>
                                    <span
                                        class="show-field-value">{{ $event?->revision_end?->format('d/m/Y') ?? '—' }}</span>
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
                                        class="show-field-value">{{ $event?->result_publish?->format('d/m/Y') ?? '—' }}</span>
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
                                        class="show-field-value">{{ $event?->enrol_start?->format('d/m/Y') ?? '—' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="show-field-label">Vagas Remanescentes</span>
                                    <span
                                        class="show-field-value">{{ $event?->enrol_remaining?->format('d/m/Y') ?? '—' }}</span>
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
                            Nenhuma grade de eventos definida
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
    <script src="{{ asset('assets/js/admin/process/swa/activate.js') }}"></script>
@endpush
