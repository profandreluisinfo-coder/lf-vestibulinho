

<?php $__env->startSection('page-title', config('app.name') . ' ' . $calendar?->year . ' | Importar Notas'); ?>

<?php $__env->startSection('dash-content'); ?>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-upload me-2"></i>Importar Notas</h4>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('import.results')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="file" class="form-label">Selecione a planilha (.xlsx):</label>
                        <div class="alert alert-warning">
                            <strong>Atenção:</strong> A planilha deve conter obrigatoriamente os seguintes cabeçalhos, nesta
                            ordem:<br>
                            <code>inscription_id | user_id | user_cpf | user_name | user_birth | points</code><br>
                            Certifique-se de que esses nomes estão exatamente assim, sem espaços extras ou acentos.
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mb-3 float-end" data-bs-toggle="modal"
                            data-bs-target="#exampleSheet"><i class="bi bi-search me-2"></i>Ver Modelo</button>
                        <input type="file" name="file" id="file" accept=".xlsx"
                            class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Importar Notas</button>
                </form>
            </div>
        </div>
        <!-- The Modal -->
        <div class="modal" id="exampleSheet">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="bi bi-file-earmark-excel me-2"></i>Modelo de cabeçalho de planilha de importacao de notas</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <img src="<?php echo e(asset('assets/img/modelo_importacao_notas.png')); ?>" class="img-fluid" alt="Modelo de cabeçalho da planilha de importacao_notas">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dash.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/import/private/index.blade.php ENDPATH**/ ?>