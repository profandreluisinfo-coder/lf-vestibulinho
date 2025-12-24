@extends('layouts.admin.master')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Painel Administrativo')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/admin/dashboard.css') }}">
@endpush

@section('dash-content')

    @php
        $calendar_active = App\Models\Calendar::first();
        $notice_active = App\Models\Notice::hasActive();
        $local_status = App\Models\ExamResult::hasRecords();
        $ranking_active = App\Models\ExamResult::hasScores();
    @endphp

    <div class="accordion accordion-flush shadow-lg bg-light mb-5" id="accordionFlushExample">
        <div class="accordion-item" id="flush-headingOne">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    📝 Fluxo de Tarefas
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse show bg-light"
                aria-labelledby="flush-headingOne">
                <div class="accordion-body">
                    <div class="stepper-container mb-5">
                        <!-- Etapa 1 -->
                        <a href="{{ route('calendar.index') }}" class="step-item text-secondary text-center" title="Definir Calendário">
                            <i
                                class="bi {{ $calendar_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Definir calendário</p>
                        </a>
                        <!-- Divider -->
                        <div class="step-divider"></div>
                        <!-- Etapa 2 -->
                        <a href="{{ route('notice.index') }}" class="step-item text-secondary text-center" title="Publicar Edital">
                            <i
                                class="bi {{ $notice_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar edital</p>
                        </a>
                        <div class="step-divider"></div>
                        <!-- Etapa 3 -->
                        <a href="{{ route('exam.create') }}" class="step-item text-secondary text-center" title="Agendar Prova">
                            <i
                                class="bi {{ $local_status ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Agendar prova</p>
                        </a>
                        <div class="step-divider"></div>
                        <!-- Etapa 4 -->
                        <a href="{{ route('archive.index') }}" class="step-item text-secondary text-center" title="Publicar Prova">
                            <i
                                class="bi {{ App\Models\Archive::latest('id')->first()->status && App\Models\Archive::latest('id')->first()->answer?->status ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar prova</p>
                        </a>
                        <div class="step-divider"></div>
                        <!-- Etapa 5 -->
                        <a href="{{ route('import.results') }}" class="step-item text-secondary text-center" title="Importar Notas">
                            <i
                                class="bi {{ $ranking_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Importar notas</p>
                        </a>
                        <div class="step-divider"></div>
                        <!-- Etapa 6 -->
                        <a href="{{ route('system.publish.result') }}" class="step-item text-secondary text-center">
                            <i
                                class="bi {{ $settings->result ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar resultados</p>
                        </a>
                        <div class="step-divider"></div>
                        <!-- Etapa 7 -->
                        <a href="{{ route('callings.create') }}" class="step-item text-secondary text-center" title="Definir Chamadas">
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


    <div class="card shadow-sm" data-bs-toggle="modal" data-bs-target="#statistics" style="width: 18rem;">
        <div class="card-body">
            📊 Estatísticas
        </div>
    </div>
    
    <!-- The Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="statistics">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">📊 Dados estatísticos</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Inscritos por Curso -->
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Inscritos por Curso</h5>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="baixarImagem('chartCursos')"
                                                title="Baixar gráfico como imagem PNG">
                                                🖼️
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="imprimirGrafico('chartCursos')"
                                                title="Imprimir gráfico">
                                                🖨️
                                            </button>
                                        </div>
                                    </div>
                                    <canvas id="chartCursos" data-labels='@json($cursos->pluck('name'))'
                                        data-values='@json($cursos->pluck('inscriptions_count'))'>
                                    </canvas>
                                </div>
                            </div>
                        </div>
                        <!-- Inscritos por Sexo -->
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Inscritos por Sexo</h5>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="baixarImagem('chartSexos')"
                                                title="Baixar gráfico como imagem PNG">
                                                🖼️
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="imprimirGrafico('chartSexos')"
                                                title="Imprimir gráfico">
                                                🖨️
                                            </button>
                                        </div>
                                    </div>
                                    <canvas id="chartSexos" data-labels='@json($sexos->pluck('gender'))'
                                        data-values='@json($sexos->pluck('total'))'>
                                    </canvas>
                                </div>
                            </div>
                        </div>
                        <!-- Distribuição de Gêneros por Curso -->
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Inscritos por Curso e Sexo</h5>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="baixarImagem('chartSexoPorCurso')"
                                                title="Baixar gráfico como imagem PNG">
                                                🖼️
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="imprimirGrafico('chartSexoPorCurso')"
                                                title="Imprimir gráfico">
                                                🖨️
                                            </button>
                                        </div>
                                    </div>
                                    <canvas id="chartSexoPorCurso" data-cursos='@json($sexoPorCurso->pluck('course'))'
                                        data-masculino='@json($sexoPorCurso->pluck('masculino'))'
                                        data-feminino='@json($sexoPorCurso->pluck('feminino'))'>
                                    </canvas>
                                </div>
                            </div>
                        </div>
                        <!-- 10 Bairros com Mais Candidatos -->
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">10 Bairros com Mais Candidatos</h5>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="baixarImagem('chartBairros')"
                                                title="Baixar gráfico como imagem PNG">
                                                🖼️
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="imprimirGrafico('chartBairros')"
                                                title="Imprimir gráfico">
                                                🖨️
                                            </button>
                                        </div>
                                    </div>
                                    <canvas id="chartBairros" data-labels='@json($bairros->pluck('burgh'))'
                                        data-values='@json($bairros->pluck('total'))'>
                                    </canvas>
                                </div>
                            </div>
                        </div>
                        <!-- 10 Escolas de Origem -->
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">10 Escolas de Origem com Mais Candidatos</h5>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="baixarImagem('chartEscolas')"
                                                title="Baixar gráfico como imagem PNG">
                                                🖼️
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="imprimirGrafico('chartEscolas')"
                                                title="Imprimir gráfico">
                                                🖨️
                                            </button>
                                        </div>
                                    </div>
                                    <canvas id="chartEscolas" data-labels='@json($escolas->pluck('school_name'))'
                                        data-values='@json($escolas->pluck('total'))'>
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endif

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="{{ asset('assets/charts/burghs.js') }}"></script>
    <script src="{{ asset('assets/charts/courses.js') }}"></script>
    <script src="{{ asset('assets/charts/schools.js') }}"></script>
    <script src="{{ asset('assets/charts/genders.js') }}"></script>
    <script src="{{ asset('assets/charts/gender-per-course.js') }}"></script>
    <script src="{{ asset('assets/charts/chart-actions.js') }}"></script>
@endpush