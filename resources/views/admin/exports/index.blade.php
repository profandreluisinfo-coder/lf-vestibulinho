@extends('layouts.admin')

@section('page-title', 'Planilha de Notas')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [['label' => 'Início', 'url' => route('admin.index')], ['label' => 'Planilha de Notas']],
    ])
@endsection

@section('content')
    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-file-earmark-zip"></i>
                </div>
                <div>
                    <h4>Planilha de Notas</h4>
                    <span>
                        Exporte uma planilha do Excel para preenchimento das notas dos candidatos
                    </span>
                </div>
            </div>
        </div>

        @if ($appealsPeriodOpen)
            <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
                <div>
                    O prazo para recursos das inscrições indeferidas, PcD e nome social ainda está aberto
                    @if ($appealsEnd)
                        até {{ $appealsEnd->format('d/m/Y') }}
                    @endif
                    . A planilha pode ser gerada, mas os dados podem mudar após a análise dos recursos.
                </div>
            </div>
        @endif

        @unless ($canExportCandidates)
            <div class="alert alert-danger" role="alert">
                <strong>Exportação indisponível.</strong>
                <ul class="mb-0 mt-2">
                    @unless ($hasCurrentProcess)
                        <li>Não há um processo seletivo cadastrado.</li>
                    @endunless
                    @if ($hasCurrentProcess && !$hasApprovedInscriptions)
                        <li>Nenhuma inscrição deferida foi encontrada no processo atual.</li>
                    @endif
                    @if ($hasCurrentProcess && !$hasExam)
                        <li>Não existe prova agendada com candidatos alocados em salas no processo atual.</li>
                    @endif
                </ul>
            </div>
        @endunless

        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-4">
                @if ($canExportCandidates)
                    <a href="{{ route('admin.exports.excel') }}" class="card h-100 text-decoration-none text-dark shadow-sm"
                        id="export-candidates-card">
                    @else
                        <div class="card h-100 text-muted shadow-sm bg-light" aria-disabled="true">
                @endif
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi bi-file-earmark-spreadsheet {{ $canExportCandidates ? 'text-success' : 'text-muted' }} fs-2"
                        aria-hidden="true"></i>
                    <div>
                        <h5 class="card-title mb-1">Exportar planilha Excel</h5>
                        <p class="card-text {{ $canExportCandidates ? 'text-muted' : 'text-secondary' }} mb-0">
                            {{ $canExportCandidates ? 'Baixe a planilha de candidatos e notas.' : 'Disponível quando houver candidatos alocados em uma prova agendada.' }}
                        </p>
                    </div>
                </div>
                @if ($canExportCandidates)
                    </a>
                @else
            </div>
            @endif
        </div>
    </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('export-candidates-card')?.addEventListener('click', async function(event) {
            event.preventDefault();

            const card = this;
            if (card.dataset.loading === 'true') return;

            card.dataset.loading = 'true';
            card.setAttribute('aria-busy', 'true');

            try {
                const response = await fetch(card.href, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    const result = await response.json().catch(() => ({}));
                    throw new Error(result.message || 'Não foi possível gerar a planilha. Tente novamente.');
                }

                const contentType = response.headers.get('Content-Type') || '';
                if (!contentType.includes('spreadsheetml')) {
                    throw new Error('O servidor não retornou um arquivo de planilha válido.');
                }

                const blob = await response.blob();
                const objectUrl = URL.createObjectURL(blob);
                const downloadLink = document.createElement('a');
                downloadLink.href = objectUrl;
                downloadLink.download = 'candidatos.xlsx';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                downloadLink.remove();
                window.setTimeout(() => URL.revokeObjectURL(objectUrl), 1000);

                Swal.fire({
                    icon: 'success',
                    title: 'Planilha gerada!',
                    text: 'O download de candidatos.xlsx foi iniciado.',
                    timer: 3000,
                    showConfirmButton: false
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Não foi possível exportar',
                    text: error.message
                });
            } finally {
                delete card.dataset.loading;
                card.removeAttribute('aria-busy');
            }
        });
    </script>
@endpush
