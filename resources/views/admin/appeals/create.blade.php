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

                <form method="POST" action="{{ route('admin.appeals.store', [$user->id, $type]) }}">
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
