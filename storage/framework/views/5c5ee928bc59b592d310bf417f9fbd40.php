
<?php if(session('success')): ?>
  <script>
    showAlert('success', 'Sucesso!', '<?php echo e(session('success')); ?>', 'Ok', true);
  </script>
<?php endif; ?>


<?php if(session('error')): ?>
  <script>
    showAlert('error', 'Erro!', '<?php echo e(session('error')); ?>', 'Ok', true);
  </script>
<?php endif; ?>


<?php if(session('warning')): ?>
  <script>
    showAlert('warning', 'Atenção!', '<?php echo e(session('warning')); ?>', 'Ok', true);
  </script>
<?php endif; ?>


<?php if(session('info')): ?>
  <script>
    showAlert('info', 'Informação', '<?php echo e(session('info')); ?>', 'Ok', false);
  </script>
<?php endif; ?>


<?php if($errors->any()): ?>
  <script>
    showAlert('error', 'Erros no formulário', `<?php echo implode('<br>', $errors->all()); ?>`, 'Corrigir', true);
  </script>
<?php endif; ?><?php /**PATH C:\laragon\www\secretaria\resources\views/partials/alerts/admins.blade.php ENDPATH**/ ?>