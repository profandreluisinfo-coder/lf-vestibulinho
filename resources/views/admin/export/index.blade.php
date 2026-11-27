@extends('layouts.admin')

@section('page-title', 'Planilha de Notas')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [['label' => 'Início', 'url' => route('admin.index')], ['label' => 'Planilha de Notas']],
    ])
@endsection

@section('content')
    <div class="container">

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
                    <a href="{{ route('admin.export.excel') }}" class="card h-100 text-decoration-none text-dark shadow-sm"
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
    <div id="export-loading-overlay" class="export-loading-overlay" aria-hidden="true">
        <div class="export-loading-card" role="status" aria-live="polite" aria-atomic="true">
            <span class="spinner-border mb-3" aria-hidden="true"></span>
            <h2 class="h5 mb-2">Gerando planilha</h2>
            <p class="text-muted mb-0">Aguarde enquanto preparamos o arquivo para download.</p>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .export-loading-overlay {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: rgba(10, 20, 35, .68);
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, visibility .2s ease;
        }

        .export-loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .export-loading-card {
            width: min(100%, 360px);
            padding: 2rem;
            border-radius: 1rem;
            background: #fff;
            text-align: center;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .25);
        }

        .export-loading-overlay .spinner-border {
            width: 2.5rem;
            height: 2.5rem;
            color: var(--color-teal, #00a896);
        }

        @media (prefers-reduced-motion: reduce) {
            .export-loading-overlay {
                transition: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        const exportCard = document.getElementById('export-candidates-card');
        const exportOverlay = document.getElementById('export-loading-overlay');

        exportCard?.addEventListener('click', async function(event) {
            event.preventDefault();

            const card = this;
            if (card.dataset.loading === 'true') return;

            card.dataset.loading = 'true';
            card.setAttribute('aria-busy', 'true');
            card.setAttribute('aria-disabled', 'true');
            exportOverlay?.classList.add('show');
            exportOverlay?.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

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
                card.removeAttribute('aria-disabled');
                exportOverlay?.classList.remove('show');
                exportOverlay?.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }
        });
    </script>
@endpush
