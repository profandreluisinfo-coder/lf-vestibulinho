<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php echo $__env->yieldPushContent('metas'); ?>
    <title><?php echo $__env->yieldContent('page-title', 'Vestibulinho 2026'); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/layouts/home.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php echo $__env->yieldPushContent('head-scripts'); ?>
</head>

<body class="<?php echo $__env->yieldContent('body-class'); ?>">
<?php echo $__env->yieldContent('content'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('plugins'); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html><?php /**PATH C:\laragon\www\secretaria\resources\views/layouts/home/master.blade.php ENDPATH**/ ?>