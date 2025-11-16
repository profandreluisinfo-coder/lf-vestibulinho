

<?php $__env->startSection('page-title', config('app.name') . ' ' . $calendar?->year . ' | Provas'); ?>

<?php $__env->startSection('dash-content'); ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-geo me-2"></i>Locais de Prova</h4>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setLocalModal">
            <i class="bi bi-plus-circle me-1"></i> Novo Local
        </a>
    </div>

    <div class="table-responsive">
        <table class="table-striped table caption-top">
            <caption><?php echo e(config('app.name')); ?> <?php echo e($calendar?->year); ?> - Lista de Locais de Prova</caption>
            <thead class="table-success text-center">
                <tr>
                    
                    <th scope="col">Local</th>
                    
                    <th scope="col">Salas Disponíveis</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php $__empty_1 = true; $__currentLoopData = $examLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="text-center">
                        <th scope="row">
                            <?php echo e($location->name); ?>

                        </th>
                        
                        <td><?php echo e($location->rooms_available); ?></td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <!-- Excluir -->
                                <form id="delete-location-form-<?php echo e($location->id); ?>"
                                    action="<?php echo e(route('exam.location.destroy', $location->id)); ?>" method="POST"
                                    style="display:none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                                <!-- Botão de excluir -->
                                <button type="button"
                                    onclick="confirmLocationDelete(<?php echo e($location->id); ?>, '<?php echo e(addslashes($location->name)); ?>')"
                                    class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash me-2"></i> Excluir
                                </button>
                                <!-- Editar -->
                                <button type="button" class="btn btn-sm btn-primary">
                                    <a href="<?php echo e(route('exam.location.update', $location->id)); ?>"
                                        class="text-white text-decoration-none">
                                        <i class="bi bi-pencil-square me-2" title="Editar"></i> Editar
                                    </a>
                                </button>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhum local registrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <div class="modal fade" id="setLocalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setLocalModalLabel"><i class="bi bi-geo me-2"></i>Cadastrar Local de Prova</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form id="exam-location" action="<?php echo e(route('exam.locations')); ?>" method="POST"
                                novalidate>
                                <?php echo csrf_field(); ?>

                                
                                <div class="form-floating mb-3">
                                    <input type="text" name="name"
                                        class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name"
                                        placeholder="Nome do local" value="<?php echo e(old('name')); ?>" required>
                                    <label for="name" class="form-label required">Nome do Local</label>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="form-floating mb-3">
                                    <input type="text" name="address"
                                        class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address"
                                        placeholder="Endereço" value="<?php echo e(old('address')); ?>" required>
                                    <label for="address" class="form-label required">Endereço</label>
                                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="form-floating mb-4">
                                    <input type="number" name="rooms_available"
                                        class="form-control <?php $__errorArgs = ['rooms_available'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="rooms_available" min="1" max="40"
                                        placeholder="Total de Salas Disponíveis" value="<?php echo e(old('rooms_available')); ?>"
                                        required>
                                    <label for="rooms_available" class="form-label required">Total de Salas
                                        Disponíveis</label>
                                    <?php $__errorArgs = ['rooms_available'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Cadastrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('plugins'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/rules/exam.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/swa/locations/delete.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dash.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/locations/private/list.blade.php ENDPATH**/ ?>