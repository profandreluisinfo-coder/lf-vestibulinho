@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Editar Arquivo de Prova')

@section('dash-content')

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Arquivo de Prova</h4>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form id="form-file-edit" action="{{ route('archive.edit', $archive) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Ano em que a prova foi aplicada --}}
        <div class="form-floating mb-3">
          <input type="text" name="year" class="form-control @error('year') is-invalid @enderror" id="year"
            placeholder="Ano em que a prova foi realizada" value="{{ $archive->year }}">
          <label for="year" class="form-label required">Ano em que a prova foi aplicada</label>
          @error('year')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        {{-- Arquivo relacionado --}}
        <div class="form-floating mb-3">
          <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" id="file"
            placeholder="Endereço" required>
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

@endsection

@push('plugins')
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush