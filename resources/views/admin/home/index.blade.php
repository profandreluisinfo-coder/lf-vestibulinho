@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF | Painel Administrativo')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="bi bi-gear me-2"></i>
                Acesso Rápido
            </h4>
        </div>

        <div class="row g-4 mb-5">

            {{-- Card: Redefinir Sistema --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-danger shadow-sm h-100">

                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-database-dash fs-4"></i>
                            </div>
                        </div>

                        <h5 class="card-title fw-bold text-danger">
                            Redefinir Sistema
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            Remove usuários, inscrições, calendário atual e eventos vinculados ao processo seletivo.
                        </p>

                        <div class="alert alert-danger py-2 px-3 small mb-3">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Esta ação não poderá ser desfeita.
                        </div>

                        <button class="btn btn-danger btn-sm w-100" id="btn-reset-system" onclick="resetSystem()">
                            <i class="bi bi-trash me-1"></i>
                            Redefinir Sistema
                        </button>
                    </div>

                </div>
            </div>

            {{-- Exemplo de outros cards --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-calendar-event fs-4"></i>
                            </div>
                        </div>
                        <h5 class="card-title fw-bold">
                            Proceso Seletivo
                        </h5>
                        <p class="card-text text-muted small flex-grow-1">
                            Gerencie os eventos do processo seletivo.
                        </p>
                        <a href="{{ route('admin.process.show') }}" class="btn btn-primary btn-sm w-100">
                            Gerenciar
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="mb-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-file-earmark-text fs-4"></i>
                            </div>
                        </div>

                        <h5 class="card-title fw-bold">
                            Edital
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            Atualize os arquivos do edital e documentos complementares.
                        </p>

                        <button class="btn btn-success btn-sm w-100">
                            Configurar
                        </button>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="mb-3">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-question-circle fs-4"></i>
                            </div>
                        </div>

                        <h5 class="card-title fw-bold">
                            FAQs
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            Gerencie perguntas frequentes e informações de ajuda aos candidatos.
                        </p>

                        <button class="btn btn-warning btn-sm w-100 text-white">
                            Editar FAQs
                        </button>

                    </div>
                </div>
            </div>

        </div>

        <div class="accordion accordion-flush shadow-lg mb-2" id="accordionFlushOne">

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        📝 Principais Tarefas
                    </button>
                </h2>

                <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne">

                    <div class="accordion-body">
                        <div class="stepper-container mb-5">

                            <!-- Etapa 1 -->
                            <a href="{{ route('admin.process.show') }}" class="step-item text-secondary text-center"
                                title="Definir Calendário">
                                <i
                                    class="bi {{ $selection_process ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                                <p class="mt-2 mb-0 fw-semibold">Definir calendário</p>
                            </a>

                            <div class="step-divider"></div>

                            <!-- Etapa 3 -->
                            <a href="{{ route('admin.exam.create') }}" class="step-item text-secondary text-center"
                                title="Agendar Prova">
                                <i
                                    class="bi {{ $local_status ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                                <p class="mt-2 mb-0 fw-semibold">Agendar prova</p>
                            </a>

                            <div class="step-divider"></div>

                            <!-- Etapa 4 -->
                            <a href="{{ route('admin.import.home') }}" class="step-item text-secondary text-center"
                                title="Importar Notas">
                                <i
                                    class="bi {{ $ranking_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                                <p class="mt-2 mb-0 fw-semibold">Importar notas</p>
                            </a>

                            <div class="step-divider"></div>

                            <!-- Etapa 5 -->
                            <a href="{{ route('admin.system.publish.result') }}"
                                class="step-item text-secondary text-center">
                                <i
                                    class="bi {{ $settings->result ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                                <p class="mt-2 mb-0 fw-semibold">Publicar resultados</p>
                            </a>

                            <div class="step-divider"></div>

                            <!-- Etapa 6 -->
                            <a href="{{ route('admin.calls.index') }}" class="step-item text-secondary text-center"
                                title="Definir Chamadas">
                                <i
                                    class="bi {{ $calls_exists ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                                <p class="mt-2 mb-0 fw-semibold">Definir chamadas</p>
                            </a>

                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                <small class="fw-semibold text-muted">Progresso das tarefas</small>
                                <small class="text-muted">
                                    {{ $steps_done }} de {{ $steps_total }} concluídas ({{ $steps_pct }}%)
                                </small>
                            </div>

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $steps_pct }}%" aria-valuenow="{{ $steps_pct }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div><!-- /.accordion-body -->

                </div>
            </div>

        </div><!-- /.accordion -->

        {{-- @include('admin.partials.graphics') --}}

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    {{-- <script src="{{ asset('assets/js/vestibulinho/charts/burghs.js') }}"></script>
    <script src="{{ asset('assets/js/vestibulinho/charts/courses.js') }}"></script>
    <script src="{{ asset('assets/js/vestibulinho/charts/schools.js') }}"></script>
    <script src="{{ asset('assets/js/vestibulinho/charts/genders.js') }}"></script>
    <script src="{{ asset('assets/js/vestibulinho/charts/gender-per-course.js') }}"></script>
    <script src="{{ asset('assets/js/vestibulinho/charts/chart-actions.js') }}"></script> --}}
    
@endpush
