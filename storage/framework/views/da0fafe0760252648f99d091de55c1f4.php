<ul class="nav nav-pills justify-content-between steps-horizontal d-md-none mb-3"
    style="--progress: <?php echo e($progress / 100); ?>;">

    
    <li class="nav-item">
        <a class="nav-link text-center
      <?php echo e(request()->routeIs('personal') ? 'active' : ''); ?>

      <?php echo e(!session()->has('step1_done') ? 'disabled-link' : ''); ?>

      <?php echo e(session()->has('step1_done') ? 'completed' : ''); ?>"
            href="<?php echo e(session()->has('step1_done') ? route('step.personal') : '#'); ?>">
            1
        </a>
    </li>

    
    <li class="nav-item">
        <a class="nav-link text-center
      <?php echo e(request()->routeIs('certificate') ? 'active' : ''); ?>

      <?php echo e(!session()->has('step2_done') ? 'disabled-link' : ''); ?>

      <?php echo e(session()->has('step2_done') ? 'completed' : ''); ?>"
            href="<?php echo e(session()->has('step1_done') ? route('step.certificate') : '#'); ?>">
            2
        </a>
    </li>

    
    <li class="nav-item">
        <a class="nav-link text-center
      <?php echo e(request()->routeIs('address') ? 'active' : ''); ?>

      <?php echo e(!session()->has('step3_done') ? 'disabled-link' : ''); ?>

      <?php echo e(session()->has('step3_done') ? 'completed' : ''); ?>"
            href="<?php echo e(session()->has('step2_done') ? route('step.address') : '#'); ?>">
            3
        </a>
    </li>

    
    <li class="nav-item">
        <a class="nav-link text-center
      <?php echo e(request()->routeIs('academic') ? 'active' : ''); ?>

      <?php echo e(!session()->has('step4_done') ? 'disabled-link' : ''); ?>

      <?php echo e(session()->has('step4_done') ? 'completed' : ''); ?>"
            href="<?php echo e(session()->has('step3_done') ? route('step.academic') : '#'); ?>">
            4
        </a>
    </li>

    
    <li class="nav-item">
        <a class="nav-link text-center
      <?php echo e(request()->routeIs('family') ? 'active' : ''); ?>

      <?php echo e(!session()->has('step5_done') ? 'disabled-link' : ''); ?>

      <?php echo e(session()->has('step5_done') ? 'completed' : ''); ?>"
            href="<?php echo e(session()->has('step4_done') ? route('step.family') : '#'); ?>">
            5
        </a>
    </li>

    
    <li class="nav-item">
        <a class="nav-link text-center
      <?php echo e(request()->routeIs('other') ? 'active' : ''); ?>

      <?php echo e(!session()->has('step6_done') ? 'disabled-link' : ''); ?>

      <?php echo e(session()->has('step6_done') ? 'completed' : ''); ?>"
            href="<?php echo e(session()->has('step5_done') ? route('step.other') : '#'); ?>">
            6
        </a>
    </li>

    
    <li class="nav-item">
        <a class="nav-link text-center
      <?php echo e(request()->routeIs('course') ? 'active' : ''); ?>

      <?php echo e(!session()->has('step6_done') ? 'disabled-link' : ''); ?>

      <?php echo e(session()->has('step7_done') ? 'completed' : ''); ?>"
            href="<?php echo e(session()->has('step6_done') ? route('step.course') : '#'); ?>">
            7
        </a>
    </li>

    
    <li class="nav-item">
        <a class="nav-link text-center
      <?php echo e(request()->routeIs('forms.confirm') ? 'active' : ''); ?>

      <?php echo e(!session()->has('step7_done') ? 'disabled-link' : ''); ?>

      <?php echo e(session()->has('step_done') ? 'completed' : ''); ?>"
            href="<?php echo e(session()->has('step7_done') ? route('step.confirm') : '#'); ?>">
            8
        </a>
    </li>
</ul>
<?php /**PATH C:\laragon\www\secretaria\resources\views/partials/forms/hsteps.blade.php ENDPATH**/ ?>