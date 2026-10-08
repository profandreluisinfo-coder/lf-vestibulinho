@extends('layouts.admin')

@section('page-title', 'Lista de Recursos')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-folder2-open text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Lista de Recursos</h6>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filtros --}}
        <form method="GET" action="{{ route('admin.appeals.index') }}" class="row g-2 align-items-end mb-4">
            <div class="col-sm-4 col-md-3">
                <label for="type" class="form-label">Tipo</label>
                <select name="type" id="type" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="pne" @selected(request('type') === 'pne')>Laudo/Relatório</option>
                    <option value="lgbt" @selected(request('type') === 'lgbt')>Nome Social</option>
                </select>
            </div>

            <div class="col-sm-4 col-md-3">
                <label for="status" class="form-label">Situação</label>
                <select name="status" id="status" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <option value="pending" @selected(request('status') === 'pending')>Em análise</option>
                    <option value="accepted" @selected(request('status') === 'accepted')>Deferido</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Indeferido</option>
                </select>
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
                <a href="{{ route('admin.appeals.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-x-lg"></i> Limpar
                </a>
            </div>
        </form>

        <table class="table table-hover align-middle">
            <caption>Vestibulinho LF - {{ $process?->year }} - Recursos registrados ({{ $appeals->total() }})</caption>
            <thead class="table-success text-center">
                <tr>
                    <th scope="col"><i class="bi bi-upc me-1"></i>Protocolo</th>
                    <th scope="col"><i class="bi bi-hash me-1"></i>Inscrição</th>
                    <th scope="col"><i class="bi bi-person me-1"></i>Candidato</th>
                    <th scope="col"><i class="bi bi-tag me-1"></i>Tipo</th>
                    <th scope="col"><i class="bi bi-search me-1"></i>Situação</th>
                    <th scope="col"><i class="bi bi-calendar-check me-1"></i>Decisão</th>
                    <th scope="col"><i class="bi bi-gear me-1"></i>Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($appeals as $appeal)
                    <tr>
                        <th scope="row" class="text-center">{{ $appeal->protocol }}</th>
                        <td class="text-center">{{ $appeal->user->inscription?->id }}</td>
                        <td>
                            @if ($appeal->user->lgbt && $appeal->user->lgbt->status === 'accepted')
                                {{ $appeal->user->lgbt->name }}
                            @else
                                {{ $appeal->user->name }}
                            @endif
                        </td>
                        @php
                            $isPne = $appeal->isPne();
                        @endphp

                        <td class="text-center">
                            <i class="bi {{ $isPne ? 'bi-universal-access' : 'bi-gender-trans' }}" title="Descrição"
                                data-bs-toggle="popover" data-bs-trigger="hover"
                                data-bs-content="{{ $isPne ? 'PCD' : 'LGBTQIA+' }}">
                            </i>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $appeal->badgeClass() }}">{{ $appeal->statusLabel() }}</span>
                        </td>
                        <td class="text-center">
                            @if ($appeal->decided_at)
                                {{ $appeal->decided_at->format('d/m/Y H:i') }}
                                <br>
                                <small class="text-muted">{{ $appeal->decider?->name }}</small>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($appeal->isPending())
                                <a href="{{ route('admin.appeals.show', $appeal) }}" class="btn btn-sm btn-success"
                                    title="Analisar recurso">
                                    <i class="bi bi-search"></i> Analisar
                                </a>
                            @else
                                <a href="{{ route('admin.appeals.show', $appeal) }}" class="btn btn-sm btn-outline-primary"
                                    title="Ver recurso">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Nenhum recurso encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $appeals->links('pagination::bootstrap-5') }}
    </div>

@endsection