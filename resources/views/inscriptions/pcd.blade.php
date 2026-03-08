@extends('layouts.admin.master')

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
    <script src="{{ asset('assets/js/datatables/pcd.js') }}"></script>
@endpush