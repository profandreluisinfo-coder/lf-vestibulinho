@extends('layouts.admin')

@section('page-title', 'Relatório - Candidatos com Nome Social')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-universal-access me-1"></i>
                <h6 class="mb-0 text-muted fw-normal">Candidatos com Nome Social</h6>
            </div>

            <a id="pdfButton" href="{{ route('admin.reports.lgbts.pdf') }}" class="btn btn-sm btn-danger">
                <i class="bi bi-filetype-pdf"></i>
                <span>Gerar PDF</span>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover caption-top align-middle">
                <caption>Candidatos com uso de Nome Social Aprovado</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th><i class="bi bi-hash me-1"></i>Inscrição</th>
                        <th><i class="bi bi-person me-1"></i>Candidato</th>
                        <th><i class="bi bi-credit-card me-1"></i>Nome Social</th>
                        <th><i class="bi bi-credit-card me-1"></i>CPF</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($lgbts as $lgbt)
                        <tr>
                            <td class="text-center">{{ $lgbt->user->inscription?->id ?? '-' }}</td>
                            <td>{{ $lgbt->user->name }}</td>
                            <td>{{ $lgbt?->name }}</td>
                            <td class="text-center">{{ $lgbt->user->cpf }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Nenhum candidato encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            
        </div>

    </div>

@endsection