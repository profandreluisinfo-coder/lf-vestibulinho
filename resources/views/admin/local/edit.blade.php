@extends('layouts.admin')

@section('page-title', config('app.name') . ' | Editar Local de Prova')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-geo text-muted"></i>
            <h6 class="mb-0 text-muted fw-normal">Editar Local de Prova</h6>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form id="exam-location" action="{{ route('admin.local.edit', $location->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                
                {{-- Nome --}}
                <div class="form-floating mb-3 ">
                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        placeholder="Nome do local"
                        value="{{ old('name', $location->name) }}"
                        required
                    >
                    <label for="name" class="form-label required">Nome do Local</label>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Endereço --}}
                <div class="form-floating mb-3">
                    <input
                        type="text"
                        name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        id="address"
                        placeholder="Endereço"
                        value="{{ old('address', $location->address) }}"
                        required
                    >
                    <label for="address" class="form-label required">Endereço</label>
                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Total de Salas --}}
                <div class="form-floating mb-4">
                    <input
                        type="number"
                        name="rooms_available"
                        class="form-control @error('rooms_available') is-invalid @enderror"
                        id="rooms_available"
                        min="1"
                        max="40"
                        placeholder="Total de Salas"
                        value="{{ old('rooms_available', $location->rooms_available) }}"
                        required
                    >
                    <label for="rooms_available" class="form-label required">Total de Salas Disponíveis</label>
                    @error('rooms_available')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- prettier-ignore --}}
                <button type="submit" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-check-circle me-1"></i>Salvar
                </button>
                {{-- prettier-ignore --}}
                <a href="{{ route('admin.local.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle me-1"></i>Cancelar</a>

            </form>

        </div>
    </div>
</div>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush