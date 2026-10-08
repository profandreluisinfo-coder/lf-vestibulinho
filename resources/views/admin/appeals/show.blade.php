@extends('layouts.admin')

@section('page-title', 'Recurso')

@section('content')

    @php
        $user = $appeal->user;

        // Arquivo do pedido original (laudo ou autorização de nome social)
        $file = $appeal->type === 'pne' ? $original?->report : $original?->authorization;
        $fileLabel = $appeal->type === 'pne' ? 'Abrir laudo' : 'Abrir autorização';
        $status = $appeal->isPending();
    @endphp

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-folder2-open"></i>
                <h6 class="mb-0 text-muted fw-normal">Recurso - {{ $appeal->typeLabel() }}</h6>
            </div>
            @if (! $status)
                <a href="{{ route('admin.appeals.pdf', $appeal) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-file-earmark-pdf"></i> Gerar PDF
                </a>
            @endif
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

        <div class="card">
            <div class="card-body">

                {{-- Dados do recurso --}}
                <dl class="row mb-4">
                    <dt class="col-sm-3">Candidato</dt>
                    <dd class="col-sm-9"><span class="fw-medium">{{ $user->name }}</span></dd>

                    <dt class="col-sm-3">Inscrição</dt>
                    <dd class="col-sm-9">{{ $user->inscription?->id }}</dd>

                    <dt class="col-sm-3">Protocolo</dt>
                    <dd class="col-sm-9"><span class="badge bg-secondary">{{ $appeal->protocol }}</span></dd>

                    <dt class="col-sm-3">Registrado em</dt>
                    <dd class="col-sm-9">{{ $appeal->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-3">Situação do recurso</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $appeal->badgeClass() }}">{{ $appeal->statusLabel() }}</span>
                    </dd>

                    <dt class="col-sm-3">Alegações do recurso</dt>
                    <dd class="col-sm-9">{{ $appeal->allegations ?? '-' }}</dd>

                    <dt class="col-sm-3">Observações do recurso</dt>
                    <dd class="col-sm-9">{{ $appeal->observations ?? '-' }}</dd>

                    {{-- Arquivo do recurso --}}
                    <dt class="col-sm-3">Arquivo do recurso</dt>
                    <dd class="col-sm-9">
                        @if ($appeal->path && Storage::disk('public')->exists($appeal->path))
                            <a href="{{ Storage::url($appeal->path) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="bi bi-file-earmark-medical"></i> Abrir arquivo do recurso
                            </a>
                        @else
                            <span class="text-muted">Nenhum arquivo</span>
                        @endif
                    </dd>

                    @unless ($appeal->isPending())
                        <dt class="col-sm-3">Decidido em</dt>
                        <dd class="col-sm-9">
                            {{ $appeal->decided_at?->format('d/m/Y H:i') }}
                            @if ($appeal->decider)
                                por {{ $appeal->decider->name }}
                            @endif
                        </dd>

                        <dt class="col-sm-3">Observações</dt>
                        <dd class="col-sm-9">{{ $appeal->observations ?? '-' }}</dd>
                    @endunless
                </dl>

                {{-- Pedido original --}}
                <h6 class="text-muted fw-semibold border-top border-bottom pb-2 pt-3 mb-3">Pedido original</h6>

                <dl class="row mb-4">
                    @if ($appeal->type === 'pne')
                        <dt class="col-sm-3">Condição</dt>
                        <dd class="col-sm-9">{{ $original?->description ?? '-' }}</dd>

                        <dt class="col-sm-3">Apoio solicitado</dt>
                        <dd class="col-sm-9">{{ $original?->support ?? 'Não Informado' }}</dd>
                    @else
                        <dt class="col-sm-3">Nome Social</dt>
                        <dd class="col-sm-9 text-primary fw-bold">{{ $original?->name ?? '-' }}</dd>
                    @endif

                    <dt class="col-sm-3">Observações do pedido</dt>
                    <dd class="col-sm-9">{{ $original?->observations ?? '-' }}</dd>

                    <dt class="col-sm-3">Documento</dt>
                    <dd class="col-sm-9">
                        @if ($file && Storage::disk('public')->exists($file))
                            <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="bi bi-file-earmark-medical"></i> {{ $fileLabel }}
                            </a>
                        @else
                            <span class="text-muted">Nenhum arquivo</span>
                        @endif
                    </dd>
                </dl>

                {{-- Decisão: só aparece enquanto o recurso está em análise --}}
                @if ($appeal->isPending())
                    <form method="POST" action="{{ route('admin.appeals.accept', $appeal) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="observations" class="form-label">
                                Observações (obrigatório para indeferir)
                            </label>
                            <textarea name="observations" id="observations" rows="4"
                                class="form-control @error('observations') is-invalid @enderror"
                                placeholder="Digite aqui o parecer sobre o recurso...">{{ old('observations') }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">O candidato verá este texto quando o recurso for indeferido.</div>
                        </div>

                        <p>Ao <strong>deferir</strong>, o pedido do candidato também passa a <strong>Deferido</strong>.
                            Ao <strong>indeferir</strong>, o pedido continua como está.
                            <strong>Nos dois casos, o candidato será notificado por e-mail.</strong>
                        </p>

                        <button type="submit" class="btn btn-sm btn-success"
                            formaction="{{ route('admin.appeals.accept', $appeal) }}" data-swal-action="defer">
                            <i class="bi bi-check-lg"></i> Deferir recurso
                        </button>

                        <button type="submit" class="btn btn-sm btn-danger"
                            formaction="{{ route('admin.appeals.reject', $appeal) }}" data-swal-action="reject">
                            <i class="bi bi-x-lg"></i> Indeferir recurso
                        </button>

                        <a href="{{ route('admin.appeals.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </form>

                    {{-- Exclusão: só enquanto o recurso está em análise --}}
                    <hr class="my-4">

                    <form method="POST" action="{{ route('admin.appeals.destroy', $appeal) }}">
                        @csrf
                        @method('DELETE')

                        <p class="text-muted mb-2">
                            Registrou este recurso por engano? <span class="text-danger">Exclua para registrá-lo de
                                novo.</span>
                        </p>

                        <button type="submit" class="btn btn-sm btn-outline-danger" data-swal-action="delete">
                            <i class="bi bi-trash"></i> Excluir recurso
                        </button>
                    </form>
                @else
                    <a href="{{ route('admin.appeals.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                @endif

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Textos e cores de cada ação
            const acoes = {
                defer: {
                    title: 'Deferir este recurso?',
                    text: 'O pedido do candidato também ficará como Deferido e ele será notificado por e-mail.',
                    icon: 'question',
                    confirmButtonText: 'Sim, deferir',
                    confirmButtonColor: '#198754'
                },
                reject: {
                    title: 'Indeferir este recurso?',
                    text: 'O pedido continuará como está e o candidato será notificado por e-mail com o seu parecer.',
                    icon: 'warning',
                    confirmButtonText: 'Sim, indeferir',
                    confirmButtonColor: '#dc3545'
                },
                delete: {
                    title: 'Excluir este recurso?',
                    text: 'Esta ação não pode ser desfeita.',
                    icon: 'warning',
                    confirmButtonText: 'Sim, excluir',
                    confirmButtonColor: '#dc3545'
                }
            };

            document.querySelectorAll('[data-swal-action]').forEach(function(botao) {
                botao.addEventListener('click', function(e) {
                    e.preventDefault(); // segura o envio até o usuário confirmar

                    const tipo = botao.dataset.swalAction;
                    const config = acoes[tipo];
                    const form = botao.form;

                    if (!config) return;

                    // Para indeferir, o parecer é obrigatório
                    if (tipo === 'reject') {
                        const campo = document.getElementById('observations');

                        if (!campo.value.trim()) {
                            campo.classList.add('is-invalid');
                            Swal.fire({
                                icon: 'info',
                                title: 'Falta o parecer',
                                text: 'Escreva as observações para poder indeferir o recurso.',
                                confirmButtonText: 'Entendi'
                            }).then(function() {
                                campo.focus();
                            });
                            return;
                        }
                    }

                    Swal.fire({
                        title: config.title,
                        text: config.text,
                        icon: config.icon,
                        showCancelButton: true,
                        confirmButtonText: config.confirmButtonText,
                        confirmButtonColor: config.confirmButtonColor,
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                        focusCancel: true
                    }).then(function(resultado) {
                        if (resultado.isConfirmed) {
                            // Envia o formulário usando o botão clicado,
                            // assim o formaction (accept/reject) é respeitado
                            form.requestSubmit(botao);
                        }
                    });
                });
            });
        });
    </script>
@endpush
