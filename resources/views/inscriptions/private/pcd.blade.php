@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Pessoas com Deficiência')

@push('datatable-styles')
    <!-- datatables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('dash-content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-universal-access me-2"></i>Pessoas com Deficiência</h4>
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
                <caption>Pessoas com Deficiência</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">Inscrição</th>
                        <th scope="col">Candidato</th>
                        <th scope="col">Necessidade Especial</th>
                        {{-- <th scope="col">Laudo?</th> --}}
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->inscription?->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->user_detail->accessibility }}</td>
                            {{-- <td><span class="badge bg-{{ $user->laudo ? 'success' : 'danger' }}">{{ $user->laudo ? 'SIM' : 'NÃO' }}</span></td> --}}
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger clear-pne" data-id="{{ $user->id }}"
                                    title="Remover candidato desta lista.">
                                    <i class="bi bi-eraser"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum inscrito encontrado</td>
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
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#subscribers').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                responsive: true,
                autoWidth: true,
                lengthChange: true,
                pageLength: 25,
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('#subscribers').on('click', '.clear-pne', function() {
                let btn = $(this);
                let userId = btn.data('id');

                let url = "{{ route('inscriptions.clearPne', ['user' => ':id']) }}";
                url = url.replace(':id', userId);

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "O candidato será removido desta lista!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, remover!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'PATCH',
                            success: function(response) {
                                // console.log('Resposta:', response);

                                if (response.success) {
                                    Swal.fire('Candidato removido com sucesso!',
                                        response.message, 'success');

                                    let row = table.row(btn.closest('tr'));
                                    let rowNode = $(row.node());
                                    let cell = rowNode.find('td').eq(2);

                                    // Fade na célula da coluna PNE (ou nome social)
                                    cell.fadeOut(200, function() {
                                        $(this).text('').fadeIn(200);
                                    });

                                    // Remove o botão completamente
                                    btn.fadeOut(200, function() {
                                        $(this).remove();
                                    });

                                    // Opcional: efeito fade na linha inteira
                                    rowNode.fadeOut(300, function() {
                                        table.row(rowNode).invalidate().draw(
                                            false);
                                    });

                                    // Atualiza DataTables mantendo paginação e filtros
                                    row.invalidate().draw(false);

                                } else {
                                    // Caso exista prova agendada ou outro bloqueio
                                    Swal.fire('Atenção!', response.message, 'warning');
                                }
                            },
                            error: function() {
                                Swal.fire(
                                    'Erro!',
                                    'Não foi possível executar a operação.',
                                    'error'
                                );
                            }

                        });
                    }
                });
            });

        });
    </script>
@endpush
