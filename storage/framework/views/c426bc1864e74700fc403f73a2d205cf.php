
<?php if(session('success')): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Sucesso!',
      text: '<?php echo e(session('success')); ?>',
      confirmButtonText: 'Ok'
    });
  </script>
<?php endif; ?>


<?php if(session('error')): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: '<?php echo e(session('error')); ?>',
      confirmButtonText: 'Ok'
    });
  </script>
<?php endif; ?>


<?php if(session('warning')): ?>
  <script>
    Swal.fire({
      icon: 'warning',
      title: 'Atenção!',
      text: '<?php echo e(session('warning')); ?>',
      confirmButtonText: 'Ok'
    });
  </script>
<?php endif; ?>


<?php if(session('info')): ?>
  <script>
    Swal.fire({
      icon: 'info',
      title: 'Informação',
      text: '<?php echo e(session('info')); ?>',
      confirmButtonText: 'Ok'
    });
  </script>
<?php endif; ?>


<?php if($errors->any()): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Erros no formulário',
      html: `
                <ul style="text-align: left;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            `,
      confirmButtonText: 'Corrigir'
    });
  </script>
<?php endif; ?><?php /**PATH C:\laragon\www\secretaria\resources\views/partials/alerts/users.blade.php ENDPATH**/ ?>