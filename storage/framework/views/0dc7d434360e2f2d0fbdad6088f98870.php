<?php
     $statusData = $calendar?->getInscriptionStatusData() ?? [
        'color' => 'secondary',
        'icon' => 'bi-calendar-x',
        'title' => 'Sem Calendário Ativo',
        'message' => 'Nenhum calendário está ativo no momento.',
        'show_button' => false,
    ];

    // Convocação / Matrícula
    $chamadaDisponivel = $calls_exists;
    $matriculaColor    = $chamadaDisponivel ? 'info' : 'secondary';
    $matriculaIcon     = $chamadaDisponivel ? 'bi-megaphone-fill' : 'bi-hourglass-split';
    $matriculaTitle    = $chamadaDisponivel ? 'Convocação para Matrícula Disponível' : 'Aguardando Convocação';
?>

<section id="calendario" class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-4">
            Informações do <?php echo e(config('app.name')); ?> <?php echo e($calendar?->year); ?>

        </h2>
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="row g-4">
                
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <i class="bi <?php echo e($statusData['icon']); ?> text-<?php echo e($statusData['color']); ?> fs-3 me-3"></i>
                        <div>
                            <h6 class="fw-semibold text-<?php echo e($statusData['color']); ?> mb-1">
                                <?php echo e($statusData['title']); ?>

                            </h6>
                            <p class="text-muted small mb-2">
                                <?php echo $statusData['message']; ?>

                            </p>
                            <?php if($statusData['show_button']): ?>
                                <a href="<?php echo e(route('register')); ?>" 
                                   class="btn btn-sm btn-<?php echo e($statusData['color']); ?> px-3 text-white">
                                    Inscrever-se
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <?php if($settings->location): ?>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-primary fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-semibold text-primary mb-1">Locais de Prova Disponíveis</h6>
                                <p class="text-muted small mb-2">
                                    Os locais de prova já estão disponíveis.
                                    Acesse a
                                    <a href="<?php echo e(route('login')); ?>"
                                        class="fw-semibold text-primary text-decoration-none">
                                        Área do Candidato
                                    </a>.
                                </p>
                                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary-alt btn-sm px-3 text-white">
                                    Acessar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if($settings->result): ?>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-semibold text-success mb-1">Resultados Publicados</h6>
                                <p class="text-muted small mb-2">
                                    O resultado do processo seletivo está disponível.
                                    Veja na
                                    <a href="<?php echo e(route('results')); ?>"
                                        class="fw-semibold text-success text-decoration-none">
                                        página de resultados
                                    </a>.
                                </p>
                                <a href="<?php echo e(route('results')); ?>" class="btn btn-success btn-sm px-3">
                                    Ver Resultado
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if($chamadaDisponivel): ?>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="bi <?php echo e($matriculaIcon); ?> text-<?php echo e($matriculaColor); ?> fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-semibold text-<?php echo e($matriculaColor); ?> mb-1"><?php echo e($matriculaTitle); ?></h6>
                                <p class="text-muted small mb-2">
                                    <?php if($chamadaDisponivel): ?>
                                        A convocação para matrícula dos classificados na
                                        <strong>1ª chamada</strong> está disponível!
                                        Confira a lista e siga as instruções para matrícula.
                                    <?php endif; ?>
                                </p>
                                <a href="<?php echo e(route('calls')); ?>"
                                    class="btn btn-<?php echo e($matriculaColor); ?> btn-sm px-3 text-white">
                                    Ver Convocação
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>
<?php /**PATH C:\laragon\www\secretaria\resources\views/home/informations.blade.php ENDPATH**/ ?>