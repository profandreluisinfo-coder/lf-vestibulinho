@extends('layouts.admin.master')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Painel Administrativo')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/admin/dashboard.css') }}">
@endpush

@section('dash-content')

    @php
        $calendar_active = App\Models\Calendar::first();
        $local_status = App\Models\ExamResult::hasRecords();
        $ranking_active = App\Models\ExamResult::hasScores();
        $inscriptions_count = App\Models\Inscription::count();
    @endphp

    @php
        $steps_done = collect([
            $calendar_active,
            $settings->isNoticeEnabled(),
            $local_status,
            App\Models\Archive::latest('id')->first()?->status == 1,
            $ranking_active,
            $settings->result,
            $calls_exists,
        ])
            ->filter()
            ->count();

        $steps_total = 7;
        $steps_pct = round(($steps_done / $steps_total) * 100);
    @endphp

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-baseline mb-1">
            <small class="fw-semibold text-muted">Progresso das tarefas</small>
            <small class="text-muted">{{ $steps_done }} de {{ $steps_total }} concluídas ({{ $steps_pct }}%)</small>
        </div>
        <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $steps_pct }}%"
                aria-valuenow="{{ $steps_pct }}" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
    </div>

    <div class="accordion accordion-flush shadow-lg bg-light mb-5" id="accordionFlush">
        <div class="accordion-item" id="flush-headingOne">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    📝 Tarefas
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse show bg-light"
                aria-labelledby="flush-headingOne">
                <div class="accordion-body">
                    <div class="stepper-container mb-5">
                        <!-- Etapa 1 -->
                        <a href="{{ route('app.calendar.index') }}" class="step-item text-secondary text-center"
                            title="Definir Calendário">
                            <i
                                class="bi {{ $calendar_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Definir calendário</p>
                        </a>

                        <!-- Divider -->
                        <div class="step-divider"></div>

                        <!-- Etapa 2 -->
                        <a href="{{ route('app.notices.index') }}" class="step-item text-secondary text-center"
                            title="Publicar Edital">
                            <i
                                class="bi {{ $settings->isNoticeEnabled() ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar edital</p>
                        </a>

                        <div class="step-divider"></div>

                        <!-- Etapa 3 -->
                        <a href="{{ route('app.exam.create') }}" class="step-item text-secondary text-center"
                            title="Agendar Prova">
                            <i
                                class="bi {{ $local_status ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Agendar prova</p>
                        </a>

                        <div class="step-divider"></div>

                        <!-- Etapa 4 -->
                        <a href="{{ route('app.archives.index') }}" class="step-item text-secondary text-center"
                            title="Publicar Prova">
                            <i
                                class="bi {{ App\Models\Archive::latest('id')->first()?->status == 1 ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar prova</p>
                        </a>

                        <div class="step-divider"></div>

                        <!-- Etapa 5 -->
                        <a href="{{ route('app.import.home') }}" class="step-item text-secondary text-center"
                            title="Importar Notas">
                            <i
                                class="bi {{ $ranking_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Importar notas</p>
                        </a>
                        <div class="step-divider"></div>
                        <!-- Etapa 6 -->
                        <a href="{{ route('app.system.publish.result') }}" class="step-item text-secondary text-center">
                            <i
                                class="bi {{ $settings->result ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar resultados</p>
                        </a>
                        <div class="step-divider"></div>
                        <!-- Etapa 7 -->
                        <a href="{{ route('app.calls.index') }}" class="step-item text-secondary text-center"
                            title="Definir Chamadas">
                            <i
                                class="bi {{ $calls_exists ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Definir chamadas</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($calendar_active?->hasInscriptionStarted())
        @if ($inscriptions_count > 0)
            <div class="row g-3 mb-4">

                {{-- Card 1: Inscritos por Curso --}}
                <div class="col-md-3">
                    <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalCursos">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-bar-chart-fill fs-2 text-primary mb-2"></i>
                            <p class="mb-0 fw-semibold">Inscritos por Curso</p>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Inscritos por Sexo --}}
                <div class="col-md-3">
                    <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalSexos">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-pie-chart-fill fs-2 text-success mb-2"></i>
                            <p class="mb-0 fw-semibold">Inscritos por Sexo</p>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Inscritos por Curso e Sexo --}}
                <div class="col-md-3">
                    <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                        data-bs-target="#modalSexoPorCurso">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-bar-chart-steps fs-2 text-warning mb-2"></i>
                            <p class="mb-0 fw-semibold">Inscritos por Curso e Sexo</p>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Bairros --}}
                <div class="col-md-3">
                    <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalBairros">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-geo-alt-fill fs-2 text-danger mb-2"></i>
                            <p class="mb-0 fw-semibold">Bairros com Mais Candidatos</p>
                        </div>
                    </div>
                </div>

                {{-- Card 5: Escolas --}}
                <div class="col-md-3">
                    <div class="card shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                        data-bs-target="#modalEscolas">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-building fs-2 text-secondary mb-2"></i>
                            <p class="mb-0 fw-semibold">Escolas de Origem</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Modais individuais --}}

            {{-- Modal: Cursos --}}
            <div class="modal fade" id="modalCursos" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Inscritos por Curso</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <canvas id="chartCursos" data-labels='@json($cursos->pluck('curso'))'
                                data-values='@json($cursos->pluck('total'))'></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal: Sexos --}}
            <div class="modal fade" id="modalSexos" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Inscritos por Sexo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <canvas id="chartSexos" data-labels='@json($sexos->pluck('gender'))'
                                data-values='@json($sexos->pluck('total'))'></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal: Sexo por Curso --}}
            <div class="modal fade" id="modalSexoPorCurso" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Inscritos por Curso e Sexo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <canvas id="chartSexoPorCurso" data-cursos='@json($sexoPorCurso->pluck('course'))'
                                data-masculino='@json($sexoPorCurso->pluck('masculino'))'
                                data-feminino='@json($sexoPorCurso->pluck('feminino'))'></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal: Bairros --}}
            <div class="modal fade" id="modalBairros" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">10 Bairros com Mais Candidatos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <canvas id="chartBairros" data-labels='@json($bairros->pluck('burgh'))'
                                data-values='@json($bairros->pluck('total'))'></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal: Escolas --}}
            <div class="modal fade" id="modalEscolas" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">10 Escolas de Origem com Mais Candidatos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <canvas id="chartEscolas" data-labels='@json($escolas->pluck('school_name'))'
                                data-values='@json($escolas->pluck('total'))'></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="{{ asset('assets/js/charts/burghs.js') }}"></script>
    <script src="{{ asset('assets/js/charts/courses.js') }}"></script>
    <script src="{{ asset('assets/js/charts/schools.js') }}"></script>
    <script src="{{ asset('assets/js/charts/genders.js') }}"></script>
    <script src="{{ asset('assets/js/charts/gender-per-course.js') }}"></script>
    <script src="{{ asset('assets/js/charts/chart-actions.js') }}"></script>
@endpush
