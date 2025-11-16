@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Provas')

@section('dash-content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-geo me-2"></i>Locais de Prova</h4>
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setLocalModal">
            <i class="bi bi-plus-circle me-1"></i> Novo Local
        </a>
    </div>

    <div class="table-responsive">
        <table class="table-striped table caption-top">
            <caption>{{ config('app.name') }} {{ $calendar?->year }} - Lista de Locais de Prova</caption>
            <thead class="table-success text-center">
                <tr>
                    {{-- <th scope="col">#</th> --}}
                    <th scope="col">Local</th>
                    {{-- <th scope="col">Endereço</th> --}}
                    <th scope="col">Salas Disponíveis</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @forelse ($examLocations as $location)
                    <tr class="text-center">
                        <th scope="row">
                            {{ $location->name }}
                        </th>
                        {{-- <td>{{ $location->address }}</td> --}}
                        <td>{{ $location->rooms_available }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <!-- Excluir -->
                                <form id="delete-location-form-{{ $location->id }}"
                                    action="{{ route('exam.location.destroy', $location->id) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <!-- Botão de excluir -->
                                <button type="button"
                                    onclick="confirmLocationDelete({{ $location->id }}, '{{ addslashes($location->name) }}')"
                                    class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash me-2"></i> Excluir
                                </button>
                                <!-- Editar -->
                                <button type="button" class="btn btn-sm btn-primary">
                                    <a href="{{ route('exam.location.update', $location->id) }}"
                                        class="text-white text-decoration-none">
                                        <i class="bi bi-pencil-square me-2" title="Editar"></i> Editar
                                    </a>
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Nenhum local registrado</td>
                    </tr>
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
                    <h5 class="modal-title" id="setLocalModalLabel"><i class="bi bi-geo me-2"></i>Cadastrar Local de Prova</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form id="exam-location" action="{{ route('exam.locations') }}" method="POST"
                                novalidate>
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

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Cadastrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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
@endpush
