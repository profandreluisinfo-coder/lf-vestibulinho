@extends('layouts.admin')

@section('page-title', 'Convocados da chamada ' . $callList->number)

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [
            ['label' => 'Início', 'url' => route('admin.index')],
            ['label' => 'Convocações', 'url' => route('admin.calls.index')],
            [
                'label' =>
                    'Convocados da chamada ' .
                    $callList->number .
                    ' de ' .
                    $callList->date->format('d/m/Y') .
                    ' às ' .
                    $callList->time->format('H:i') .
                    ' - ' .
                    ($callList->status === 'completed' ? 'Publicada' : 'Rascunho'),
            ],
        ],
    ])
@endsection

@section('content')
    <div class="container">

        {{-- Cabeçalho --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-broadcast-pin"></i>
                </div>
                <div>
                    <h4>Convocação para matrícula</h4>
                    <span>
                        {{ $callList->calls->count() }}
                        {{ $callList->calls->count() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="calls" class="table table-striped">
                <thead class="table-success">
                    <tr>
                        <th scope="col" class="text-center">Classificação</th>
                        <th scope="col" class="text-center">Inscrição</th>
                        <th scope="col">Candidato</th>
                        <th scope="col">PcD</th>
                        <th scope="col" class="text-center">Reserva PcD</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($callList->calls->sortBy(fn ($call) => $call->result?->ranking) as $call)
                        @php($user = $call->result?->inscription?->user)
                        <tr>
                            <th scope="row" class="text-center">{{ $call->result?->ranking ?? '—' }}</th>
                            <td class="text-center">{{ $call->result?->inscription_id ?? '—' }}</td>
                            <td>{{ $user?->lgbt?->status === 'accepted' ? $user->lgbt->name : $user?->name }}</td>
                            <td>
                                @if ($user?->pne?->status === 'accepted')
                                    <strong class="text-success fw-bold">Sim</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($call->is_pcd_reserve)
                                    <strong class="text-success fw-bold">Sim</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Esta chamada ainda não tem candidatos.</td>
                        </tr>
                    @endforelse
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
            $('#calls').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                buttons: [
                    "excel",
                    {
                        extend: 'pdfHtml5',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function (doc) {
                            doc.content.forEach(function (item) {
                                if (item.table) {
                                    item.table.widths = Array(item.table.body[0].length + 1).join('*').split('');

                                    item.table.body.forEach(function (row) {
                                        row.forEach(function (cell) {
                                            cell.alignment = 'center';
                                        });
                                    });
                                }
                            });
                        }
                    },
                    "print",
                    "colvis"
                ],
                responsive: true,
                autoWidth: true,
                lengthChange: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                ordering: true,
                info: true,
                dom: 'lBfrtip',
                // columnDefs: [
                //     {
                //         targets: [0, 1, 3, 4],
                //         className: 'text-center'
                //     }
                // ]
            });
        });
    </script>
@endpush
