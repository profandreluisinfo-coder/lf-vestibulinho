

<?php $__env->startSection('page-title', config('app.name') . ' ' . $calendar?->year . ' | Painel Administrativo'); ?>

<?php $__env->startSection('dash-content'); ?>

<div class="container" style="background-color: #f8f9fa; border-radius: 10px; ">
    <h4 class="mb-4 border-bottom pb-2">📊 Estatísticas - <?php echo e(config('app.name')); ?> <?php echo e($calendar?->year); ?></h4>

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
                        data-labels='<?php echo json_encode($cursos->pluck("curso"), 15, 512) ?>'
                        data-values='<?php echo json_encode($cursos->pluck("total"), 15, 512) ?>'>
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
                        data-labels='<?php echo json_encode($sexos->pluck("gender"), 15, 512) ?>'
                        data-values='<?php echo json_encode($sexos->pluck("total"), 15, 512) ?>'>
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
                        data-cursos='<?php echo json_encode($sexoPorCurso->pluck("course"), 15, 512) ?>'
                        data-masculino='<?php echo json_encode($sexoPorCurso->pluck("masculino"), 15, 512) ?>'
                        data-feminino='<?php echo json_encode($sexoPorCurso->pluck("feminino"), 15, 512) ?>'>
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
                        data-labels='<?php echo json_encode($bairros->pluck("burgh"), 15, 512) ?>'
                        data-values='<?php echo json_encode($bairros->pluck("total"), 15, 512) ?>'>
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
                        data-labels='<?php echo json_encode($escolas->pluck("school_name"), 15, 512) ?>'
                        data-values='<?php echo json_encode($escolas->pluck("total"), 15, 512) ?>'>
                    </canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="<?php echo e(asset('assets/charts/burghs.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/charts/courses.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/charts/schools.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/charts/genders.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/charts/gender-per-course.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/charts/chart-actions.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dash.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>