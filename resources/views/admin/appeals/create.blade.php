@extends('layouts.admin')

@section('page-title', 'Registrar Recurso')

@section('content')

    @php
        $cancelRoute = $type === 'pne' ? route('admin.inscriptions.pcds') : route('admin.inscriptions.lgbts');
    @endphp

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-folder-plus"></i>
                <h6 class="mb-0 text-muted fw-normal">
                    Registrar Recurso - {{ $type === 'pne' ? 'Laudo/Relatório' : 'Nome Social' }}
                </h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <dl class="row mb-4">
                    <dt class="col-sm-3">Candidato</dt>
                    <dd class="col-sm-9">{{ $user->name }}</dd>

                    <dt class="col-sm-3">Inscrição</dt>
                    <dd class="col-sm-9">{{ $user->inscription?->id }}</dd>

                    @if ($type === 'pne')
                        <dt class="col-sm-3">Condição</dt>
                        <dd class="col-sm-9">{{ $original->description ?? '-' }}</dd>

                        <dt class="col-sm-3">Apoio solicitado</dt>
                        <dd class="col-sm-9">{{ $original->support ?? '-' }}</dd>
                    @else
                        <dt class="col-sm-3">Nome Social</dt>
                        <dd class="col-sm-9 text-primary fw-bold">{{ $original->name ?? '-' }}</dd>
                    @endif

                    <dt class="col-sm-3">Motivo do indeferimento</dt>
                    <dd class="col-sm-9">{{ $original->observations ?? '-' }}</dd>
                </dl>

                <form id="appeal-form" method="POST" action="{{ route('admin.appeals.store', [$user->id, $type]) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="protocol" class="form-label">Número do protocolo</label>
                        <input type="text" name="protocol" id="protocol" maxlength="30"
                            class="form-control @error('protocol') is-invalid @enderror" value="{{ old('protocol') }}"
                            placeholder="Digite o número do protocolo ou deixe em branco" autofocus>

                        @error('protocol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            <i class="bi bi-info-circle"></i>
                            Se o recurso não tiver número de protocolo, deixe o campo em branco:
                            o sistema vai gerar um automaticamente (formato
                            <strong>REC-{{ now()->year }}-XXXXXX</strong>).
                        </div>

                        @if ($protocols->isNotEmpty())
                            <div class="form-text">
                                Últimos protocolos registrados (em ordem decrescente):
                                <strong>{{ $protocols->implode(', ') }}</strong>
                            </div>
                        @else
                            <div class="form-text">
                                Nenhum protocolo registrado ainda.
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="allegations" class="form-label">Alegações do candidato (opcional)</label>
                        <textarea name="allegations" id="allegations" rows="3"
                            class="form-control @error('allegations') is-invalid @enderror"
                            placeholder="Digite as alegações do candidato sobre o recurso">{{ old('allegations') }}</textarea>

                        @error('allegations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="observations" class="form-label">Observações do administrador (opcional)</label>
                        <textarea name="observations" id="observations" rows="3"
                            class="form-control @error('observations') is-invalid @enderror" placeholder="Digite suas observações sobre o recurso">{{ old('observations') }}</textarea>

                        @error('observations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="path" class="form-label">Arquivo do recurso apresentado pelo candidato (opcional)</label>
                        <input type="file" name="path" id="path"
                            class="form-control @error('path') is-invalid @enderror">

                        @error('path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            <i class="bi bi-info-circle"></i>
                            O arquivo deve estar em formato PDF, imagens (jpg, png, etc.) ou documentos (doc, docx) e ter no
                            máximo 2 MB.
                        </div>
                    </div>

                    <p>O recurso será registrado como <strong>em análise</strong>. Depois, você poderá deferir ou
                        indeferir.</p>

                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="bi bi-check-lg"></i> Registrar recurso
                    </button>
                    <a href="{{ $cancelRoute }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-x-lg"></i> Cancelar
                    </a>
                    </form>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('appeal-form');

            if (!form) return;

            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    return;
                }

                event.preventDefault();

                Swal.fire({
                    title: 'Confirmar registro do recurso?',
                    text: 'O recurso será registrado como "em análise". Depois, você poderá deferir ou indeferir.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sim, registrar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        HTMLFormElement.prototype.submit.call(form);
                    }
                });
            });
        });
    </script>
@endpush
