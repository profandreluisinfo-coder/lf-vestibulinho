<div class="progress-container">
    <div class="d-flex justify-content-between align-items-baseline mb-1">
        <small class="fw-semibold text-muted">Progresso das tarefas</small>
        <small class="text-muted">
            {{ $steps_done }} de {{ $steps_total }} concluídas ({{ $steps_pct }}%)
        </small>
    </div>

    <div class="progress" style="height: 10px;">
        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $steps_pct }}%"
            aria-valuenow="{{ $steps_pct }}" aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
</div>