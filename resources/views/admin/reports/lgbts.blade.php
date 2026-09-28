@extends('layouts.admin')

@section('page-title', 'Relatório - Candidatos com Nome Social')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-gender-trans me-1"></i>
                <h6 class="mb-0 text-muted fw-normal">Candidatos com Nome Social</h6>
            </div>

            <button id="pdfButton" class="btn btn-sm btn-danger">
                <i class="bi bi-filetype-pdf"></i>
                <span>Gerar PDF</span>
            </button>
        </div>

    </div>

@endsection

@push('scripts')
    
@endpush
