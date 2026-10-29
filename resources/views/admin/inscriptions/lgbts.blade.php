@extends('layouts.admin')

@section('page-title', 'Nome Social')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <style>
        #subscribers caption {
            caption-side: top;
            padding: .25rem 0 .75rem;
            color: var(--bs-secondary-color, #6c757d);
        }

        #subscribers .col-actions {
            white-space: nowrap;
        }

        .status-filter .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .status-filter .btn .badge {
            font-weight: 600;
        }
    </style>
@endpush

@php
    // Mapa único de status: rótulo, cor e ícone em um só lugar.
    $statusMap = [
        'pending' => ['label' => 'Pendente', 'class' => 'bg-warning text-dark', 'icon' => 'bi-hourglass-split'],
        'accepted' => ['label' => 'Deferido', 'class' => 'bg-success', 'icon' => 'bi-check-circle-fill'],
        'rejected' => ['label' => 'Indeferido', 'class' => 'bg-danger', 'icon' => 'bi-x-circle-fill'],
    ];

    $counts = $users->countBy(fn($u) => $u->lgbt?->status);
@endphp

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-gender-trans" aria-hidden="true"></i>
                <h1 class="h6 mb-0 text-muted fw-normal">Candidatos com Nome Social</h1>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        @if ($users->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2" aria-hidden="true"></i>
                <p class="mb-0">Nenhum candidato com solicitação de nome social foi encontrado neste processo.</p>
            </div>
        @else
            {{-- Filtro por situação, com contagem. Filtra a tabela sem recarregar a página. --}}
            <div class="status-filter btn-group flex-wrap mb-3" role="group" aria-label="Filtrar por situação">
                <button type="button" class="btn btn-outline-secondary active" data-filter="" aria-pressed="true">
                    Todos <span class="badge bg-secondary">{{ $users->count() }}</span>
                </button>
                @foreach ($statusMap as $key => $meta)
                    <button type="button" class="btn btn-outline-secondary" data-filter="{{ $key }}"
                        aria-pressed="false">
                        <i class="bi {{ $meta['icon'] }}" aria-hidden="true"></i>
                        {{ $meta['label'] }}
                        <span class="badge {{ $meta['class'] }}">{{ $counts[$key] ?? 0 }}</span>
                    </button>
                @endforeach
            </div>

            <div class="table-responsive">
                <table id="subscribers" class="table table-hover align-middle">
                    <caption>Vestibulinho LF - {{ $process?->year }} - Lista de Candidatos com Nome Social</caption>
                    <thead class="table-success text-center">
                        <tr>
                            <th scope="col"><i class="bi bi-hash me-1" aria-hidden="true"></i>Inscrição</th>
                            <th scope="col"><i class="bi bi-person me-1" aria-hidden="true"></i>Nome de Registro</th>
                            <th scope="col"><i class="bi bi-gender-trans me-1" aria-hidden="true"></i>Nome Social</th>
                            <th scope="col"><i class="bi bi-file-earmark-medical me-1" aria-hidden="true"></i>Autorização
                            </th>
                            <th scope="col"><i class="bi bi-search me-1" aria-hidden="true"></i>Status</th>
                            <th scope="col" class="text-nowrap"><i class="bi bi-gear me-1" aria-hidden="true"></i>Ação
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @php
                                $lgbt = $user->lgbt;
                                $status = $lgbt?->status;
                                $meta = $statusMap[$status] ?? ['label' => '-', 'class' => 'bg-secondary', 'icon' => 'bi-dash'];
                                $hasObservation = in_array($status, ['accepted', 'rejected']) && $lgbt?->observations;
                                $appeal = $user->appeals->firstWhere('type', 'lgbt');
                                $canDefer = in_array($status, ['pending', 'rejected']);
                                $canReject = in_array($status, ['pending', 'accepted']);
                            @endphp

                            <tr data-id="{{ $user->id }}">
                                <th scope="row" class="text-center">{{ $user->inscription?->id ?? '-' }}</th>

                                {{-- Nome de Registro sempre mostra o nome civil; o nome social tem coluna própria --}}
                                <td>{{ $user->name }}</td>

                                <td class="text-center">
                                    {{ $lgbt?->name ?: '-' }}
                                </td>

                                <td class="text-center">
                                    @if ($lgbt?->authorization)
                                        <a href="{{ Storage::url($lgbt->authorization) }}" target="_blank"
                                            rel="noopener" class="btn btn-outline-primary btn-sm"
                                            title="Abrir autorização em nova aba"
                                            aria-label="Abrir autorização de {{ $user->name }} em nova aba">
                                            <i class="bi bi-file-earmark-text" aria-hidden="true"></i> Abrir autorização
                                        </a>
                                    @else
                                        <span class="text-muted">Nenhum arquivo</span>
                                    @endif
                                </td>

                                {{-- data-order/data-search: DataTables ordena e filtra pelo valor, não pelo HTML --}}
                                <td class="text-center" data-order="{{ $status }}" data-search="{{ $status }}">
                                    <span class="badge {{ $meta['class'] }} d-inline-flex align-items-center gap-1"
                                        @if ($hasObservation) data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            data-bs-title="{{ $lgbt->observations }}" @endif>
                                        <i class="bi {{ $meta['icon'] }}" aria-hidden="true"></i>
                                        {{ $meta['label'] }}
                                        @if ($hasObservation)
                                            <i class="bi bi-chat-left-text" aria-hidden="true"></i>
                                            <span class="visually-hidden">Observação: {{ $lgbt->observations }}</span>
                                        @endif
                                    </span>
                                    @if ($status === 'pending')
                                        <div class="small text-muted">Aguardando análise</div>
                                    @endif
                                </td>

                                <td class="col-actions">
                                    {{-- Ordem fixa: Imprimir, Deferir, Indeferir, Recurso --}}
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.analyses.pdf', ['type' => 'lgbt', 'id' => $user->id]) }}"
                                            class="btn btn-sm btn-outline-primary" title="Imprimir dados do candidato"
                                            aria-label="Imprimir dados de {{ $user->name }}" target="_blank">
                                            <i class="bi bi-printer" aria-hidden="true"></i>
                                            <span class="d-none d-xl-inline">Imprimir</span>
                                        </a>

                                        @if ($canDefer)
                                            <a href="{{ route('admin.analyses.accept.social.name.form', $user->id) }}"
                                                class="btn btn-sm btn-success" title="Deferir"
                                                aria-label="Deferir {{ $user->name }}">
                                                <i class="bi bi-check-lg" aria-hidden="true"></i>
                                                <span class="d-none d-xl-inline">Deferir</span>
                                            </a>
                                        @endif

                                        @if ($canReject)
                                            <a href="{{ route('admin.analyses.reject.social.name.form', $user->id) }}"
                                                class="btn btn-sm btn-danger" title="Indeferir"
                                                aria-label="Indeferir {{ $user->name }}">
                                                <i class="bi bi-x-lg" aria-hidden="true"></i>
                                                <span class="d-none d-xl-inline">Indeferir</span>
                                            </a>
                                        @endif

                                        @if ($status === 'rejected')
                                            @if ($appeal)
                                                <a href="{{ route('admin.appeals.show', $appeal) }}"
                                                    class="btn btn-sm btn-outline-secondary" title="Ver recurso"
                                                    aria-label="Ver recurso de {{ $user->name }}">
                                                    <i class="bi bi-folder2-open" aria-hidden="true"></i>
                                                    <span class="d-none d-xl-inline">Ver recurso</span>
                                                </a>
                                            @elseif (!$user->inscription?->exam_result)
                                                <a href="{{ route('admin.appeals.create', [$user->id, 'lgbt']) }}"
                                                    class="btn btn-sm btn-warning" title="Registrar recurso"
                                                    aria-label="Registrar recurso de {{ $user->name }}">
                                                    <i class="bi bi-folder-plus" aria-hidden="true"></i>
                                                    <span class="d-none d-xl-inline">Registrar recurso</span>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
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
    {{-- <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script> --}}
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <!-- PDF e Excel (para botões de exportação) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/admin/datatables/shared.js') }}"></script>
    <script>
        $(function() {
            const $table = $('#subscribers');
            if (!$table.length) return;

            // Usa a instância criada pelo shared.js; se ela não existir, cria uma básica.
            const table = $.fn.dataTable.isDataTable($table) ?
                $table.DataTable() :
                $table.DataTable({
                    responsive: true,
                    order: [
                        [0, 'asc']
                    ]
                });

            const STATUS_COLUMN = 4;

            // Filtro por situação (Todos / Pendente / Deferido / Indeferido)
            $('.status-filter').on('click', '[data-filter]', function() {
                const value = $(this).data('filter');

                $('.status-filter [data-filter]')
                    .removeClass('active')
                    .attr('aria-pressed', 'false');
                $(this).addClass('active').attr('aria-pressed', 'true');

                table.column(STATUS_COLUMN)
                    .search(value ? '^' + value + '$' : '', true, false)
                    .draw();
            });

            // Tooltips das observações (reiniciados a cada paginação/ordenação)
            const initTooltips = () => {
                if (!window.bootstrap) return;
                document.querySelectorAll('#subscribers [data-bs-toggle="tooltip"]').forEach(el => {
                    bootstrap.Tooltip.getOrCreateInstance(el);
                });
            };

            initTooltips();
            table.on('draw.dt responsive-display.dt', initTooltips);
        });
    </script>
@endpush