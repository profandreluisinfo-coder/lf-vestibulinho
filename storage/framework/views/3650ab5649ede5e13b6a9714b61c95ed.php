<ul class="nav nav-pills steps-menu flex-column">
  <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('personal') ? 'active' : ''); ?> <?php echo e(!session()->has('step1_done') ? 'disabled-link' : ''); ?> <?php echo e(session()->has('step1_done') ? 'completed' : ''); ?>"
      href="<?php echo e(session()->has('step1_done') ? route('step.personal') : '#'); ?>">
      <span class="step-number">1</span>
      <span class="step-title">Dados Pessoais</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('certificate') ? 'active' : ''); ?>  <?php echo e(!session()->has('step2_done') ? 'disabled-link' : ''); ?>  <?php echo e(session()->has('step2_done') ? 'completed' : ''); ?>"
       href="<?php echo e(session()->has('step1_done') ? route('step.certificate') : '#'); ?>">
      <span class="step-number">2</span>
      <span class="step-title">Certidão de Nascimento</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('address') ? 'active' : ''); ?> <?php echo e(!session()->has('step3_done') ? 'disabled-link' : ''); ?> <?php echo e(session()->has('step3_done') ? 'completed' : ''); ?>"
      href="<?php echo e(session()->has('step2_done') ? route('step.address') : '#'); ?>">
      <span class="step-number">3</span>
      <span class="step-title">Endereço</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('academic') ? 'active' : ''); ?> <?php echo e(!session()->has('step3_done') ? 'disabled-link' : ''); ?> <?php echo e(session()->has('step4_done') ? 'completed' : ''); ?>"
      href="<?php echo e(session()->has('step3_done') ? route('step.academic') : '#'); ?>">
      <span class="step-number">4</span>
      <span class="step-title">Dados Escolares</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('family') ? 'active' : ''); ?> <?php echo e(!session()->has('step4_done') ? 'disabled-link' : ''); ?> <?php echo e(session()->has('step5_done') ? 'completed' : ''); ?>"
      href="<?php echo e(session()->has('step4_done') ? route('step.family') : '#'); ?>">
      <span class="step-number">5</span>
      <span class="step-title">Filiação / Responsável Legal</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('other') ? 'active' : ''); ?> <?php echo e(!session()->has('step5_done') ? 'disabled-link' : ''); ?> <?php echo e(session()->has('step6_done') ? 'completed' : ''); ?>"
      href="<?php echo e(session()->has('step5_done') ? route('step.other') : '#'); ?>">
      <span class="step-number">6</span>
      <span class="step-title">Informações Complementares</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('course') ? 'active' : ''); ?> <?php echo e(!session()->has('step6') ? 'disabled-link' : ''); ?> <?php echo e(session()->has('step6_done') ? 'completed' : ''); ?>"
      href="<?php echo e(session()->has('step6_done') ? route('step.course') : '#'); ?>">
      <span class="step-number">7</span>
      <span class="step-title">Curso</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('forms.confirm') ? 'active' : ''); ?> <?php echo e(!session()->has('step7_done') ? 'disabled-link' : ''); ?> <?php echo e(session()->has('step_done') ? 'completed' : ''); ?>"
      href="<?php echo e(session()->has('step7') ? route('step.confirm') : '#'); ?>">
      <span class="step-number">8</span>
      <span class="step-title">Confirmar Dados</span>
    </a>
  </li>
</ul>
<?php /**PATH C:\laragon\www\secretaria\resources\views/partials/forms/steps.blade.php ENDPATH**/ ?>