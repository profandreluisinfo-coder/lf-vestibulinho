@extends('layouts.admin')

@section('page-title', 'Relatório - Pessoas com Deficiência')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-universal-access me-1"></i>
                <h6 class="mb-0 text-muted fw-normal">Pessoas com Deficiência</h6>
            </div>

            <a id="pdfButton" href="{{ route('admin.reports.pcds.pdf') }}" class="btn btn-sm btn-danger">
                <i class="bi bi-filetype-pdf"></i>
                <span>Gerar PDF</span>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover caption-top align-middle">
                <caption>Candidatos com Deficiência e Laudo/Relatório Médico Aprovado</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th><i class="bi bi-hash me-1"></i>Inscrição</th>
                        <th><i class="bi bi-person me-1"></i>Candidato</th>
                        <th><i class="bi bi-credit-card me-1"></i>CPF</th>
                        <th>Condição</th>
                        <th>Requer</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($pcds as $pcd)
                        <tr>
                            <td class="text-center">{{ $pcd->user->inscription->id ?? '-' }}</td>
                            <td>{{ $pcd->user->lgbt?->status === 'accepted' ? $pcd->user->lgbt?->name : $pcd->user->name }}
                            </td>
                            <td class="text-center">{{ $pcd->user->cpf ?? '-' }}</td>
                            <td>{{ $pcd->description }}</td>
                            <td>{{ $pcd->support }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Nenhum candidato encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $pcds->links() }}
            </div>
        </div>

    </div>

@endsection

@push('scripts')
@endpush
