
<?php
    // Total de etapas que o formulário possui
    $totalSteps = 8;

    // Contar quantas etapas já foram completadas (estão na sessão)
    $completedSteps = 0;
    for ($i = 1; $i <= $totalSteps; $i++) {
        if (session("step{$i}_done")) {
            $completedSteps++;
        }
    }

    // Calcular progresso em porcentagem
    $progress = intval(($completedSteps / $totalSteps) * 100);

    // Definir cor da barra com base no progresso
    if ($progress === 100) {
        $bg = 'bg-success';
    } elseif ($progress >= 50) {
        $bg = 'bg-info';
    } elseif ($progress > 0) {
        $bg = 'bg-warning';
    } else {
        $bg = 'bg-secondary';
    }
?>

<div class="progress mb-3">
    <div class="progress-bar <?php echo e($bg); ?>" style="width: <?php echo e($progress); ?>%">
        <?php echo e($progress); ?>%
    </div>
</div><?php /**PATH C:\laragon\www\secretaria\resources\views/partials/forms/progress-bar.blade.php ENDPATH**/ ?>