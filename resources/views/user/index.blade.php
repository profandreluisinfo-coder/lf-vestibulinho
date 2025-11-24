@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Usuários Cadastrados')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-people me-2"></i>Usuários Cadastrados</h4>
        </div>
        <div class="mb-3">
            <label class="me-2">
                <input type="checkbox" id="filterVerified" class="form-check-input"> Mostrar apenas e-mails verificados
            </label>
        </div>
        @if (!($users->isEmpty()))
        <div class="alert alert-info d-flex align-items-center shadow-sm alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="fw-semibold d-flex align-items-center">
                <i class="bi bi-info-circle me-2 fs-5"></i>
                <span class="text-muted">Para encontrar um registro específico, digite na caixa de pesquisa qualquer parte do e-mail, "sim" ou "não".</span>
            </div>
        </div>
        @endif
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table id="subscribers" class="table table-striped table-hover freezed-table caption-top align-middle">
                <caption>{{ config('app.name') }} {{ $calendar?->year }} - Lista de usuários cadastrados que não
                    fizeram inscrição</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th>E-mail</th>
                        <th>E-mail Verificado?</th>
                        {{-- <th>Último Acesso</th> --}}
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">
                                {{ $user?->email }}
                            </th>
                            <td><span
                                    class="badge bg-{{ $user->email_verified_at ? 'success' : 'danger' }}">{{ $user->email_verified_at ? 'SIM' : 'NÃO' }}
                                </span></td>
                            {{-- <td>{{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i:s') }}</td> --}}
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
    <script>
        $(document).ready(function() {
            var table = $('#subscribers').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                buttons: ["excel", "pdf", "print", "colvis"],
                responsive: true,
                autoWidth: true,
                lengthChange: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, 500],
                    [10, 25, 50, 100, 500]
                ],
                ordering: true,
                info: true,
                dom: 'lBfrtip'
            });

            // Filtro personalizado
            $('#filterVerified').on('change', function() {
                if (this.checked) {
                    table.column(1).search('SIM').draw();
                } else {
                    table.column(1).search('').draw();
                }
            });
        });
    </script>
@endpush