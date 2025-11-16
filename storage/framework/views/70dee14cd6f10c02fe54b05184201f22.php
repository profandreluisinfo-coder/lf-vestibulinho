<section class="hero-banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="hero-content">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
                        <img src="<?php echo e(asset('assets/img/logo.webp')); ?>" class="img-fluid" alt="Avatar Logo" width="75">

                        <h1 class="display-4 fw-bold mb-3 mb-md-0">
                            Vestibulinho LF <?php echo e($calendar->year ?? ''); ?>

                        </h1>
                    </div>
                    <h2 class="h3 mb-4">
                        <div>Totalmente Gratuito!</div>
                        <?php if($calendar): ?>
                            <?php if(!$calendar?->hasInscriptionStarted()): ?>
                                <div>Em breve.</div>
                            <?php elseif($calendar?->isInscriptionOpen()): ?>
                                <div class="text-success-alt">Inscrições Abertas</div>
                            <?php else: ?>
                                <div class="text-danger-alt">Inscrições Encerradas</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </h2>
                    <p class="lead mb-4">
                        Garanta sua vaga em um dos nossos cursos técnicos de qualidade.
                        Processo seletivo sem taxa de inscrição com vagas para 4 cursos diferentes.
                    </p>
                    <div class="d-flex hero-buttons">
                        <?php if($calendar->isInscriptionOpen()): ?>
                            <a href="<?php echo e(route('register')); ?>" class="btn btn-success btn-lg" title="Inscrever-se Agora">
                                <i class="bi bi-pencil-square me-2"></i>Inscrever-se Agora
                            </a>
                        <?php endif; ?>
                        <a href="#cursos" class="btn btn-outline-light btn-lg" title="Conhecer Cursos">
                            <i class="bi bi-book me-2"></i>Conhecer Cursos
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <?php if($calendar?->isInscriptionOpen()): ?>
                    <div class="highlight-box">
                        <h3 class="h4 mb-3">
                            <i class="bi bi-calendar-check me-2"></i>Prazo Final
                        </h3>
                        <h4 class="fw-bold">
                            <?php echo e($calendar?->inscription_end?->translatedFormat('d \d\e F') ?? 'Aguardando Informações'); ?>

                        </h4>
                        <p class="mb-0">Não perca esta oportunidade!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section><?php /**PATH C:\laragon\www\secretaria\resources\views/home/hero-banner.blade.php ENDPATH**/ ?>