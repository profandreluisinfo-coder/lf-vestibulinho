@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Painel Administrativo')

@section('dash-content')

    @php
        $calendar_active = App\Models\Calendar::getActive();
        $notice_active   = App\Models\Notice::hasActive();
        $local_status    = App\Models\ExamResult::hasRecords();
        $ranking_active  = App\Models\ExamResult::hasScores();
    @endphp

    <div class="container" style="background-color: #f8f9fa; border-radius: 10px;">

        <h5 class="border-bottom pb-2 mb-4">📝 Fluxo de Tarefas</h5>
        <div class="d-flex justify-content-between align-items-center mb-5">

            <!-- Etapa 1 -->
            <div class="text-center">
                @if ($calendar_active)
                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                @else
                    <i class="bi bi-hourglass-split text-warning fs-3"></i>
                @endif
                <p class="mt-2 mb-0">Definir calendário</p>
                <span
                    class="badge {{ $calendar_active ? 'bg-success' : 'bg-danger' }}">{{ $calendar_active ? 'Concluído' : 'Pendente' }}</span>
            </div>

            <!-- Linha -->
            <div class="flex-grow-1 border-top border-3 mx-2"></div>

            <!-- Etapa 2 -->
            <div class="text-center">
                <i
                    class="bi {{ $notice_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                <p class="mt-2 mb-0">Publicar edital</p>
                <span
                    class="badge {{ $notice_active ? 'bg-success' : 'bg-danger' }}">{{ $notice_active ? 'Concluído' : 'Pendente' }}</span>
            </div>

            <!-- Linha -->
            <div class="flex-grow-1 border-top border-3 mx-2"></div>

            <!-- Etapa 3 -->
            <div class="text-center">
                <i class="bi bi-{{ $local_status ? 'check-circle-fill text-success' : 'clock-fill text-secondary' }} fs-3"></i>
                <p class="mt-2 mb-0">Agendar prova</p>
                <span class="badge {{ $local_status ? 'bg-success' : 'bg-secondary' }}">{{ $local_status ? 'Concluído' : 'Aguardando' }}</span>
            </div>

            <!-- Linha -->
            <div class="flex-grow-1 border-top border-3 mx-2"></div>

            <!-- Etapa 4 -->
            <div class="text-center">
                <i class="bi bi-{{ $ranking_active ? 'check-circle-fill text-success' : 'clock-fill text-secondary' }} fs-3"></i>
                <p class="mt-2 mb-0">Importar notas</p>
                <span class="badge {{ $ranking_active ? 'bg-success' : 'bg-secondary' }}">{{ $ranking_active ? 'Concluído' : 'Aguardando' }}</span>
            </div>

            <!-- Linha -->
            <div class="flex-grow-1 border-top border-3 mx-2"></div>

            <!-- Etapa 5 -->
            <div class="text-center">
                <i class="bi bi-{{ $settings->result ? 'check-circle-fill text-success' : 'clock-fill text-secondary' }} fs-3"></i>
                <p class="mt-2 mb-0">Publicar resultados</p>
                <span class="badge {{ $settings->result ? 'bg-success' : 'bg-secondary' }}">{{ $settings->result ? 'Concluído' : 'Aguardando' }}</span>
            </div>

            <!-- Linha -->
            <div class="flex-grow-1 border-top border-3 mx-2"></div>

            <!-- Etapa 6 -->
            <div class="text-center">
                <i class="bi bi-{{ $calls_exists ? 'check-circle-fill text-success' : 'clock-fill text-secondary' }} fs-3"></i>
                <p class="mt-2 mb-0">Definir chamadas</p>
                <span class="badge {{ $calls_exists ? 'bg-success' : 'bg-secondary' }}">{{ $calls_exists ? 'Concluído' : 'Aguardando' }}</span>
            </div>

        </div>

        <h5 class="border-bottom pb-2 mb-4 ">📊 Estatísticas</h5>

        <div class="row g-4 mb-5">

            <!-- Inscritos por Curso -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Inscritos por Curso</h5>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-secondary" onclick="baixarImagem('chartCursos')"
                                    title="Baixar gráfico como imagem PNG">
                                    🖼️
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="imprimirGrafico('chartCursos')"
                                    title="Imprimir gráfico">
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
                                <button class="btn btn-sm btn-outline-secondary" onclick="baixarImagem('chartSexos')"
                                    title="Baixar gráfico como imagem PNG">
                                    🖼️
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="imprimirGrafico('chartSexos')"
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
                                <button class="btn btn-sm btn-outline-secondary" onclick="baixarImagem('chartSexoPorCurso')"
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
                            data-masculino='@json($sexoPorCurso->pluck('masculino'))' data-feminino='@json($sexoPorCurso->pluck('feminino'))'>
                        </canvas>
                    </div>
                </div>
            </div>

            <!-- 10 Bairros com Mais Candidatos -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">10 Bairros com Mais Candidatos</h5>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-secondary" onclick="baixarImagem('chartBairros')"
                                    title="Baixar gráfico como imagem PNG">
                                    🖼️
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="imprimirGrafico('chartBairros')"
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
                                <button class="btn btn-sm btn-outline-secondary" onclick="baixarImagem('chartEscolas')"
                                    title="Baixar gráfico como imagem PNG">
                                    🖼️
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="imprimirGrafico('chartEscolas')"
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
