@extends('layouts.admin')

@section('page-title', 'Inscrições')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-folder2-open text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Lista de Recursos</h6>
            </div>

            {{-- <button id="pdfButton" class="btn btn-sm btn-danger">
                <i class="bi bi-filetype-pdf"></i>
                <span>Gerar PDF (Todos)</span>
            </button> --}}
        </div>
        <table id="subscribers" class="table table-striped table-hover caption-top align-middle">
            <caption>Lista Geral de Inscritos</caption>
            <thead class="table-success text-center">
                <tr>
                    <th><i class="bi bi-list me-1"></i>#</th>
                    <th><i class="bi bi-hash me-1"></i>Inscrição</th>
                    <th><i class="bi bi-person me-1"></i>Candidato</th>
                    <th><i class="bi bi-credit-card me-1"></i>CPF</th>
                    <th><i class="bi bi-gear me-1"></i>Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <!-- Os dados serão carregados -->
            </tbody>
        </table>
    </div>
@endsection

@push('plugins')
    
@endpush

@push('scripts')
    
@endpush
