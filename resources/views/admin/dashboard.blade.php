@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Painel Administrativo')

@section('dash-content')

<div class="container" style="background-color: #f8f9fa; border-radius: 10px; ">
    <h4 class="mb-4 border-bottom pb-2">📊 Estatísticas - {{ config('app.name') }} {{ $calendar?->year }}</h4>

    <div class="row g-4">

        <!-- Inscritos por Curso -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Inscritos por Curso</h5>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-secondary" onclick="baixarImagem('chartCursos')" title="Baixar gráfico como imagem PNG">
                                🖼️
                            </button>
                            <button class="btn btn-sm btn-outline-secondary"
                                onclick="imprimirGrafico('chartCursos')" title="Imprimir gráfico">
                                🖨️
                            </button>
                        </div>
                    </div>
                    <canvas id="chartCursos"
                        data-labels='@json($cursos->pluck("curso"))'
                        data-values='@json($cursos->pluck("total"))'>
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
                    <canvas id="chartSexos"
                        data-labels='@json($sexos->pluck("gender"))'
                        data-values='@json($sexos->pluck("total"))'>
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
                                onclick="baixarImagem('chartSexoPorCurso')" title="Baixar gráfico como imagem PNG">
                                🖼️
                            </button>
                            <button class="btn btn-sm btn-outline-secondary"
                                onclick="imprimirGrafico('chartSexoPorCurso')" title="Imprimir gráfico">
                                🖨️
                            </button>
                        </div>
                    </div>
                    <canvas id="chartSexoPorCurso"
                        data-cursos='@json($sexoPorCurso->pluck("course"))'
                        data-masculino='@json($sexoPorCurso->pluck("masculino"))'
                        data-feminino='@json($sexoPorCurso->pluck("feminino"))'>
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
                    <canvas id="chartBairros"
                        data-labels='@json($bairros->pluck("burgh"))'
                        data-values='@json($bairros->pluck("total"))'>
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
                    <canvas id="chartEscolas"
                        data-labels='@json($escolas->pluck("school_name"))'
                        data-values='@json($escolas->pluck("total"))'>
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