@extends('layouts.admin')

@section('page-title', config('app.name') . ' | Usuários Cadastrados')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-people me-2"></i>Usuários Cadastrados</h5>
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
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table id="subscribers" class="table table-striped table-hover freezed-table caption-top align-middle">
                <caption>{{ config('app.name') }} {{ $process?->year }} - Lista de usuários cadastrados</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th>E-mail</th>
                        <th>Tipo</th>
                        <th>Verificado</th>
                        <th>Inscrição</th>
                        <th>Registrado em</th>
                        <th>E-mail verificado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">
                                {{ $user?->email }}
                            </th>
                            <td>
                                <i class="bi {{ $user->role === 'admin' ? 'bi-person-gear' : 'bi-person-fill' }} fs-5"
                                    data-bs-toggle="popover" data-bs-trigger="hover"
                                    data-bs-content="{{ $user->role === 'admin' ? 'Administrador' : 'Candidato' }}"></i>
                            </td>
                            <td>
                                <i class="bi {{ $user->email_verified_at ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }} fs-5"
                                    data-bs-toggle="popover" data-bs-trigger="hover"
                                    data-bs-content="{{ $user->email_verified_at ? 'Sim' : 'Não' }}"></i>
                                <span class="d-none">{{ $user->email_verified_at ? 'Sim' : 'Não' }}</span>
                            </td>
                            <td>
                                @if ($user->role !== 'admin')
                                    <i class="bi {{ $user->inscription?->id ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }} fs-5"
                                        data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-content="{{ $user->inscription?->id ? 'Sim' : 'Não' }}"></i>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td>
                                {{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                @if ($user->role === 'user')

                                @if ($user->inscription?->id)
                                    <a href="{{ route('admin.inscriptions.show', Crypt::encrypt($user->id)) }}"
                                        class="btn btn-sm btn-primary btn-sm" title="Visualizar detalhes">
                                        <i class="bi bi-search"></i> Detalhes
                                    </a>
                                @endif

                                <form id="delete-form-{{ $user->id }}"
                                    action="{{ route('admin.users.destroy', ['user' => $user]) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                        onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>

                                @else 
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Nenhum registro encontrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('plugins')
    <!-- Datatables -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <!-- PDF e Excel (para botões de exportação) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="{{ asset('assets/js/datatables/users.js') }}"></script>
@endpush

@push('scripts')
    <script>
        /**
         * Inicializa todos os popovers com o trigger 'click'.
         * @description
         *   Inicializa todos os elementos com a classe 'popover-trigger' como
         *   popovers com o trigger 'click'. Além disso, fecha todos os popovers
         *   abertos quando o usuário clica fora do elemento.
         */

        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })
    </script>
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
