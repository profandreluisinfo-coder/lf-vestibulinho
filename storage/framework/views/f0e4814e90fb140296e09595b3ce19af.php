

<?php $__env->startSection('page-title', config('app.name') . ' ' . $calendar?->year . ' | Classificação Geral'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/results/styles.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('dash-content'); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-list-ol me-2"></i>Classificação Geral</h4>
        </div>
        <div class="row">
            <div class="col mx-auto">
                
                <?php if($results?->isNotEmpty()): ?>
                    <form id="result-access-form" class="mb-3" action="<?php echo e(route('setAccessToResult')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="result" name="result"
                                onchange="confirmResultAccess(this)" <?php echo e($status->result != 0 ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="result">
                                Acesso ao resultado:
                                <span class="badge bg-<?php echo e($status->result != 0 ? 'success' : 'danger'); ?> ms-2">
                                    <?php echo e($status->result != 0 ? 'Liberado' : 'Bloqueado'); ?>

                                </span>
                            </label>
                        </div>
                    </form>
                    
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                        <div class="mb-2 mb-md-0">
                            <label for="filter-status" class="form-label fw-semibold me-2">Filtrar por situação:</label>
                            <select id="filter-status" class="form-select form-select-sm w-auto d-inline-block">
                                <option value="all" selected>Todos</option>
                                <option value="classificado">Classificados</option>
                                <option value="empate">Empatados</option>
                                <option value="desclassificado">Desclassificados</option>
                            </select>
                        </div>

                        
                        <div class="small text-center text-md-end">
                            <span class="badge bg-success me-2">Classificado</span>
                            <span class="badge bg-warning text-dark me-2">Empate</span>
                            <span class="badge bg-danger">Desclassificado</span>
                        </div>
                    </div>
                    
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Pesquisar por nome ou inscrição" autocomplete="off">
                    </div>
                <?php endif; ?>
                
                <div class="table-responsive mt-3" style="max-height: 500px; overflow-y: auto;">
                    <table id="classification" class="table table-striped mb-0 caption-top">
                        <caption><?php echo e(config('app.name')); ?> <?php echo e(config('app.year')); ?> - Lista de Classificação Geral
                        </caption>

                        <thead class="table-success">
                            <tr>
                                <th>Classificação</th>
                                <th>Inscrição</th>
                                <th>Nome</th>
                                <th>Nascimento</th>
                                <th>Nota</th>
                                <th>Situação</th>
                            </tr>
                        </thead>

                        <tbody id="results" class="table-group-divider">
                            <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $isDirectClassified = $result->ranking <= $limit;
                                    $isTieClassified = !$isDirectClassified && $result->score == $cutoffScore;
                                    $isClassified = $isDirectClassified || $isTieClassified;

                                    // Define a cor da linha conforme a situação
                                    $rowClass = $isDirectClassified
                                        ? 'table-success'
                                        : ($isTieClassified
                                            ? 'table-warning'
                                            : 'table-light');
                                ?>
                                <tr class="<?php echo e($rowClass); ?>"
                                    data-status="<?php echo e($isDirectClassified ? 'classificado' : ($isTieClassified ? 'empate' : 'desclassificado')); ?>">
                                    <th scope="col"><?php echo e($result->ranking); ?>º</th>
                                    <td><?php echo e($result->id); ?></td>
                                    <th><?php echo e($result->social_name ? $result->social_name : $result->name); ?></th>
                                    <td><?php echo e(\Carbon\Carbon::parse($result->birth)->format('d/m/Y')); ?></td>
                                    <td><?php echo e($result->score); ?></td>
                                    <td>
                                        <?php if($isDirectClassified): ?>
                                            <span class="badge bg-success">CLASSIFICADO</span>
                                        <?php elseif($isTieClassified): ?>
                                            <span class="badge bg-warning text-dark">CLASSIFICADO</span>
                                            <small class="text-muted d-block">(Empate na nota de corte)</small>
                                        <?php else: ?>
                                            <span class="badge bg-danger">DESCLASSIFICADO</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum resultado encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/filters/results.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/swa/ranking/results.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dash.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/results/private/index.blade.php ENDPATH**/ ?>