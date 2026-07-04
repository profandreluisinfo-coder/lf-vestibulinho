@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF ' . $process?->year . ' - Pessoas com Deficiência')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')

    <div class="container">
        <div class="page-header mb-4">
            <h4 class="mb-1">
                <i class="bi bi-universal-access"></i> Pessoas com Deficiência
            </h4>
            <small>
                Candidatos que solicitaram atendimento especializado.
            </small>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <table id="subscribers" class="table table-hover align-middle">
            <caption>Vestibulinho LF - {{ $process?->year }} - Lista de Candidatos com Necessidades Especiais</caption>
            <thead class="table-success text-center">
                <tr>
                    <th scope="col"><i class="bi bi-hash me-1"></i>Inscrição</th>
                    <th scope="col"><i class="bi bi-person me-1"></i>Candidato</th>
                    <th scope="col"><i class="bi bi-gear me-1"></i>Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr data-id="{{ $user->id }}">
                        <th scope="row">{{ $user->inscription?->id }}</th>
                        <td>
                            {{ $user->name }}

                            @if ($user->pne->status == 'pending')
                                <i class="bi bi-hourglass-split text-warning ms-2" data-bs-toggle="popover"
                                    data-bs-trigger="hover" data-bs-content="Pendente - Aguardando análise"></i>
                            @elseif($user->pne->status == 'accepted')
                                <i class="bi bi-check-circle-fill text-success ms-2" data-bs-toggle="popover"
                                    data-bs-trigger="hover" data-bs-content="Deferido - {{ $user->pne->observations ?? 'Nenhuma observação' }}"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                    data-bs-content="Indeferido - {{ $user->pne->observations ?? 'Nenhuma observação' }}"></i>
                            @endif
                        </td>
                        <td>
                            @if ($user->pne->status === 'pending')
                                <a href="{{ route('admin.deferrals.accept.report.form', $user->id) }}"
                                    class="btn btn-success btn-sm" title="Deferir">
                                    <i class="bi bi-check-lg"></i> Deferir
                                </a>

                                <a href="{{ route('admin.deferrals.reject.report.form', $user->id) }}"
                                    class="btn btn-danger btn-sm" title="Indeferir">
                                    <i class="bi bi-x-lg"></i> Indeferir
                                </a>
                            @endif

                            @if ($user->pne->status === 'accepted')
                                <a href="{{ route('admin.deferrals.reject.report.form', $user->id) }}"
                                    class="btn btn-danger btn-sm" title="Indeferir">
                                    <i class="bi bi-x-lg"></i> Indeferir
                                </a>
                            @endif

                            @if ($user->pne->status === 'rejected')
                                <a href="{{ route('admin.deferrals.accept.report.form', $user->id) }}"
                                    class="btn btn-success btn-sm" title="Deferir">
                                    <i class="bi bi-check-lg"></i> Deferir
                                </a>
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
        <div class="d-flex flex-wrap align-items-center gap-4 small">
            <div class="d-flex align-items-center">
                <i class="bi bi-hourglass-split text-warning me-2 fs-5"></i>
                <span>Pendente</span>
            </div>

            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                <span>Deferido</span>
            </div>

            <div class="d-flex align-items-center">
                <i class="bi bi-x-circle-fill text-danger me-2 fs-5"></i>
                <span>Indeferido</span>
            </div>
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
    <script src="{{ asset('assets/js/admin/datatables/pcd.js') }}"></script>
@endpush
