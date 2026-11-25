@extends('layouts.admin')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [
            ['label' => 'Início', 'url' => route('admin.index')],
            ['label' => 'Locais de Prova', 'url' => route('admin.location.index')],
            ['label' => 'Cadastrar Local'],
        ],
    ])
@endsection

@section('content')
    <div class="container">
        {{-- Cabeçalho --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <h4>Cadastrar Local de Prova</h4>
                    <span>Informe os dados abaixo para cadastrar um novo local de prova</span>
                </div>
            </div>
        </div>        
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="exam-location" action="{{ route('admin.location.store') }}" method="POST">
                    @csrf
                    {{-- Nome do Local --}}
                    <div class="form-group mb-3">                        
                        <label for="name" class="form-label required">Nome do Local</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="name" value="{{ old('name') }}" aria-label="Nome do Local">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- Endereço --}}
                    <div class="form-group mb-3">
                        <label for="address" class="form-label required">Endereço</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                            id="address" value="{{ old('address') }}" aria-label="Endereço">
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- Total de Salas --}}
                    <div class="form-group mb-4">
                        <label for="rooms_available" class="form-label required">Total de Salas Disponíveis</label>
                        <input type="number" name="rooms_available"
                            class="form-control @error('rooms_available') is-invalid @enderror" id="rooms_available"
                            min="1" max="50" value="{{ old('rooms_available') }}" aria-label="Total de Salas Disponíveis">
                        @error('rooms_available')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Salvar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/location/create.js') }}"></script>
@endpush
