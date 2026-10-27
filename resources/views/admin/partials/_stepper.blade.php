<div class="stepper-container">

    <!-- Etapa 1 -->
    <a href="{{ route('admin.process.show') }}" class="step-item text-secondary text-center" title="Definir Calendário">
        <i class="bi {{ $calendar ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
        <p class="mt-2 mb-0 fw-semibold">Definir calendário</p>
    </a>

    <div class="step-divider"></div>

    <!-- Etapa 3 -->
    <a href="{{ route('admin.exam.create') }}" class="step-item text-secondary text-center" title="Agendar Prova">
        <i
            class="bi {{ $location_published ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
        <p class="mt-2 mb-0 fw-semibold">Divulgar Local de Prova</p>
    </a>

    <div class="step-divider"></div>

    <!-- Etapa 5 -->
    <a href="{{ route('admin.reports.classification') }}" class="step-item text-secondary text-center">
        <i
            class="bi {{ $result_published ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
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