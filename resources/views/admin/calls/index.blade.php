@extends('layouts.admin')

@section('page-title', 'Convocação para matrícula')

@section('content')
    @php
        $show = $callLists->isNotEmpty();
    @endphp
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Não foi possível concluir a operação:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-broadcast-pin text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Convocação para matrícula</h6>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if ($show)
                    <a href="{{ route('admin.calls.all') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-list-ol me-1"></i>Todos os convocados ()
                    </a>
                @endif
                @if ($countResults > 0)
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#setNewCall">
                        <i class="bi bi-plus-circle me-1"></i>Nova chamada
                    </button>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr class="table-success">
                        <th>Chamada</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Candidatos</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($callLists as $callList)
                        <tr>
                            <th>{{ $callList->number }}</th>
                            <td>{{ $callList->date->format('d/m/Y') }}</td>
                            <td>{{ $callList->time->format('H:i') }}</td>
                            <td>{{ $callList->calls_count }}</td>
                            <td>
                                <span class="badge bg-{{ $callList->status === 'pending' ? 'warning' : 'success' }}">
                                    {{ $callList->status === 'pending' ? 'Rascunho' : 'Publicada' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <form id="delete-form-{{ $callList->id }}"
                                        action="{{ route('admin.calls.destroy', $callList->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" title="Excluir chamada"
                                            onclick="confirmDelete({{ $callList->id }})">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.calls.show', $callList->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-people"></i> Convocados
                                    </a>
                                    <a href="{{ route('admin.calls.pdf', $callList->id) }}" class="btn btn-sm btn-primary"
                                        title="Imprimir fichas de matrícula" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Formulários
                                    </a>
                                    @if ($callList->status === 'pending')
                                        <form id="finalize-form-{{ $callList->id }}"
                                            action="{{ route('admin.calls.finalize', $callList->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-sm btn-success" title="Publicar chamada"
                                                onclick="confirmFinalize({{ $callList->id }})">
                                                <i class="bi bi-check-circle"></i> Publicar
                                            </button>
                                        </form>
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
                            <th>Total: {{ $callLists->count() }}</th>
                            <th></th>
                            <th></th>
                            <th>{{ $callLists->sum('calls_count') }} candidatos convocados</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <div class="modal fade" id="setNewCall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="setNewCallLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="setNewCallLabel"><i class="bi bi-plus-circle me-2"></i>Nova chamada</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.calls.store') }}" method="POST" id="setNewCallForm"
                            class="d-grid gap-3">
                            @csrf
                            <div>
                                <label for="number" class="form-label">Número da chamada</label>
                                <input type="number" name="number" id="number" min="1" required
                                    class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}">
                                <div class="form-text">Números de chamada podem se repetir.</div>
                            </div>
                            <div>
                                <label for="limit" class="form-label">Quantidade de candidatos</label>
                                <input type="number" name="limit" id="limit" min="1" required
                                    class="form-control @error('limit') is-invalid @enderror" value="{{ old('limit') }}">
                            </div>
                            <div>
                                <label for="pcd_limit" class="form-label">Quantidade de PcDs nesta chamada</label>
                                <input type="number" name="pcd_limit" id="pcd_limit" min="0" required
                                    class="form-control @error('pcd_limit') is-invalid @enderror"
                                    value="{{ old('pcd_limit', 0) }}">
                                <div class="form-text">Os PcDs deferidos são selecionados pela classificação e as demais
                                    vagas são preenchidas pela lista geral. Empates na posição de corte permanecem juntos.
                                </div>
                            </div>
                            <div>
                                <label for="date" class="form-label">Data de comparecimento</label>
                                <input type="date" name="date" id="date" required
                                    class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}">
                            </div>
                            <div>
                                <label for="time" class="form-label">Hora de comparecimento</label>
                                <input type="time" name="time" id="time" required
                                    class="form-control @error('time') is-invalid @enderror" value="{{ old('time') }}">
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success"><i
                                        class="bi bi-check-circle me-1"></i>Salvar chamada</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('setNewCall');
                if (modal) new bootstrap.Modal(modal).show();
            });
        </script>
    @endif
    <script src="{{ asset('assets/js/admin/calls/delete.js') }}"></script>
    <script src="{{ asset('assets/js/admin/calls/finalize.js') }}"></script>
@endpush
