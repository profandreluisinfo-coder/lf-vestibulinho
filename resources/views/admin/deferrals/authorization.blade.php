@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF - Laudo/Relatório')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-medical"></i>
                <h6 class="mb-0 text-muted fw-normal">{{ $action === 'accept' ? 'Deferir' : 'Indeferir' }} Autorização de Nome Social</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <dl class="row mb-4">
                    <dt class="col-sm-3">Candidato</dt>
                    <dd class="col-sm-9">{{ $user->name }}</dd>
                    
                    <dt class="col-sm-3">Nome Social</dt>
                    <dd class="col-sm-9 text-primary fw-bold">{{ $user->lgbt?->name }}</dd>

                    <dt class="col-sm-3">Inscrição</dt>
                    <dd class="col-sm-9">{{ $user->inscription?->id }}</dd>

                    <dt class="col-sm-3">Observações</dt>
                    <dd class="col-sm-9">{{ $user->lgbt?->observations ?? '-' }}</dd>

                    <dt class="col-sm-3">Autorização</dt>
                    <dd class="col-sm-9">
                        <a href="{{ Storage::url($user->lgbt->authorization) }}" target="_blank"
                            class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-file-earmark-medical"></i> Abrir autorização
                        </a>
                    </dd>
                </dl>

                @if ($action === 'accept')

                    <p>Confirma o <strong>deferimento</strong> da autorização de uso de nome social deste candidato?
                        <strong>O candidato será notificado por e-mail.</strong></p>

                    <form method="POST" action="{{ route('admin.deferrals.accept.authorization', $user->id) }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-check-lg"></i> Confirmar deferimento
                        </button>
                        <a href="{{ route('admin.inscriptions.lgbts') }}" class="btn btn-sm btn-outline-secondary">
                            Cancelar
                        </a>
                    </form>

                @else

                    <form method="POST" action="{{ route('admin.deferrals.reject.authorization', $user->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="reason" class="form-label">Razão do indeferimento (opcional)</label>
                            <textarea name="reason" id="reason" class="form-control" rows="4"
                                placeholder="Digite aqui a razão..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-lg"></i> Confirmar indeferimento
                        </button>
                        <a href="{{ route('admin.inscriptions.lgbts') }}" class="btn btn-sm btn-outline-secondary">
                            Cancelar
                        </a>
                    </form>

                @endif

            </div>
        </div>
    </div>

@endsection