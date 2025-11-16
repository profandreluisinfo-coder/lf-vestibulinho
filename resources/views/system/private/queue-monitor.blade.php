@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . config('app.year') . ' | Monitoramento de Fila de E-mails')

@section('dash-content')

<div class="container mt-5" style="background-color: #f8f9fa; border-radius: 10px; padding: 30px;">
    <h2 class="mb-4 border-bottom pb-2">📊 Monitoramento de Fila de E-mails</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="p-3 border rounded bg-light shadow-sm">
                <h5>🕓 Pendentes</h5>
                <p class="fs-3 fw-bold text-warning">{{ $pending }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3 border rounded bg-light shadow-sm">
                <h5>✅ Processados</h5>
                <p class="fs-3 fw-bold text-success">{{ $processed }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3 border rounded bg-light shadow-sm">
                <h5>❌ Falhos</h5>
                <p class="fs-3 fw-bold text-danger">{{ $failed }}</p>

                @if($failed > 0)
                    <form method="POST" action="{{ route('queue.retry') }}" class="mt-2 d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">🔁 Reenviar</button>
                    </form>
                    <form method="POST" action="{{ route('queue.flush') }}" class="mt-2 d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">🧹 Limpar</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3 border rounded bg-light shadow-sm">
                <h5>📈 Taxa de Sucesso</h5>
                <p class="fs-3 fw-bold text-primary">{{ $successRate }}%</p>
                <small class="text-muted">Falhas: {{ $failureRate }}%</small>
            </div>
        </div>
    </div>

    <canvas id="queueChart" height="80"></canvas>

    <hr class="my-5">

    <h4 class="mb-3">📋 Últimos Jobs com Falha</h4>
    @if($failedJobs->count() === 0)
        <p class="text-muted">Nenhum job com falha recente 🎉</p>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Conexão</th>
                    <th>Fila</th>
                    <th>Data</th>
                    <th>Erro</th>
                </tr>
            </thead>
            <tbody>
                @foreach($failedJobs as $job)
                    <tr>
                        <td>{{ $job->id }}</td>
                        <td>{{ $job->connection }}</td>
                        <td>{{ $job->queue }}</td>
                        <td>{{ \Carbon\Carbon::parse($job->failed_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            <pre class="small text-danger" style="max-height: 100px; overflow-y: auto;">{{ Str::limit($job->exception, 250) }}</pre>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('queueChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Processados', 'Pendentes', 'Falhos'],
            datasets: [{
                data: [{{ $processed }}, {{ $pending }}, {{ $failed }}],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Distribuição de Jobs na Fila' }
            }
        }
    });
</script>
@endsection