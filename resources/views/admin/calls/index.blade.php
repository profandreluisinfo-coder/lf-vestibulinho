@extends('layouts.admin')

@section('page-title', 'Convocação para matrícula')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [['label' => 'Início', 'url' => route('admin.index')], ['label' => 'Convocações']],
    ])
@endsection

@section('content')
    @php
        $show = $callLists->isNotEmpty();
    @endphp
    <div class="container">
        {{-- Cabeçalho --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-broadcast-pin"></i>
                </div>
                <div>
                    <h4>Convocação para matrícula</h4>
                    <span>
                        {{ $callLists->count() }}
                        {{ $callLists->count() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
            @if ($countResults > 0)
                <a href="{{ route('admin.calls.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i>Nova chamada
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="status">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Não foi possível concluir a operação:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex flex-wrap gap-2">
                @if ($show)
                    <a href="{{ route('admin.calls.all') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-list-ol me-1"></i>Todos os convocados
                    </a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr class="table-success">
                        <th scope="col" class="text-center">Chamada</th>
                        <th scope="col">Data</th>
                        <th scope="col">Hora</th>
                        <th scope="col" class="text-center">Candidatos</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($callLists as $callList)
                        <tr>
                            <th scope="row" class="text-center">{{ $callList->number }}</th>
                            <th scope="row">{{ $callList->date->format('d/m/Y') }}</th>
                            <th scope="row">{{ $callList->time->format('H:i') }}</th>
                            <td class="text-center">
                                {{ $callList->calls_count }} candidatos ({{ $callList->pcd_calls_count }} PcD)
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $callList->status === 'pending' ? 'warning' : 'success' }}">
                                    {{ $callList->status === 'pending' ? 'Rascunho' : 'Publicada' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.calls.show', $callList->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-people"></i> Candidatos
                                    </a>
                                    <a href="{{ route('admin.calls.pdf', $callList->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Imprimir fichas de matrícula"
                                        target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Fichas
                                    </a>
                                    @if ($callList->status === 'pending')
                                        <form id="finalize-form-{{ $callList->id }}"
                                            action="{{ route('admin.calls.finalize', $callList->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                title="Publicar chamada" onclick="confirmFinalize({{ $callList->id }})">
                                                <i class="bi bi-check-circle"></i> Publicar
                                            </button>
                                        </form>
                                    @endif

                                    <form id="delete-form-{{ $callList->id }}"
                                        action="{{ route('admin.calls.destroy', $callList->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir chamada"
                                            onclick="confirmDelete({{ $callList->id }})">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    </form>

                                    @if ($callList->status === 'completed')
                                        <a href="{{ route('site.calls.index') }}" target="_blank" rel="noopener"
                                            class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Ver página pública
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma chamada registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($callLists->isNotEmpty())
                    <tfoot>
                        <tr class="table-success">
                            <th scope="row" class="text-center">Total: {{ $callLists->count() }}</th>
                            <th></th>
                            <th></th>
                            <th scope="row" class="text-center">
                                {{ $callLists->sum('calls_count') }} candidatos
                                ({{ $callLists->sum('pcd_calls_count') }} PcD)
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/calls/delete.js') }}"></script>
    <script src="{{ asset('assets/js/admin/calls/finalize.js') }}"></script>
@endpush
