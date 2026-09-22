<div class="stepper-container">

    <!-- Etapa 1 -->
    <a href="{{ route('admin.process.show') }}" class="step-item text-secondary text-center" title="Definir Calendário">
        <i class="bi {{ $process_status ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
        <p class="mt-2 mb-0 fw-semibold">Definir calendário</p>
    </a>

    <div class="step-divider"></div>

    <!-- Etapa 3 -->
    <a href="{{ route('admin.exam.create') }}" class="step-item text-secondary text-center" title="Agendar Prova">
        <i
            class="bi {{ $local_status ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
        <p class="mt-2 mb-0 fw-semibold">Agendar prova</p>
    </a>

    <div class="step-divider"></div>

    <!-- Etapa 4 -->
    <a href="{{ route('admin.import.home') }}" class="step-item text-secondary text-center" title="Importar Notas">
        <i
            class="bi {{ $ranking_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
        <p class="mt-2 mb-0 fw-semibold">Importar notas</p>
    </a>

    <div class="step-divider"></div>

    <!-- Etapa 5 -->
    <a href="{{ route('admin.system.publish.result') }}" class="step-item text-secondary text-center">
        <i
            class="bi {{ $settings->isResultEnabled() ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
        <p class="mt-2 mb-0 fw-semibold">Publicar resultados</p>
    </a>

    <div class="step-divider"></div>

    <!-- Etapa 6 -->
    <a href="{{ route('admin.calls.index') }}" class="step-item text-secondary text-center" title="Definir Chamadas">
        <i
            class="bi {{ $calls_exists ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
        <p class="mt-2 mb-0 fw-semibold">Definir chamadas</p>
    </a>

</div>