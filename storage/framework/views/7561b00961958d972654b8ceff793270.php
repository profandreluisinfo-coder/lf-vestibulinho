

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/steps/hsteps.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('head-scripts'); ?>
    <script src="<?php echo e(asset('assets/js/reloadif.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('dash-content'); ?>
    <div class="row justify-content-center">
        <div class="col-central col-12">
            <div class="text-danger fst-italic mb-1 text-end">
                <span class="required"></span> Indica um campo obrigatório
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 py-3">
            <div class="card shadow-lg">
                <div class="card-header text-white">
                    <h6 class="mb-0"><span class="badge rounded-pill <?php echo e($bg); ?> me-2"><?php echo e($step); ?></span>
                        <?php echo e($title); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-lg-3 mb-3 p-3">
                            <?php echo $__env->make('partials.forms.steps', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php
                                $totalSteps = 8;
                                $completed = 0;

                                for ($i = 1; $i <= $totalSteps; $i++) {
                                    if (session()->has('step' . $i . '_done')) {
                                        $completed++;
                                    }
                                }

                                $progress = $completed / $totalSteps;
                            ?>
                            <?php echo $__env->make('partials.forms.hsteps', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <div class="forms col-md-8 col-lg-9 p-3">

                            <?php echo $__env->make('partials.forms.progress-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                            <?php echo $__env->yieldContent('forms'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('plugins'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/addons/cleave-phone.br.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dash.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/layouts/forms/master.blade.php ENDPATH**/ ?>