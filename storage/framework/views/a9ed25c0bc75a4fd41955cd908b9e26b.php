<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/quick-access/styles.css')); ?>">
<?php $__env->stopPush(); ?>

<section id="acesso-rapido" class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center">Acesso Rápido</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center text-center">
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm <?php echo e(!$notice->status ? 'card-inactive' : ''); ?>">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-file-text quick-access-icon fs-1 text-<?php echo e($notice->status ? 'success' : 'primary'); ?>"></i>
                        <h5 class="card-title mt-3">Edital</h5>
                        <p class="card-text">Baixe o edital completo</p>
                        <div class="mt-auto">
                        <?php if($notice->status): ?>
                            <a href="<?php echo e(asset('storage/' . $notice->file)); ?>" class="btn btn-sm btn-success" title="Leia o edital na íntegra" target="_blank">Ler Edital</a>
                        <?php else: ?>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="Baixe o edital e manual completo">Ler Edital</a>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($calendar?->isInscriptionOpen()): ?>
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-person-plus quick-access-icon fs-1 text-success"></i>
                        <h5 class="card-title mt-3">Fazer Inscrição</h5>
                        <p class="card-text">Inscreva-se gratuitamente</p>
                        <div class="mt-auto">
                            <a href="<?php echo e(route('register')); ?>" class="btn btn-sm btn-success">Inscreva-se</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm <?php echo e(!$calendar->hasInscriptionStarted() ? 'card-inactive' : ''); ?>">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-person quick-access-icon fs-1 text-<?php echo e($calendar->hasInscriptionStarted() ? 'success' : 'secondary'); ?>"></i>
                        <h5 class="card-title mt-3">Área do Candidato</h5>
                        <p class="card-text">Verifique o status da sua inscrição</p>
                        <div class="mt-auto">
                        <?php if($calendar->hasInscriptionStarted()): ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-success">Acessar</a>
                        <?php else: ?>
                            <a href="javascript:void(0);" class="btn btn-sm btn-secondary" disabled>Em Breve</a>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm <?php echo e(!$settings->result ? 'card-inactive' : ''); ?>">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-trophy quick-access-icon fs-1 text-<?php echo e($settings->result ? 'success' : 'secondary'); ?>"></i>
                        <h5 class="card-title mt-3">Resultados</h5>
                        <p class="card-text">Confira os resultados publicados</p>
                        <div class="mt-auto">
                          <?php if($settings->result): ?>
                            <a href="<?php echo e(route('results')); ?>" class="btn btn-sm btn-success" title="Confira os resultados publicados">Visualizar</a>
                          <?php else: ?>
                            <a href="javascript:void(0);" class="btn btn-sm btn-secondary" disabled title="Resultados ainda não disponíveis">Em Breve</a>
                          <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm <?php echo e(!$calls ? 'card-inactive' : ''); ?>">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-megaphone quick-access-icon fs-1 text-<?php echo e($calls ? 'success' : 'secondary'); ?>"></i>
                        <h5 class="card-title mt-3">Matrículas</h5>
                        <p class="card-text">Acompanhe as convocações para realização das matrículas</p>
                        <div class="mt-auto">
                          <?php if($calls): ?>
                            <a href="<?php echo e(route('calls')); ?>" class="btn btn-sm btn-success" title="Acompanhe as convocações para realização das matrículas">Convocações</a>
                          <?php else: ?>
                            <a href="javascript:void(0);" class="btn btn-sm btn-secondary" disabled title="Convocações ainda não disponíveis">Em Breve</a>
                          <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/quick-access.js')); ?>"></script>
<?php $__env->stopPush(); ?><?php /**PATH C:\laragon\www\secretaria\resources\views/home/quick-access.blade.php ENDPATH**/ ?>