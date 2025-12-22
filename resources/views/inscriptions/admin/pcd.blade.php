@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Pessoas com Deficiência')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('dash-content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-universal-access me-2"></i>Pessoas com Deficiência</h5>
        </div>

        <div class="alert alert-info d-flex align-items-center shadow-sm alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <div class="d-flex align-items-center fw-semibold">
                <i class="bi bi-info-circle fs-5 me-2"></i>
                Para encontrar um registro específico, digite na caixa de pesquisa qualquer parte da inscrição, do nome do candidato ou do CPF.
            </div>
        </div>

        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table id="subscribers" class="table table-striped table-hover freezed-table caption-top align-middle">
                <caption>Pessoas com Deficiência</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">Inscrição</th>
                        <th scope="col">Candidato</th>
                        <th scope="col">Necessidade Especial</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <!-- Os dados serão carregados via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('plugins')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#subscribers').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('inscriptions.getPCDData') }}", // Defina essa rota
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    { data: 'inscription_id', name: 'inscription_id' },
                    { data: 'name', name: 'name' },
                    { data: 'accessibility', name: 'accessibility' },
                    { 
                        data: 'actions', 
                        name: 'actions', 
                        orderable: false, 
                        searchable: false,
                        className: 'text-center no-export'
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Carregando...</span>'
                },
                responsive: true,
                autoWidth: false,
                lengthChange: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, 500],
                    [10, 25, 50, 100, 500]
                ],
                ordering: true,
                info: true,
                dom: 'lBfrtip',
                buttons: [
                    {
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

            table.buttons().container().appendTo('#subscribers_wrapper .col-md-6:eq(0)');

            // Event handler para remover candidato
            $('#subscribers').on('click', '.clear-pne', function() {
                let btn = $(this);
                let userId = btn.data('id');
                let url = "{{ route('users.clear.pne.condition', ['user' => ':id']) }}".replace(':id', userId);

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "O candidato será removido desta lista!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, remover!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Removido!', response.message, 'success');
                                    table.ajax.reload(null, false); // Recarrega sem resetar a paginação
                                } else {
                                    Swal.fire('Atenção!', response.message, 'warning');
                                }
                            },
                            error: function() {
                                Swal.fire('Erro!', 'Não foi possível executar a operação.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush