<section id="provas-anteriores" class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Provas Anteriores</h2>
        <div class="row g-4 d-flex justify-content-center">
        <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-file-pdf text-danger mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Vestibulinho <?php echo e($file->year); ?></h5>
                        <p class="card-text">Prova completa para download</p>
                        <a href="<?php echo e(asset('storage/' . $file->file)); ?>" target="_blank"
                            class="btn btn-outline-primary">
                            <i class="bi bi-download me-2"></i>Download
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if($files->isNotEmpty()): ?>
            <div class="mt-4 text-center">
                <a href="<?php echo e(route('archives')); ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-search me-2"></i>Ver mais provas anteriores
                </a>
            </div>
        <?php endif; ?>
        </div>
    </div>
</section><?php /**PATH C:\laragon\www\secretaria\resources\views/home/previous-exams.blade.php ENDPATH**/ ?>