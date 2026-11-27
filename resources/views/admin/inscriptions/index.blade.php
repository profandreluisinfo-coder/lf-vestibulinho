@extends('layouts.admin')

@section('page-title', 'Candidatos Inscritos')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [
            ['label' => 'Início', 'url' => route('admin.index')],
            ['label' => 'Candidatos Inscritos'],
        ],
    ])
@endsection

@section('content')

    <div class="container">
{{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <h4>Candidatos Inscritos</h4>
                    <span>
                        Gere relatórios e exporte dados dos candidatos
                    </span>
                </div>
            </div>
            <button id="pdfButton" class="btn btn-sm btn-danger">
                <i class="bi bi-filetype-pdf"></i>
                <span>Gerar PDF (Todos)</span>
            </button>
        </div>
        <div class="table-responsive">
        <table id="subscribers" class="table table-striped align-middle">
            <thead class="table-success">
                <tr>                    
                    <th class="text-center">Inscrição</th>
                    <th>Candidato</th>
                    <th class="text-center">CPF</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <!-- Os dados serão carregados via AJAX -->
            </tbody>
        </table>
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
                    url: "{{ route('admin.inscriptions.get.data') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [                    
                    { data: 'inscription_id', name: 'inscription_id', className: 'text-center' },
                    { data: 'name',           name: 'name' },
                    { data: 'cpf',            name: 'cpf', className: 'text-center' },
                    { data: 'actions',        name: 'actions', orderable: false, searchable: false, className: 'text-center no-export' }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Carregando...</span>'
                },
                responsive: true,
                autoWidth: false,
                lengthChange: true,
                pageLength: 10,
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
                let pdfUrl = "{{ route('admin.inscriptions.pdf') }}";
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
