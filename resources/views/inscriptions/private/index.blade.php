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

        <div class="alert alert-info d-flex align-items-center shadow-sm alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <div class="fw-semibold">
                <i class="bi bi-info-circle me-2"></i>
                Para encontrar um registro específico, digite na caixa de pesquisa qualquer parte da inscrição, do nome do
                candidato ou do CPF.
            </div>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button id="pdfButton" class="btn btn-danger d-flex align-items-center justify-content-center">
                <i class="bi bi-filetype-pdf me-1"></i>
                <span>Gerar PDF (Todos)</span>
            </button>
        </div>

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
                    url: "{{ route('inscriptions.getInscriptionsData') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'inscription_id',
                        name: 'inscription_id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'cpf',
                        name: 'cpf'
                    },
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
                order: [
                    [0, 'asc']
                ], // Ordena por inscrição (primeira coluna)
                info: true,
                dom: 'lBfrtip',
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

            table.buttons().container().appendTo('#subscribers_wrapper .col-md-6:eq(0)');

            // Botão de gerar PDF com todos os registros
            $('#pdfButton').on('click', function() {
                const btn = $(this);
                const searchValue = table.search(); // Pega o filtro atual do DataTables

                btn.addClass('disabled').css('pointer-events', 'none');
                btn.html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Gerando PDF, aguarde...
                `);

                // Monta a URL com o filtro de busca (se houver)
                let pdfUrl = "{{ route('inscriptions.pdf') }}";
                if (searchValue) {
                    pdfUrl += '?search=' + encodeURIComponent(searchValue);
                }

                // Abre o PDF em nova aba
                window.open(pdfUrl, '_blank');

                // Volta ao estado normal após 3 segundos
                setTimeout(() => {
                    btn.removeClass('disabled').css('pointer-events', 'auto');
                    btn.html(`
                        <i class="bi bi-filetype-pdf me-1"></i>
                        <span>Gerar PDF (Todos)</span>
                    `);
                }, 3000);
            });
        });
    </script>
@endpush
