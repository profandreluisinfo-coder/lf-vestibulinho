@extends('layouts.admin.master')

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
            <h5 class="mb-0"><i class="bi bi-person me-2"></i>Pessoas com Deficiência</h5>
        </div>
        @if (!$users->isEmpty())
            <div class="alert alert-info d-flex align-items-center shadow-sm alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <div class="d-flex align-items-center fw-semibold">
                    <i class="bi bi-info-circle fs-5 me-2"></i>
                    Para encontrar um registro específico, digite na caixa de pesquisa o número da inscrição, ou qualquer parte do nome
                    do candidato.
                </div>
            </div>
        @endif
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table id="subscribers" class="table table-striped table-hover freezed-table caption-top align-middle">
                <caption>{{ config('app.name') }} {{ $calendar->year }} - Lista de Candidatos com Necessidades Especiais</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">Inscrição</th>
                        <th scope="col">Candidato</th>
                        <th scope="col">Laudo/Relatório</th>
                        <th scope="col">Situação</th>
                        <th scope="col">Observações</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->inscription?->id }}</th>
                            <td>{{ ($user->authorization_accepted == 1) ? $user->social_name : $user->name }}</td>
                            <td>
                                @if ($user->user_detail?->pne_report)
                                    <a href="{{ asset('storage/' . $user->user_detail->pne_report) }}"
                                        class="btn btn-sm btn-link text-decoration-none" target="_blank">
                                        <i class="bi bi-eye"></i> Visualizar
                                    </a>
                                @else
                                    Não apresentou
                                @endif
                            </td>
                            <td>
                                @if ($user->user_detail?->pne_report_accepted === null)
                                    Pendente de análise
                                @elseif ($user->user_detail?->pne_report_accepted == 1)
                                    Deferido
                                @elseif ($user->user_detail?->pne_report_accepted == 2)
                                    Indeferido                                    
                                @endif
                            </td>
                            <td>
                                @if ($user->user_detail?->pne_report_rejection_reason)
                                    {{ $user->user_detail?->pne_report_rejection_reason }}
                                @endif
                            </td>
                            <td>
                                @if ($user->user_detail?->pne_report && $user->user_detail?->pne_report_accepted === null)
                                    <button class="btn btn-sm btn-success accept-report"
                                        data-url="{{ route('deferrals.accept.report', $user->id) }}"
                                        title="Deferir">

                                        <i class="bi bi-check me-1"></i> Deferir
                                    </button>

                                    <button class="btn btn-sm btn-danger reject-report"
                                        data-url="{{ route('deferrals.reject.report', $user->id) }}"
                                        title="Indeferir">

                                        <i class="bi bi-x me-1"></i> Indeferir
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum candidato encontrado</td>
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
    <script src="{{ asset('assets/js/datatables/pcd.js') }}"></script>
@endpush