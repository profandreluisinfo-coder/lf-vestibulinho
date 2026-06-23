@extends('layouts.admin')

@section('page-title', 'Vestibulinho | Eventos')

@section('content')

    @php
        $sp = App\Models\SelectionProcess::find($selection_process?->id);
        $event = $sp?->latestEvent;
    @endphp

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="bi bi-calendar4-week me-2"></i> Eventos
            </h5>
            <a href="{{ route('admin.process.edit', $sp?->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square me-1"></i>
                {{ $sp?->exists() ? 'Editar Eventos' : 'Definir Eventos' }}
            </a>
        </div>

        @if ($sp?->exists())

            <div class="row g-4 mb-4">

                {{-- Card de Informações Básicas --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-primary">
                                <i class="bi bi-info-circle me-2"></i>Informações Básicas
                            </h6>
                            <p class="mb-0">
                                <span class="text-muted">Processo Seletivo</span>: {{ $sp?->year }}<br><span
                                    class="text-muted">Ano de Referência:</span> {{ $sp?->year }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card de Período de Inscrições --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-primary">
                                <i class="bi bi-pencil-square me-2"></i>Inscrições
                            </h6>
                            <p class="mb-0">
                                {{ $event?->start?->format('d/m/Y') }}
                                <span class="text-muted">até</span>
                                {{ $event?->end?->format('d/m/Y') }}
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
                            <p class="mb-0">{{ $event?->location_publish?->format('d/m/Y') }}
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
                            <p class="mb-0">{{ $event?->exam_date?->format('d/m/Y') }}</p>
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
                            <p class="mb-0">{{ $event?->answer_publish?->format('d/m/Y') }}
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
                                {{ $event?->revision_start?->format('d/m/Y') }}
                                <span class="text-muted">até</span>
                                {{ $event?->revision_end?->format('d/m/Y') }}
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
                            <p class="mb-0">{{ $event?->result_publish?->format('d/m/Y') }}
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
                            <p class="mb-0">{{ $event?->enrol_start?->format('d/m/Y') }}</p>
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
                            <p class="mb-0">{{ $event?->enrol_remaining?->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Edital do Processo Seletivo --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-info">
                                <a href="{{ Storage::url($sp?->edital) }}" target="_blank" class="text-decoration-none"
                                    title="Visualizar detalhes">
                                    <i class="bi bi-file-text me-2"></i>Edital do Processo Seletivo <i
                                        class="bi bi-search ms-2"></i>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            @if ($sp?->exists())
                <form id="process-access-form" action="{{ route('admin.process.activate', $sp) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="process" name="process"
                            onchange="activateProcessSelective(this)" {{ $sp?->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="calendar">
                            <span class="badge bg-{{ $sp?->status ? 'success' : 'danger' }} ms-2">
                                {!! $sp?->status
                                    ? '<i class="bi bi-unlock"></i> O Processo Seletivo está aberto'
                                    : '<i class="bi bi-lock"></i> O Processo Seletivo está fechado' !!}
                            </span>
                        </label>
                    </div>
                </form>
            @endif
        @else
            <div id="meu-alert" class="alert alert-info d-flex align-items-start border-0 rounded-3 p-3" role="alert">

                <div class="me-3 fs-3" aria-hidden="true">
                    <i class="bi bi-info-circle-fill"></i>
                </div>

                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">Informação Importante</h5>
                    <p class="mb-0">
                        Você ainda não definiu uma grade de eventos.
                    </p>
                    <p class="mb-0 mt-2 small opacity-75">
                        Em caso de dúvidas, entre em contato com o suporte técnico.
                    </p>
                </div>

                <button type="button" class="btn-close ms-3" aria-label="Fechar alerta" data-bs-dismiss="alert"></button>

            </div>

        @endif

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/selection-process/swa/activate.js') }}"></script>
@endpush
