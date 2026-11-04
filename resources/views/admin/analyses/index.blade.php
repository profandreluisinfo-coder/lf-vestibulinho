@extends('layouts.admin')

@php
    // $type (AnalysisType) vem de quem inclui esta view: pcds.blade.php ou lgbts.blade.php.
    $isLgbt = $type === \App\Enums\AnalysisType::Lgbt;

    // Textos e campos que mudam entre os dois tipos.
    $ui = $isLgbt
        ? [
            'title' => 'Nome Social',
            'heading' => 'Candidatos com Nome Social',
            'icon' => 'bi-gender-trans',
            'empty' => 'Nenhum candidato com solicitação de nome social foi encontrado neste processo.',
            'caption' => 'Lista de Candidatos com Nome Social',
            'nameHeader' => 'Nome de Registro',
            'detailHeader' => 'Nome Social',
            'detailField' => 'name',
            'fileHeader' => 'Autorização',
        ]
        : [
            'title' => 'Pessoas com Deficiência',
            'heading' => 'Pessoas com Deficiência',
            'icon' => 'bi-universal-access',
            'empty' => 'Nenhum candidato com necessidades especiais foi encontrado neste processo.',
            'caption' => 'Lista de Candidatos com Necessidades Especiais',
            'nameHeader' => 'Candidato',
            'detailHeader' => 'Condição',
            'detailField' => 'description',
            'fileHeader' => 'Relatório',
        ];

    // Mapa único de status: rótulo, cor e ícone.
    $statusMap = [
        'pending' => [
            'label' => 'Pendente',
            'class' => 'bg-warning text-dark',
            'icon' => 'bi-hourglass-split',
        ],
        'accepted' => [
            'label' => 'Deferido',
            'class' => 'bg-success',
            'icon' => 'bi-check-circle-fill',
        ],
        'rejected' => [
            'label' => 'Indeferido',
            'class' => 'bg-danger',
            'icon' => 'bi-x-circle-fill',
        ],
    ];

    // Contagem dos candidatos por status.
    $counts = $users->countBy(fn($u) => $u->{$type->relation()}?->status);

    // True quando a alocação nas salas já foi realizada.
    // Após a alocação, novas análises e recursos ficam bloqueados.
    $allocated = \App\Models\Exam::allocationExists();
@endphp

