@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Acervo de Provas')

@section('dash-content')

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="bi bi-file-earmark-zip me-2"></i>Acervo de Provas</h5>
    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setFile">
      <i class="bi bi-plus-circle me-1"></i> Novo Arquivo
    </a>
  </div>

  <div class="table-responsive">
    <table class="table-striped table caption-top">
      <caption>Lista de Provas do Vestibulinho</caption>
      <thead class="table-success text-center">
        <tr>
          {{-- <th scope="col">#</th> --}}
          <th scope="col">Ano</th>
          <th scope="col">Prova</th>
          <th scope="col">Status</th>
          <th scope="col">Usuário</th>
          <th scope="col">Ações</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">

        @forelse($files as $file)
          <tr>
            <td scope="row">{{ $file->year }}</td>
            <td>
              <a href="{{ asset('storage/' . $file->file) }}" target="_blank">
                Vestibulinho {{ $file->year }}
              </a>
            </td>
            <td><span
                class="badge bg-{{ $file->status ? 'success' : 'warning' }}">{{ $file->status ? 'publicado' : 'não publicado' }}</span>
            </td>
            <td>{{ $file->user->name }}</td>
            <td class="d-flex justify-content-center align-items-center">
              {{-- Botão de publicar (alterar status) --}}
              <form id="archive-form-{{ $file->id }}" action="{{ route('archive.publish', $file->id) }}"
                method="POST" class="d-none">
                @csrf
                @method('PUT')
              </form>
              <button type="button" class="btn btn-sm btn-{{ $file->status ? 'warning' : 'success' }} me-2"
                onclick="confirmFilePublish({{ $file->id }}, 'Vestibulinho {{ $file->year }}')">
                <i class="bi bi-{{ $file->status ? 'eye-slash' : 'eye' }} me-1"></i>
                {{ $file->status ? 'Não Publicar' : 'Publicar' }}
              </button>
              <a href="{{ route('archive.edit', $file->id) }}" class="btn btn-sm btn-primary me-2" title="Editar">
                <i class="bi bi-pencil-square me-2"></i> Editar
              </a>
              <form action="{{ route('archive.destroy', $file->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                  <i class="bi bi-trash me-2"></i> Excluir
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="3">Nenhuma prova encontrada.</td>
          </tr>
        @endforelse

      </tbody>
    </table>
  </div>

  <div class="modal fade" id="setFile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="setFileLabel">Arquivar Prova</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="card shadow-sm">
            <div class="card-body">
              <form id="form-file" action="{{ route('archive.create') }}" method="POST"
                enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Ano em que a prova foi aplicada --}}
                <div class="form-floating mb-3">
                  <input type="name" name="year" class="form-control @error('year') is-invalid @enderror"
                    id="year" placeholder="Ano em que a prova foi realizada" value="{{ old('year') }}" required>
                  <label for="year" class="form-label required">Ano em que a prova foi aplicada</label>
                  @error('year')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                {{-- Arquivo relacionado --}}
                <div class="form-floating mb-3">
                  <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                    id="file" placeholder="Endereço" value="{{ old('file') }}" required>
                  <label for="file" class="form-label required">Arquivo relacionado</label>
                  @error('file')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Gravar
                  </button>
                </div>
              </form>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('plugins')
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
  <script src="{{ asset('assets/swa/archives/publish.js') }}"></script>
@endpush