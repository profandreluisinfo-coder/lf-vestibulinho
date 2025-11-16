<section id="faq" class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center">Dúvidas Frequentes</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
            <?php if($faqs->isNotEmpty()): ?>
                <div class="accordion accordion-flush" id="faqAccordion">
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading<?php echo e($faq->id); ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse<?php echo e($faq->id); ?>" aria-expanded="false"
                                    aria-controls="collapse<?php echo e($faq->id); ?>">
                                    <?php echo e($faq->question); ?>

                                </button>
                            </h2>
                            <div id="collapse<?php echo e($faq->id); ?>" class="accordion-collapse collapse"
                                aria-labelledby="heading<?php echo e($faq->id); ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?php echo e($faq->answer); ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="text-center">
                    <a href="<?php echo e(route('questions')); ?>" class="btn btn-sm btn-primary">Ver todas as perguntas</a>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</section><?php /**PATH C:\laragon\www\secretaria\resources\views/home/faq.blade.php ENDPATH**/ ?>