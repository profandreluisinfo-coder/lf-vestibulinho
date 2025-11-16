<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/calendar/styles.css')); ?>">
<?php $__env->stopPush(); ?>
<section id="calendary" class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            Calendário do Processo Seletivo <?php echo e($calendar->year); ?>

        </h2>

        <?php if($calendar): ?>
            <div class="row">
                <div class="col-lg-10 mx-auto">

                    <div class="calendar-horizontal-wrapper">
                        <div class="calendar-horizontal">

                            
                            <div class="cal-item <?php echo e(!$calendar->hasInscriptionStarted() ? 'cal-item-inactive' : ($calendar->isInscriptionOpen() ? 'cal-item-active' : 'cal-item-completed')); ?>">
                                <div class="cal-icon bg-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </div>
                                <div class="cal-card">
                                    <h5 class="fw-bold mb-1">Período de Inscrições</h5>
                                    <p class="small text-muted mb-2">Inscrições abertas para todos os cursos</p>
                                    <span class="badge <?php echo e($calendar->isInscriptionOpen() ? 'bg-success' : ($calendar->hasInscriptionStarted() ? 'bg-secondary' : 'bg-warning')); ?> p-2">
                                        <?php echo e(Carbon\Carbon::parse($calendar->inscription_start)->format('d/m/Y')); ?>

                                        até
                                        <?php echo e(Carbon\Carbon::parse($calendar->inscription_end)->format('d/m/Y')); ?>

                                    </span>
                                    <?php if(!$calendar->hasInscriptionStarted()): ?>
                                        <span class="cal-status-badge">Em Breve</span>
                                    <?php elseif($calendar->isInscriptionOpen()): ?>
                                        <span class="cal-status-badge cal-status-active">Em Andamento</span>
                                    <?php else: ?>
                                        <span class="cal-status-badge cal-status-completed">Concluído</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="cal-item <?php echo e(!$calendar->hasInscriptionEnded() ? 'cal-item-inactive' : ($calendar->hasExamDatePassed() ? 'cal-item-completed' : 'cal-item-active')); ?>">
                                <div class="cal-icon bg-danger">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <div class="cal-card">
                                    <h5 class="fw-bold mb-1">Aplicação das Provas</h5>
                                    <p class="small text-muted mb-2">Prova objetiva para todos os candidatos</p>
                                    <span class="badge <?php echo e($calendar->hasExamDatePassed() ? 'bg-secondary' : ($calendar->hasInscriptionEnded() ? 'bg-danger' : 'bg-warning')); ?> p-2">
                                        <?php echo e(Carbon\Carbon::parse($calendar->exam_date)->format('d/m/Y')); ?>

                                    </span>
                                    <?php if(!$calendar->hasInscriptionEnded()): ?>
                                        <span class="cal-status-badge">Em Breve</span>
                                    <?php elseif(!$calendar->hasExamDatePassed()): ?>
                                        <span class="cal-status-badge cal-status-active">Próximo</span>
                                    <?php else: ?>
                                        <span class="cal-status-badge cal-status-completed">Concluído</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="cal-item <?php echo e(!$calendar->hasExamDatePassed() ? 'cal-item-inactive' : ($calendar->isFinalResultPublished() ? 'cal-item-completed' : 'cal-item-active')); ?>">
                                <div class="cal-icon bg-success">
                                    <i class="bi bi-trophy"></i>
                                </div>
                                <div class="cal-card">
                                    <h5 class="fw-bold mb-1">Resultado Final</h5>
                                    <p class="small text-muted mb-2">Classificação geral dos candidatos</p>
                                    <span class="badge <?php echo e($calendar->isFinalResultPublished() ? 'bg-success' : ($calendar->hasExamDatePassed() ? 'bg-warning' : 'bg-secondary')); ?> p-2">
                                        <?php echo e(Carbon\Carbon::parse($calendar->final_result_publish)->format('d/m/Y')); ?>

                                    </span>
                                    <?php if(!$calendar->hasExamDatePassed()): ?>
                                        <span class="cal-status-badge">Em Breve</span>
                                    <?php elseif(!$calendar->isFinalResultPublished()): ?>
                                        <span class="cal-status-badge cal-status-active">Aguardando</span>
                                    <?php else: ?>
                                        <span class="cal-status-badge cal-status-completed">Publicado</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?php echo e(route('calendary')); ?>" class="btn btn-sm btn-primary">
                            Ver Calendário Completo
                        </a>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>
</section><?php /**PATH C:\laragon\www\secretaria\resources\views/home/calendar.blade.php ENDPATH**/ ?>