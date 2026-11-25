@extends('layouts.admin')

@section('page-title', 'Locais de Prova')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', ['items' => [
        ['label' => 'Início', 'url' => route('admin.index')],
        ['label' => 'Locais de Prova'],
    ]])
@endsection

@section('content')

    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <h4>Locais de Prova</h4>
                    <span>
                        {{ $locations->total() }}
                        {{ $locations->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.location.create') }}" class="btn btn-primary btn-sm" aria-label="Cadastrar novo local">
                <i class="bi bi-plus-lg me-1"></i> Novo
            </a>
        </div>

        <div class="table-responsive">

            <table class="table-striped table">
                <thead class="table-success">
                    <tr>
                        <th scope="col">Local</th>
                        <th scope="col">Endereço</th>
                        <th scope="col" class="text-center">Salas Disponíveis</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($locations as $local)
                        <tr>
                            <th scope="row">
                                {{ $local->name }}
                            </th>
                            <td>{{ $local->address }}</td>
                            <td class="text-center">{{ $local->rooms_available }}</td>
                            <td>

                                <!-- Excluir -->
                                <form id="delete-location-form-{{ $local->id }}"
                                    action="{{ route('admin.location.destroy', ['location' => $local]) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <!-- Editar -->
                                <a href="{{ route('admin.location.edit', $local->id) }}"
                                    class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="Editar local" aria-label="Editar local">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <!-- Botão de excluir -->
                                <button type="button" title="Excluir"
                                    onclick="confirmLocationDelete({{ $local->id }}, '{{ addslashes($local->name) }}')"
                                    class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="Excluir local" aria-label="Excluir local">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Nenhum local cadastrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin/location/delete.js') }}"></script>
@endpush
