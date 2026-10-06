@extends('layouts.admin')

@section('page-title', 'Recurso')

@section('content')

    @php
        $user = $appeal->user;

        // Arquivo do pedido original (laudo ou autorização de nome social)
        $file = $appeal->type === 'pne' ? $original?->report : $original?->authorization;
        $fileLabel = $appeal->type === 'pne' ? 'Abrir laudo' : 'Abrir autorização';
    @endphp

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-folder2-open"></i>
                <h6 class="mb-0 text-muted fw-normal">Recurso - {{ $appeal->typeLabel() }}</h6>
            </div>
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

                    <form method="POST" action="{{ route('admin.appeals.destroy', $appeal) }}" class="delete-appeal-form">
                        @csrf
                        @method('DELETE')

                        <p class="text-muted mb-2">
                            Registrou este recurso por engano? Exclua para registrá-lo de novo.
                        </p>

                        <button type="submit" class="btn btn-sm btn-outline-danger">
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

            /*
             * Deferir / Indeferir
             */
            document.querySelectorAll('[data-swal-action]').forEach(button => {

                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const form = this.closest('form');
                    const action = this.dataset.swalAction;

                    if (action === 'defer') {

                        Swal.fire({
                            title: 'Deferir recurso?',
                            text: 'O recurso será deferido e o pedido do candidato também será deferido. O candidato será notificado por e-mail.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, deferir',
                            cancelButtonText: 'Cancelar',
                            reverseButtons: true
                        }).then(result => {

                            if (result.isConfirmed) {
                                form.action = this.formAction;
                                form.submit();
                            }

                        });

                    }

                    if (action === 'reject') {

                        const observations = form.querySelector('#observations');

                        if (!observations.value.trim()) {

                            Swal.fire({
                                title: 'Observações obrigatórias',
                                text: 'Informe as observações antes de indeferir o recurso.',
                                icon: 'warning',
                                confirmButtonText: 'Entendi'
                            }).then(() => {
                                observations.focus();
                            });

                            return;
                        }

                        Swal.fire({
                            title: 'Indeferir recurso?',
                            text: 'O recurso será indeferido. O candidato será notificado por e-mail.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, indeferir',
                            cancelButtonText: 'Cancelar',
                            reverseButtons: true
                        }).then(result => {

                            if (result.isConfirmed) {
                                form.action = this.formAction;
                                form.submit();
                            }

                        });
                    }
                });
            });


            /*
             * Excluir recurso
             */
            document.querySelectorAll('.delete-appeal-form').forEach(form => {

                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Excluir recurso?',
                        text: 'Depois da exclusão, você poderá registrar este recurso novamente.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then(result => {

                        if (result.isConfirmed) {
                            form.submit();
                        }

                    });
                });

            });

        });
    </script>
@endpush
