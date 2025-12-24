@extends('layouts.admin.master')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Provas')

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-geo me-2"></i>Local de Prova</h5>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setLocalModal">
                <i class="bi bi-plus-circle me-1"></i> Novo
            </a>
        </div>

        <div class="table-responsive">

            <table class="table-striped table caption-top">
                <caption>{{ config('app.name') }} {{ $calendar->year }} - Lista de Locais de Prova</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">Local</th>
                        {{-- <th scope="col">Salas Disponíveis</th> --}}
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($locations as $local)
                        <tr class="text-center">
                            <th scope="row">
                                {{ $local->name }}
                            </th>
                            {{-- <td>{{ $local->rooms_available }}</td> --}}
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- Detalhes -->
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#viewLocal"
                                        onclick="showLocalDetails({{ $local->id }}, '{{ addslashes($local->name) }}', '{{ addslashes($local->address) }}', '{{ $local->rooms_available }}')">
                                        <i class="bi bi-eye" title="Ver Detalhes"></i>
                                    </a>
                                    <!-- Excluir -->
                                    <form id="delete-location-form-{{ $local->id }}"
                                        action="{{ route('app.local.destroy', $local->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <!-- Botão de excluir -->
                                    <button type="button" title="Excluir"
                                        onclick="confirmLocationDelete({{ $local->id }}, '{{ addslashes($local->name) }}')"
                                        class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <!-- Editar -->
                                    <button type="button" class="btn btn-sm btn-primary" title="Editar">
                                        <a href="{{ route('app.local.edit', $local->id) }}"
                                            class="text-white text-decoration-none">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('components.no-records', [
                            'message' => 'Causas de problemas com os locais de prova:',
                            'submessage' => 'Provavelmente nenhum local foi cadastrado no sistema.',
                            'action' => true,
                            'actionMessage' =>
                                'Solução: Clique no botão "Novo" e tente cadastrar um local. Se o problema persistir, entre em contato com o suporte.',
                        ])
                    @endforelse
                </tbody>
            </table>

        </div>

        {{-- Modal de definição de local --}}
        <div class="modal fade" id="setLocalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setLocalModalLabel"><i class="bi bi-geo me-2"></i>Cadastrar Local de
                            Prova</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">

                                <form id="exam-location" action="{{ route('app.local.store') }}" method="POST" novalidate>
                                    @csrf

                                    {{-- Nome do Local --}}
                                    <div class="form-floating mb-3">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            placeholder="Nome do local" value="{{ old('name') }}" required>
                                        <label for="name" class="form-label required">Nome do Local</label>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Endereço --}}
                                    <div class="form-floating mb-3">
                                        <input type="text" name="address"
                                            class="form-control @error('address') is-invalid @enderror" id="address"
                                            placeholder="Endereço" value="{{ old('address') }}" required>
                                        <label for="address" class="form-label required">Endereço</label>
                                        @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Total de Salas --}}
                                    <div class="form-floating mb-4">
                                        <input type="number" name="rooms_available"
                                            class="form-control @error('rooms_available') is-invalid @enderror"
                                            id="rooms_available" min="1" max="40"
                                            placeholder="Total de Salas Disponíveis" value="{{ old('rooms_available') }}"
                                            required>
                                        <label for="rooms_available" class="form-label required">Total de Salas
                                            Disponíveis</label>
                                        @error('rooms_available')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- prettier-ignore --}}
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle me-1"></i>Salvar
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal de exibição de detalhes de um local --}}
        <div class="modal fade" id="viewLocal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-eye me-2"></i>Detalhes do Local
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <p><strong>Nome:</strong> <span id="view-name"></span></p>
                                <p><strong>Endereço:</strong> <span id="view-address"></span></p>
                                <p><strong>Salas Disponíveis:</strong> <span id="view-rooms"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/rules/exam.js') }}"></script>
    <script src="{{ asset('assets/swa/locations/delete.js') }}"></script>
    <script>
        function showLocalDetails(id, name, address, rooms) {
            document.getElementById('view-name').textContent = name;
            document.getElementById('view-address').textContent = address;
            document.getElementById('view-rooms').textContent = rooms;
        }
    </script>
@endpush
