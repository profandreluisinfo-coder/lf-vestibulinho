@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Inscrições')

@push('datatable-styles')

    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-people me-2"></i>Candidatos Inscritos</h4>
        </div>
        @if (!($users->isEmpty()))

        <div class="alert alert-info d-flex align-items-center shadow-sm alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <div class="fw-semibold">
                <i class="bi bi-info-circle me-2"></i>
                Para encontrar um registro específico, digite na caixa de pesquisa qualquer parte da inscrição, do nome do candidato ou do CPF.
            </div>
        </div>
        @endif
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table id="subscribers" class="table table-striped table-hover freezed-table caption-top align-middle">
                <caption>Lista Geral de Inscritos</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th>Inscrição</th>
                        <th>Candidato</th>
                        <th>CPF</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">
                                {{ $user->inscription?->id }}
                            </th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->cpf }}</td>
                            <td>
                                <a href="{{ route('inscriptions.details', Crypt::encrypt($user->id)) }}"
                                    class="text-decoration-none">
                                    <i class="bi bi-search animate__animated animate__fadeIn me-2" title="Visualizar"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Nenhum inscrito encontrado</td>
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
                pageLength: 50,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Tudo"]
                ],
                ordering: true,
                info: true,
                dom: 'lBfrtip',

                columnDefs: [{
                    targets: -1, // última coluna ("Ações")
                    orderable: false,
                    searchable: false,
                    className: 'text-center no-export'
                }],

                buttons: [{
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel me-1 text-white"></i> Excel',
                        className: 'btn btn-sm btn-success',
                        exportOptions: {
                            columns: ':visible:not(.no-export)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="bi bi-file-earmark-pdf me-1 text-white"></i> PDF',
                        className: 'btn btn-sm btn-danger',
                        exportOptions: {
                            columns: ':visible:not(.no-export)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer me-1 text-white"></i> Imprimir',
                        className: 'btn btn-sm btn-primary',
                        exportOptions: {
                            columns: ':visible:not(.no-export)'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-eye me-1"></i> Colunas',
                        className: 'btn btn-sm btn-secondary'
                    }
                ]
            });

            // Posiciona os botões no topo direito, mais bonitinho
            table.buttons().container().appendTo('#subscribers_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