@section('page-title', $ui['title'])

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

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi {{ $ui['icon'] }}" aria-hidden="true"></i>
                <h1 class="h6 mb-0 text-muted fw-normal">{{ $ui['heading'] }}</h1>
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
                <p class="mb-0">
                    {{ $ui['empty'] }}
                </p>
            </div>

        @else

            {{-- Filtro por situação, com contagem. --}}
            <div class="status-filter btn-group flex-wrap mb-3"
                role="group"
                aria-label="Filtrar por situação">

                <button type="button"
                    class="btn btn-outline-secondary active"
                    data-filter=""
                    aria-pressed="true">

                    Todos
                    <span class="badge bg-secondary">{{ $users->count() }}</span>
                </button>

                @foreach ($statusMap as $key => $meta)

                    <button type="button"
                        class="btn btn-outline-secondary"
                        data-filter="{{ $key }}"
                        aria-pressed="false">

                        <i class="bi {{ $meta['icon'] }}" aria-hidden="true"></i>
                        {{ $meta['label'] }}

                        <span class="badge {{ $meta['class'] }}">
                            {{ $counts[$key] ?? 0 }}
                        </span>
                    </button>

                @endforeach

            </div>

            <div class="table-responsive">

                <table id="subscribers" class="table table-hover align-middle">

                    <caption>
                        Vestibulinho LF - {{ $process?->year }} -
                        {{ $ui['caption'] }}
                    </caption>

                    <thead class="table-success text-center">
                        <tr>
                            <th scope="col">
                                <i class="bi bi-hash me-1" aria-hidden="true"></i>
                                Inscrição
                            </th>

                            <th scope="col">
                                <i class="bi bi-person me-1" aria-hidden="true"></i>
                                {{ $ui['nameHeader'] }}
                            </th>

                            <th scope="col">
                                <i class="bi {{ $ui['icon'] }} me-1" aria-hidden="true"></i>
                                {{ $ui['detailHeader'] }}
                            </th>

                            <th scope="col">
                                <i class="bi bi-file-earmark-medical me-1" aria-hidden="true"></i>
                                {{ $ui['fileHeader'] }}
                            </th>

                            <th scope="col">
                                <i class="bi bi-search me-1" aria-hidden="true"></i>
                                Status
                            </th>

                            <th scope="col" class="text-nowrap">
                                <i class="bi bi-gear me-1" aria-hidden="true"></i>
                                Ação
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($users as $user)

                            @php
                                // Registro da análise (pne / lgbt) e o arquivo enviado.
                                $analysis = $user->{$type->relation()};
                                $status = $analysis?->status;
                                $file = $analysis?->{$type->fileField()};

                                $meta = $statusMap[$status] ?? [
                                    'label' => '-',
                                    'class' => 'bg-secondary',
                                    'icon' => 'bi-dash',
                                ];

                                $hasObservation = in_array($status, ['accepted', 'rejected'])
                                    && $analysis?->observations;

                                // Nome Social: mostra sempre o nome civil. PcD: se o nome
                                // social já foi deferido, ele aparece no lugar do civil.
                                $displayName = (! $isLgbt && $user->lgbt?->status === 'accepted')
                                    ? $user->lgbt->name
                                    : $user->name;

                                // O tipo do recurso usa o mesmo nome da relação (pne / lgbt).
                                $appeal = $user->appeals->firstWhere('type', $type->relation());

                                $routeParams = ['type' => $type->value, 'user' => $user->id];

                                // Análise inicial: somente candidatos pendentes.
                                $canAnalyze = ! $allocated
                                    && $status === 'pending';

                                // Recurso: somente candidatos indeferidos
                                // que ainda não possuem recurso registrado.
                                $canRegisterAppeal = ! $allocated
                                    && $status === 'rejected'
                                    && ! $appeal;

                                // Recursos existentes continuam disponíveis para consulta.
                                $canViewAppeal = $status === 'rejected'
                                    && (bool) $appeal;
                            @endphp

                            <tr data-id="{{ $user->id }}">

                                <th scope="row" class="text-center">
                                    {{ $user->inscription?->id ?? '-' }}
                                </th>

                                <td>
                                    {{ $displayName }}
                                </td>

                                <td class="text-center">
                                    {{ $analysis?->{$ui['detailField']} ?: '-' }}
                                </td>

                                <td class="text-center">

                                    @if ($file)

                                        <a href="{{ Storage::url($file) }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="btn btn-outline-primary btn-sm"
                                            title="{{ $type->fileButton() }} em nova aba"
                                            aria-label="{{ $type->fileButton() }} de {{ $user->name }} em nova aba">

                                            <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                            {{ $type->fileButton() }}
                                        </a>

                                    @else

                                        <span class="text-muted">Nenhum arquivo</span>

                                    @endif

                                </td>

                                {{-- Status do candidato. --}}
                                <td class="text-center"
                                    data-order="{{ $status }}"
                                    data-search="{{ $status }}">

                                    <span class="badge {{ $meta['class'] }} d-inline-flex align-items-center gap-1"

                                        @if ($hasObservation)
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            data-bs-title="{{ $analysis->observations }}"
                                        @endif>

                                        <i class="bi {{ $meta['icon'] }}" aria-hidden="true"></i>

                                        {{ $meta['label'] }}

                                        @if ($hasObservation)
                                            <i class="bi bi-chat-left-text" aria-hidden="true"></i>
                                            <span class="visually-hidden">
                                                Observação: {{ $analysis->observations }}
                                            </span>
                                        @endif

                                    </span>

                                    @if ($status === 'pending')
                                        <div class="small text-muted">
                                            Aguardando análise
                                        </div>
                                    @endif

                                </td>

                                {{-- Ações disponíveis. --}}
                                <td class="col-actions">

                                    <div class="d-flex gap-1">

                                        {{-- Imprimir dados do candidato. --}}
                                        <a href="{{ route('admin.analyses.pdf', [
                                            'type' => $type->value,
                                            'id' => $user->id,
                                        ]) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Imprimir dados do candidato"
                                            aria-label="Imprimir dados de {{ $user->name }}"
                                            target="_blank">

                                            <i class="bi bi-printer" aria-hidden="true"></i>

                                            <span class="d-none d-xl-inline">
                                                Imprimir
                                            </span>

                                        </a>

                                        {{-- Análise inicial: deferir ou indeferir. --}}
                                        @if ($canAnalyze)

                                            <a href="{{ route('admin.analyses.accept.form', $routeParams) }}"
                                                class="btn btn-sm btn-success"
                                                title="Deferir"
                                                aria-label="Deferir {{ $user->name }}">

                                                <i class="bi bi-check-lg" aria-hidden="true"></i>

                                                <span class="d-none d-xl-inline">
                                                    Deferir
                                                </span>

                                            </a>

                                            <a href="{{ route('admin.analyses.reject.form', $routeParams) }}"
                                                class="btn btn-sm btn-danger"
                                                title="Indeferir"
                                                aria-label="Indeferir {{ $user->name }}">

                                                <i class="bi bi-x-lg" aria-hidden="true"></i>

                                                <span class="d-none d-xl-inline">
                                                    Indeferir
                                                </span>

                                            </a>

                                        @endif

                                        {{-- Consulta ou registro de recurso. --}}
                                        @if ($canViewAppeal)

                                            <a href="{{ route('admin.appeals.show', $appeal) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Ver recurso"
                                                aria-label="Ver recurso de {{ $user->name }}">

                                                <i class="bi bi-folder2-open" aria-hidden="true"></i>

                                                <span class="d-none d-xl-inline">
                                                    Ver recurso
                                                </span>

                                            </a>

                                        @elseif ($canRegisterAppeal)

                                            <a href="{{ route('admin.appeals.create', [
                                                $user->id,
                                                $type->relation(),
                                            ]) }}"
                                                class="btn btn-sm btn-warning"
                                                title="Registrar recurso"
                                                aria-label="Registrar recurso de {{ $user->name }}">

                                                <i class="bi bi-folder-plus" aria-hidden="true"></i>

                                                <span class="d-none d-xl-inline">
                                                    Registrar recurso
                                                </span>

                                            </a>

                                        @endif

                                        {{-- Indicação de encerramento das análises. --}}
                                        @if ($allocated)

                                            <span class="text-muted small align-self-center">
                                                <i class="bi bi-lock" aria-hidden="true"></i>
                                                Análise encerrada
                                            </span>

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

    <!-- DataTables -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

    <!-- PDF e Excel -->
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

            // Usa a instância criada pelo shared.js;
            // caso não exista, cria uma configuração básica.
            const table = $.fn.dataTable.isDataTable($table) ?
                $table.DataTable() :
                $table.DataTable({
                    responsive: true,
                    order: [
                        [0, 'asc']
                    ]
                });

            const STATUS_COLUMN = 4;

            // Filtro por situação.
            $('.status-filter').on('click', '[data-filter]', function() {

                const value = $(this).data('filter');

                $('.status-filter [data-filter]')
                    .removeClass('active')
                    .attr('aria-pressed', 'false');

                $(this)
                    .addClass('active')
                    .attr('aria-pressed', 'true');

                table.column(STATUS_COLUMN)
                    .search(value ? '^' + value + '$' : '', true, false)
                    .draw();

            });

            // Inicialização dos tooltips.
            const initTooltips = () => {

                if (!window.bootstrap) return;

                document.querySelectorAll(
                    '#subscribers [data-bs-toggle="tooltip"]'
                ).forEach(el => {
                    bootstrap.Tooltip.getOrCreateInstance(el);
                });

            };

            initTooltips();

            table.on('draw.dt responsive-display.dt', initTooltips);

        });
    </script>

@endpush