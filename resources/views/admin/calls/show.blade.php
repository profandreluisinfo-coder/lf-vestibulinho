@extends('layouts.admin')

@section('page-title', 'Convocados da chamada ' . $callList->number)

@section('content')
    <div class="container my-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h3 class="mb-1">Chamada nº {{ $callList->number }}</h3>
                <div class="text-muted">
                    {{ $callList->date->format('d/m/Y') }} às {{ $callList->time->format('H:i') }}
                    · {{ $callList->status === 'completed' ? 'Publicada' : 'Rascunho' }}
                </div>
            </div>
            <a href="{{ route('admin.calls.index') }}" class="btn btn-outline-secondary">Voltar às chamadas</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Classificação</th>
                        <th>Inscrição</th>
                        <th>Candidato</th>
                        <th>PcD</th>
                        <th>Reserva PcD</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($callList->calls->sortBy(fn ($call) => $call->result?->ranking) as $call)
                        @php($user = $call->result?->inscription?->user)
                        <tr>
                            <td>{{ $call->result?->ranking ?? '—' }}</td>
                            <td>{{ $call->result?->inscription_id ?? '—' }}</td>
                            <td>{{ $user?->lgbt?->status === 'accepted' ? $user->lgbt->name : $user?->name }}</td>
                            <td>{{ $user?->pne?->status === 'accepted' ? 'Sim' : 'Não' }}</td>
                            <td>{{ $call->is_pcd_reserve ? 'Sim' : 'Não' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Esta chamada ainda não tem candidatos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
