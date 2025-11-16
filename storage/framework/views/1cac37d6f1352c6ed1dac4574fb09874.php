

<?php $__env->startSection('page-title', config('app.name') . ' ' . config('app.year') . ' | Nova FaQ'); ?>

<?php $__env->startPush('datatable-styles'); ?>
    <!-- datatables -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')); ?>">
    <!-- // datatables -->
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/faqs/styles2.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('dash-content'); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-question-circle me-2"></i>Perguntas Frequentes</h4>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setNewFAQ">
                <i class="bi bi-plus-circle me-2"></i> Nova
            </a>
        </div>

        <?php if($faqs->isNotEmpty()): ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Pesquisar por.." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="filterPublished" checked>
                        <label class="form-check-label" for="filterPublished">
                            Publicadas
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="0" id="filterUnpublished" checked>
                        <label class="form-check-label" for="filterUnpublished">
                            Não Publicadas
                        </label>
                    </div>
                </div>
            </div>
            <div class="accordion accordion-flush" id="faqAccordion">
                <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo e($faq->id); ?>">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse<?php echo e($faq->id); ?>" aria-expanded="false"
                                aria-controls="collapse<?php echo e($faq->id); ?>">
                                <?php echo e($faq->question); ?>

                            </button>
                        </h2>

                        <div id="collapse<?php echo e($faq->id); ?>" class="accordion-collapse collapse"
                            aria-labelledby="heading<?php echo e($faq->id); ?>" data-bs-parent="#faqAccordion">

                            <div class="accordion-body">
                                <?php echo e($faq->answer); ?>


                                <div
                                    class="d-flex justify-content-end border-bottom small text-muted mt-2 mb-2 p-2 border border-top-1">
                                    <span class="me-2">Autor: <?php echo e($faq->user->name); ?></span> |
                                    <span class="mx-2">Criado em: <?php echo e($faq->created_at->format('d/m/Y H:i:s')); ?></span> |
                                    <span class="mx-2">Alterado em: <?php echo e($faq->updated_at->format('d/m/Y H:i:s')); ?></span>
                                    |
                                    <span class="ms-2">Status:
                                        <span class="badge bg-<?php echo e($faq->status ? 'success' : 'warning'); ?>">
                                            <?php echo e($faq->status ? 'Publicado' : 'Não Publicado'); ?>

                                        </span>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-faq', $faq)): ?>
                                        <form id="publish-faq-form-<?php echo e($faq->id); ?>"
                                            action="<?php echo e(route('faq.publish', $faq->id)); ?>" method="POST" class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                        </form>
                                        <button type="button"
                                            class="btn btn-sm btn-<?php echo e($faq->status ? 'warning' : 'success'); ?>"
                                            onclick="confirmFaqPublish(<?php echo e($faq->id); ?>, '<?php echo e(addslashes($faq->question)); ?>')">
                                            <i class="bi bi-<?php echo e($faq->status ? 'eye-slash' : 'eye'); ?> me-1"></i>
                                            <?php echo e($faq->status ? 'Não Publicar' : 'Publicar'); ?>

                                        </button>
                                    <?php endif; ?>

                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-faq', $faq)): ?>
                                        <a href="<?php echo e(route('faq.edit', $faq->id)); ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil-square me-1" title="Editar"></i> Editar
                                        </a>
                                    <?php endif; ?>

                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-faq', $faq)): ?>
                                        <form id="delete-faq-form-<?php echo e($faq->id); ?>"
                                            action="<?php echo e(route('faq.destroy', $faq->id)); ?>" method="POST" class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmFaqDelete(<?php echo e($faq->id); ?>, '<?php echo e(addslashes($faq->question)); ?>')">
                                            <i class="bi bi-trash me-1"></i> Excluir
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Nenhuma pergunta cadastrada ainda.
            </div>
        <?php endif; ?>

        
        <div class="modal fade" id="setNewFAQ" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="setNewFAQModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setLocalModalLabel">Gravar Nova FaQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <form action="<?php echo e(route('faq.store')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label for="question" class="form-label required">Pergunta:</label>
                                        <input type="text" class="form-control" id="question" name="question">
                                    </div>
                                    <div class="mb-3">
                                        <label for="answer" class="form-label required">Resposta:</label>
                                        <textarea class="form-control" id="answer" name="answer" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gravar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('plugins'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/swa/faqs/publish.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/swa/faqs/delete.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/filters/faqs.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.dash.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/faqs/private/index.blade.php ENDPATH**/ ?>