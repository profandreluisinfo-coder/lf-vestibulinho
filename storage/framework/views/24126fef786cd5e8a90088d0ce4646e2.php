<?php
    $alertMap = [
        'error' => [
            'class' => 'danger',
            'title' => 'Erro!',
            'icon'  => 'exclamation-octagon-fill'
        ],
        'warning' => [
            'class' => 'warning',
            'title' => 'Atenção!',
            'icon'  => 'exclamation-triangle-fill'
        ],
        'success' => [
            'class' => 'success',
            'title' => 'Sucesso!',
            'icon'  => 'check-circle-fill'
        ],
        'info' => [
            'class' => 'info',
            'title' => 'Informação!',
            'icon'  => 'info-circle-fill'
        ],
    ];
?>

<?php $__currentLoopData = $alertMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(session($key)): ?>

        <div class="alert alert-<?php echo e($alert['class']); ?> border-0 shadow-sm py-3 fade show 
                    rounded-3 animate__animated animate__fadeIn">
            
            <div class="fw-bold text-center mb-1">
                <i class="bi bi-<?php echo e($alert['icon']); ?> me-1"></i>
                <?php echo e($alert['title']); ?>

            </div>

            <div class="text-center">
                <?php echo e(session($key)); ?>

            </div>
        </div>

    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/components/alerts.blade.php ENDPATH**/ ?>