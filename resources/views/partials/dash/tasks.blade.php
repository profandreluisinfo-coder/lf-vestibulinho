<div class="mb-3">
    <div class="d-flex justify-content-between align-items-baseline mb-1">
        <small class="fw-semibold text-muted">Progresso das tarefas</small>
        <small class="text-muted">{{ $steps_done }} de {{ $steps_total }} concluídas ({{ $steps_pct }}%)</small>
    </div>
    <div class="progress" style="height: 10px;">
        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $steps_pct }}%"
            aria-valuenow="{{ $steps_pct }}" aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>

<div class="accordion accordion-flush shadow-lg bg-light mb-5" id="accordionFlush">
    <div class="accordion-item" id="flush-headingOne">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                📝 Principais Tarefas
            </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse show bg-light"
            aria-labelledby="flush-headingOne">
            <div class="accordion-body">
                <div class="stepper-container mb-5">
                    <!-- Etapa 1 -->
                    <a href="{{ route('app.notices.index') }}" class="step-item text-secondary text-center"
                        title="Publicar Edital">
                        <i
                            class="bi {{ $settings->isNoticeEnabled() ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                        <p class="mt-2 mb-0 fw-semibold">Publicar edital</p>
                    </a>

                    <!-- Divider -->
                    <div class="step-divider"></div>

                    <!-- Etapa 2 -->
                    <a href="{{ route('app.calendar.index') }}" class="step-item text-secondary text-center"
                        title="Definir Calendário">
                        <i
                            class="bi {{ $calendar_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                        <p class="mt-2 mb-0 fw-semibold">Definir calendário</p>
                    </a>

                    <div class="step-divider"></div>

                    <!-- Etapa 3 -->
                    <a href="{{ route('app.exam.create') }}" class="step-item text-secondary text-center"
                        title="Agendar Prova">
                        <i
                            class="bi {{ $local_status ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                        <p class="mt-2 mb-0 fw-semibold">Agendar prova</p>
                    </a>

                    <div class="step-divider"></div>

                    <!-- Etapa 4 -->
                    {{-- <a href="{{ route('app.archives.index') }}" class="step-item text-secondary text-center"
                            title="Publicar Prova">
                            <i
                                class="bi {{ App\Models\Archive::latest('id')->first()?->status == 1 ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                            <p class="mt-2 mb-0 fw-semibold">Publicar prova</p>
                        </a>

                        <div class="step-divider"></div> --}}

                    <!-- Etapa 5 -->
                    <a href="{{ route('app.import.home') }}" class="step-item text-secondary text-center"
                        title="Importar Notas">
                        <i
                            class="bi {{ $ranking_active ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                        <p class="mt-2 mb-0 fw-semibold">Importar notas</p>
                    </a>
                    <div class="step-divider"></div>
                    <!-- Etapa 6 -->
                    <a href="{{ route('app.system.publish.result') }}" class="step-item text-secondary text-center">
                        <i
                            class="bi {{ $settings->result ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                        <p class="mt-2 mb-0 fw-semibold">Publicar resultados</p>
                    </a>
                    <div class="step-divider"></div>
                    <!-- Etapa 7 -->
                    <a href="{{ route('app.calls.index') }}" class="step-item text-secondary text-center"
                        title="Definir Chamadas">
                        <i
                            class="bi {{ $calls_exists ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                        <p class="mt-2 mb-0 fw-semibold">Definir chamadas</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>