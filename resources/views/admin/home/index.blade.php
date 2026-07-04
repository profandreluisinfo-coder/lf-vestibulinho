@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF - Painel Administrativo')

@section('content')
    <div class="container">
        <h5>📝 Principais Tarefas</h5>
        
        <div class="stepper-container">

            <!-- Etapa 1 -->
            <a href="{{ route('admin.process.show') }}" class="step-item text-secondary text-center"
                title="Definir Calendário">
                <i
                    class="bi {{ $process ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
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
                    class="bi {{ $settings->result ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                <p class="mt-2 mb-0 fw-semibold">Publicar resultados</p>
            </a>

            <div class="step-divider"></div>

            <!-- Etapa 6 -->
            <a href="{{ route('admin.calls.index') }}" class="step-item text-secondary text-center"
                title="Definir Chamadas">
                <i
                    class="bi {{ $calls_exists ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning' }} fs-3"></i>
                <p class="mt-2 mb-0 fw-semibold">Definir chamadas</p>
            </a>

        </div>

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

        @include('admin.partials.graphics')

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <script src="{{ asset('assets/js/admin/charts/burghs.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/courses.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/schools.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/genders.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/gender-per-course.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/chart-actions.js') }}"></script>
@endpush
