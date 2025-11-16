<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color) 0%, #1e3a5f 100%);">
    <div class="container text-center text-white">
        <h2 class="mb-4">Não Perca Esta Oportunidade!</h2>
        <p class="lead mb-4">
            Invista no seu futuro profissional. Inscreva-se agora no Vestibulinho LF <?php echo e($calendar?->year); ?> 
            e garanta sua vaga em um curso técnico de qualidade.
        </p>

        <?php if($calendar?->isInscriptionOpen()): ?>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-success btn-lg" title="Inscrever-se Agora">
                <i class="bi bi-pencil-square me-2"></i> Fazer Inscrição
            </a>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH C:\laragon\www\secretaria\resources\views/home/cta.blade.php ENDPATH**/ ?>