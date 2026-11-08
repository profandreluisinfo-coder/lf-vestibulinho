@extends('layouts.admin')

@section('page-title', 'Todos os convocados')

@section('content')
    <div class="container my-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h3 class="mb-1">Todos os convocados</h3>
                <p class="text-muted mb-0">
                    Vestibulinho {{ $process?->year ?? '' }}
                    @if ($calls->isNotEmpty())
                        · {{ $calls->count() }} {{ $calls->count() === 1 ? 'candidato' : 'candidatos' }}
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.calls.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Voltar às chamadas
            </a>
        </div>

        @if ($calls->isEmpty())
            <div class="alert alert-info" role="status">
                Ainda não há candidatos convocados neste processo seletivo.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>Chamada</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Classificação</th>
                            <th>Inscrição</th>
                            <th>Candidato</th>
                            <th>PcD</th>
                            <th>Reserva PcD</th>
                            <th>Status da chamada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calls as $call)
                            @php($user = $call->result?->inscription?->user)
                            <tr>
                                <td>{{ $call->callList?->number ?? $call->call_number }}</td>
                                <td>{{ $call->callList?->date?->format('d/m/Y') ?? $call->date->format('d/m/Y') }}</td>
                                <td>{{ $call->callList?->time?->format('H:i') ?? $call->time->format('H:i') }}</td>
                                <td>{{ $call->result?->ranking ?? '—' }}</td>
                                <td>{{ $call->result?->inscription_id ?? '—' }}</td>
                                <td>{{ $user?->lgbt?->status === 'accepted' ? $user->lgbt->name : $user?->name }}</td>
                                <td>{{ $user?->pne?->status === 'accepted' ? 'Sim' : 'Não' }}</td>
                                <td>{{ $call->is_pcd_reserve ? 'Sim' : 'Não' }}</td>
                                <td>
                                    <span class="badge bg-{{ $call->callList?->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ $call->callList?->status === 'completed' ? 'Publicada' : 'Rascunho' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
