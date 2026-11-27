@extends('layouts.admin')

@section('page-title', 'Lista de Usuários Cadastrados')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [
            ['label' => 'Início', 'url' => route('admin.index')],
            ['label' => 'Usuários Cadastrados'],
        ],
    ])
@endsection

@section('content')

    <div class="container">
        {{-- Cabeçalho --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h4>Usuários Cadastrados</h4>
                    <span>
                        {{ $users->total() }}
                        {{ $users->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="text-muted me-2">
                <input type="checkbox" id="filterVerified" class="form-check-input me-2"> Mostrar apenas e-mails verificados
            </label>
        </div>

        @if (!$users->isEmpty())
            <div class="alert alert-info d-flex align-items-center shadow-sm alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <div class="fw-semibold d-flex align-items-center">
                    <i class="bi bi-info-circle me-2 fs-5"></i>
                    <span class="text-muted">Para encontrar um registro específico, digite na caixa de pesquisa qualquer
                        parte do e-mail ou data.</span>
                </div>
            </div>
        @endif

        <table id="subscribers" class="table table-striped align-middle">
            <thead class="table-success">
                <tr>
                    <th>E-mail</th>
                    <th class="text-center">Verificado</th>
                    <th>Registro</th>
                    <th>Validação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @forelse ($users as $user)
                    <tr>
                        <th scope="row">
                            {{ $user?->email }}
                        </th>
                        <td class="text-center">
                            <i class="bi {{ $user->email_verified_at ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }} fs-5"
                                data-bs-toggle="popover" data-bs-trigger="hover"
                                data-bs-content="{{ $user->email_verified_at ? 'Sim' : 'Não' }}"></i>
                            <span class="d-none">{{ $user->email_verified_at ? 'Sim' : 'Não' }}</span>
                        </td>
                        <td>
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            {{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            @if ($user->role === 'user' && $user?->inscription)
                                <a href="{{ route('admin.inscriptions.show', $user->id) }}"
                                    class="btn btn-sm btn-outline-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Visualizar detalhes" aria-label="Visualizar detalhes">
                                    <i class="bi bi-search"></i>
                                </a>
                            @elseif ($user->role === 'guest' || ($user->role === 'user' && !$user?->inscription))
                                <form id="delete-form-{{ $user->id }}"
                                    action="{{ route('admin.users.destroy', ['user' => $user]) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Excluir"
                                        aria-label="Excluir" onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhum registro encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Paginação --}}
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ✅ Função JavaScript para confirmação de exclusão (exemplo de uso abaixo)
        function confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Tem certeza que deseja excluir o usuário?',
                html: `
            <strong>${userName}</strong><br><br>
            Esta ação irá excluir permanentemente o usuário e todos os seus dados associados.
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${userId}`).submit();
                }
            });
        }
    </script>
@endpush
