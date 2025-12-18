@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Convocação para Matrícula')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-broadcast-pin me-2"></i>Convocação para matrícula</h5>

            @if (!empty($countResults))

                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setNewCall">
                    <i class="bi bi-plus-circle me-2"></i> Novo
                </a>

            @endif

        </div>

        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">

            @if ($callLists->isNotEmpty())

            <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="bi bi-bar-chart-fill me-2"></i> Convocados por Curso
            </button>

            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-bar-chart-fill me-2"></i>Convocados por Curso
                            </h5>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <canvas id="convocadosChart" height="100"></canvas>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle me-2"></i>Fechar</button>
                        </div>

                    </div>
                </div>
            </div>

            <table class="table table-striped freezed-table align-middle">
                <thead>
                    <tr class="table-success">
                        <th scope="col">Chamada</th>
                        <th scope="col">Data</th>
                        <th scope="col">Hora</th>
                        <th scope="col">Candidatos</th>
                        <th scope="col">Status</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($callLists as $callList)
                        <tr>
                            <th>{{ $callList->number }}</th> <!-- Número da chamada -->
                            <td>{{ \Carbon\Carbon::parse($callList->date)->format('d/m/Y') }}</td>
                            <!-- Data da chamada -->
                            <td>{{ \Carbon\Carbon::parse($callList->time)->format('H:i') }}</td>
                            <!-- Hora da chamada -->
                            <th>{{ $callList->calls_count }}</th> <!-- Quantidade de convocados -->
                            <td>
                                <span class="badge bg-{{ $callList->status == 'pending' ? 'warning' : 'success' }}">
                                    {{ $callList->status == 'pending' ? 'Pendente' : 'Finalizada' }}
                                </span>
                            </td> <!-- Status da chamada -->
                            <td>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    
                                    <!-- Botão de excluir -->
                                    <form id="delete-form-{{ $callList->id }}"
                                        action="{{ route('callings.destroy', $callList->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                            onclick="confirmDelete({{ $callList->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <!-- Botão de detalhes -->
                                    <button class="btn btn-sm btn-secondary text-white" title="Detalhes"  data-bs-toggle="collapse"
                                        data-bs-target="#details-{{ $callList->id }}" aria-expanded="false"
                                        aria-controls="details-{{ $callList->id }}">
                                        <i class="bi bi-info-circle"></i>
                                    </button>

                                    <!-- Botão de finalizar -->
                                    @if ($callList->status === 'pending')
                                        <form id="finalize-form-{{ $callList->id }}"
                                            action="{{ route('callings.finalize', $callList->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-sm btn-success" title="Finalizar chamada"
                                                onclick="confirmFinalize({{ $callList->id }})">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($callList->status === 'completed')
                                        {{-- <a href="{{ route('callings.excel', $callList->number) }}"
                                            class="btn btn-sm btn-success text-white">
                                            <i class="bi bi-file-earmark-excel"></i> Excel
                                        </a> --}}

                                        <a href="{{ route('callings.pdf', $callList->number) }}"
                                            class="btn btn-sm btn-primary text-white" title="Gerar PDF" target="_blank">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                    @endif

                                </div>
                            </td> <!-- Ações -->
                        </tr>
                        <tr class="collapse" id="details-{{ $callList->id }}">
                            <td colspan="6" class="p-0">
                                <div class="my-3 px-2">
                                    <table class="table table-bordered w-100 datatable mb-0 table align-middle">
                                        <thead>
                                            <tr class="table-warning">
                                                <th scope="col">Classificação</th>
                                                <th scope="col">Inscrição</th>
                                                <th scope="col">Nome</th>
                                                <th scope="col">CPF</th>
                                                <th scope="col">PCD</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider">
                                            @foreach ($callList->calls as $call)
                                                @php
                                                    $user = $call->examResult->inscription->user;
                                                @endphp
                                                <tr>
                                                    <td>{{ $call->examResult->ranking }}</td>
                                                    <td>{{ $call->examResult->inscription_id }}</td>
                                                    <td>{{ $user->social_name ?? $user->name }}</td>
                                                    <td>{{ $user->cpf }}</td>
                                                    <td>
                                                        @if ($user->pne)
                                                            <span class="badge bg-success" title="Candidato PCD"><i
                                                                    class="bi bi-universal-access"></i></span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @else

            @include('components.no-records', [
                        'message' => 'Causas de problemas com as chamadas:',
                        'submessage' => 'Provavelmente nenhuma chamada ainda foi regisstrada.',
                        'action' => true,
                        'actionMessage' =>
                            'Solução: Tente cadastrar uma nova chamada. Se o problema persistir, entre em contato com o suporte.',
                    ])

            @endif

        </div>

    {{-- Modal para criar uma chamada --}}
    <div class="modal fade" id="setNewCall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title" id="setLocalModalLabel"><i class="bi bi-plus-circle me-2"></i>Nova Chamada</h5>
                </div>
                <div class="modal-body">

                    <div class="card shadow-sm">

                        @php
                            $last_call = App\Models\Call::orderBy('call_number', 'desc')->first();
                            $amount = $last_call?->amount ?? 0;
                            $number_of_pcd = App\Models\Call::countPcdInLastCall();
                        @endphp

                        <form action="{{ route('callings.store') }}" method="POST" class="p-3" id="setNewCallForm">
                            @csrf

                            <div class="mb-3">
                                <label for="number" class="form-label">Número da Chamada</label>
                                <input type="number" name="number" id="number"
                                    class="form-control @error('number') is-invalid @enderror" min="1">
                                <div class="form-text text-success">
                                    @if ($last_call)
                                    <span class="fw-semibold"><i class="bi bi-info-circle me-2"></i>Ultima chamada registrada:</span> <span class="fs-6 fw-bold">{{ $last_call?->call_number }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="limit" class="form-label">Quantidade de Candidatos</label>
                                <input type="number" name="limit" id="limit"
                                    class="form-control @error('limit') is-invalid @enderror" min="1">
                                <div class="form-text text-success">
                                    @if ($last_call)
                                    <span class="fw-semibold"><i class="bi bi-info-circle me-2"></i>Quantidade de candidatos da última chamada:</span> <span class="fs-6 fw-bold">{{ $amount }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pne_exam_result_ids" class="form-label">Selecionar Candidatos PCD
                                    (opcional)</label>
                                <select name="manual_pcds[]" id="manual_pcds" class="form-select" multiple>
                                    @foreach ($pneCandidates as $candidate)
                                        <option value="{{ $candidate->id }}">
                                            {{ $candidate->inscription->user->name }} — CPF:
                                            {{ $candidate->inscription->user->cpf }} —
                                            Posição: {{ $candidate->ranking }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-success mt-2">
                                    <div class="fw-semibold"><i class="bi bi-info-circle me-2"></i>Número de candidatos PCD convocados até o momento: <span class="fs-6 fw-bold">{{ $number_of_pcd }}</span></div>
                                    <div class="fw-semibold"><i class="bi bi-info-circle me-2"></i>Segure Ctrl (ou Cmd) para selecionar múltiplos.</div>
                                    <div class="fw-semibold"><i class="bi bi-info-circle me-2"></i>Verifique se a posição do candidato já está contemplada na chamada.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Data de Comparecimento</label>
                                <input type="date" name="date" id="date"
                                    class="form-control @error('date') is-invalid @enderror">
                            </div>

                            <div class="mb-3">
                                <label for="time" class="form-label">Hora de Comparecimento</label>
                                <input type="time" name="time" id="time"
                                    class="form-control @error('time') is-invalid @enderror">
                            </div>

                            {{-- prettier-ignore --}}
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle me-1"></i>Salvar
                            </button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle me-2"></i>Fechar</button>
                </div>
            </div>
        </div>
    </div>

    

    </div>

@endsection

@push('plugins')

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
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

    <script src="{{ asset('assets/rules/user/calling.js') }}"></script>
    <script src="{{ asset('assets/swa/calls/delete.js') }}"></script>
    <script src="{{ asset('assets/swa/calls/finalize.js') }}"></script>
    <!-- Importa o Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Importa o plugin de datalabels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        const chartLabels = @json($chartData['labels']);
        const chartData = @json($chartData['data']);
        const total = chartData.reduce((acc, val) => acc + val, 0);

        chartLabels.push('Total');
        chartData.push(total);

        const fixedColors = ['#109618', '#000000', '#dc3912', '#3366cc'];

        const chartColors = chartLabels.map((label, index) => {
            if (label === 'Total') return '#888888'; // cinza para total
            return fixedColors[index % fixedColors.length];
        });

        const ctx = document.getElementById('convocadosChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Convocados',
                    data: chartData,
                    backgroundColor: chartColors,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        color: '#fff', // branco pra destacar dentro da barra
                        anchor: 'center', // centraliza na barra
                        align: 'center', // deixa no meio vertical
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value) => value
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                order: [
                    [0, 'asc']
                ], // Remove a ordenação padrão
                columnDefs: [{
                    orderable: false,
                    targets: [1, 2, 3, 4] // Colunas que não devem ser ordenáveis
                }],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                buttons: ["excel", "pdf", "print", "colvis"],
                responsive: true,
                autoWidth: true,
                lengthChange: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Tudo"]
                ],
                ordering: true,
                info: true,
                dom: 'lBfrtip'
            });
        });
    </script>

@endpush