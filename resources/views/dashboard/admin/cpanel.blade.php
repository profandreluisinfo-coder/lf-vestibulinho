@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Painel Administrativo')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/admin/dashboard.css') }}">
@endpush

@section('dash-content')

    @php
        $calendar_active = App\Models\Calendar::first();
        $notice_active   = App\Models\Notice::hasActive();
        $local_status    = App\Models\ExamResult::hasRecords();
        $ranking_active  = App\Models\ExamResult::hasScores();
    @endphp

    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item" id="flush-headingOne">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    📝 Fluxo de Tarefas
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse show bg-light" aria-labelledby="flush-headingOne">
                <div class="accordion-body">
                    <div class="stepper-container mb-5">
                        <!-- Etapa 1 -->
                        <div class="step-item text-center">
                            <i
                                class="bi {{ $calendar_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Definir calendário</p>
                            {{-- <span class="badge {{ $calendar_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $calendar_active ? 'Concluído' : 'Pendente' }}
                            </span> --}}
                        </div>
                        <!-- Divider -->
                        <div class="step-divider"></div>
                        <!-- Etapa 2 -->
                        <div class="step-item text-center">
                            <i
                                class="bi {{ $notice_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar edital</p>
                            {{-- <span class="badge {{ $notice_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $notice_active ? 'Concluído' : 'Pendente' }}
                            </span> --}}
                        </div>
                        <!-- Divider -->
                        <div class="step-divider"></div>
                        <!-- Etapa 3 -->
                        <div class="step-item text-center">
                            <i
                                class="bi {{ $local_status ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Agendar prova</p>
                            {{-- <span class="badge {{ $local_status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $local_status ? 'Concluído' : 'Aguardando' }}
                            </span> --}}
                        </div>
                        <!-- Divider -->
                        <div class="step-divider"></div>
                        <!-- Etapa 4 -->
                        <div class="step-item text-center">
                            <i
                                class="bi {{ $ranking_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Importar notas</p>
                            {{-- <span class="badge {{ $ranking_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $ranking_active ? 'Concluído' : 'Aguardando' }}
                            </span> --}}
                        </div>
                        <!-- Divider -->
                        <div class="step-divider"></div>
                        <!-- Etapa 5 -->
                        <div class="step-item text-center">
                            <i
                                class="bi {{ $settings->result ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar resultados</p>
                            {{-- <span class="badge {{ $settings->result ? 'bg-success' : 'bg-secondary' }}">
                                {{ $settings->result ? 'Concluído' : 'Aguardando' }}
                            </span> --}}
                        </div>
                        <!-- Divider -->
                        <div class="step-divider"></div>
                        <!-- Etapa 6 -->
                        <div class="step-item text-center">
                            <i
                                class="bi {{ $calls_exists ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Definir chamadas</p>
                            {{-- <span class="badge {{ $calls_exists ? 'bg-success' : 'bg-secondary' }}">
                                {{ $calls_exists ? 'Concluído' : 'Aguardando' }}
                            </span> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($calendar_active?->hasInscriptionStarted())
        <div class="accordion-item bg-light">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    📊 Estatísticas
                </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo">
                <div class="accordion-body p-0">
                    <div class="row g-4">
                    <!-- Inscritos por Curso -->
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Inscritos por Curso</h5>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="baixarImagem('chartCursos')" title="Baixar gráfico como imagem PNG">
                                            🖼️
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="imprimirGrafico('chartCursos')" title="Imprimir gráfico">
                                            🖨️
                                        </button>
                                    </div>
                                </div>
                                <canvas id="chartCursos" data-labels='@json($cursos->pluck('curso'))'
                                    data-values='@json($cursos->pluck('total'))'>
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
                                            onclick="baixarImagem('chartSexos')" title="Baixar gráfico como imagem PNG">
                                            🖼️
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="imprimirGrafico('chartSexos')" title="Imprimir gráfico">
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
                                            onclick="imprimirGrafico('chartSexoPorCurso')" title="Imprimir gráfico">
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
                                            onclick="baixarImagem('chartBairros')" title="Baixar gráfico como imagem PNG">
                                            🖼️
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="imprimirGrafico('chartBairros')" title="Imprimir gráfico">
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
                                            onclick="baixarImagem('chartEscolas')" title="Baixar gráfico como imagem PNG">
                                            🖼️
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="imprimirGrafico('chartEscolas')" title="Imprimir gráfico">
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
            </div>
        </div>
        @endif
    </div>

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
