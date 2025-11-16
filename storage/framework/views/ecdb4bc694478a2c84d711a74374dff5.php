

<?php $__env->startSection('page-title', config('app.name') . ' ' . $calendar?->year . ' | Redefinir Dados do Sistema'); ?>

<?php $__env->startSection('dash-content'); ?>
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-database-dash me-2"></i>Redefinir Dados do Sistema</h4>
        </div>

        <div class="card border-danger shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-danger fw-bold align-items-middle"><i
                        class="bi bi-exclamation-diamond me-2"></i>Atenção</h5>
                <p class="card-text">
                    Esta operação irá <strong>apagar todos os dados dos usuários e de suas inscrições, além do calendário
                        atual do processo seletivo e os eventos relacionados a ele.</strong><br>As FAQs, o edital e os
                    arquivos das provas anteriores serão mantidos.
                    <br>Os dados não poderão ser recuperados após a exclusão.
                </p>

                <div class="d-flex gap-2">
                    <button class="btn btn-danger" id="btn-reset-system" onclick="resetSystem()">
                        <i class="bi bi-trash me-1"></i> Redefinir Sistema
                    </button>
                </div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        const resetUrl = "<?php echo e(route('system.reset')); ?>";
    </script>
    <script src="<?php echo e(asset('assets/swa/system/reset.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dash.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/system/private/index.blade.php ENDPATH**/ ?>