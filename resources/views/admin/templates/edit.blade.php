@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF ' . config('app.year') . ' - Modelos de Autorização de Uso de Nome Social')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-zip text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Editar Modelo de Autorização de Uso de Nome Social</h6>
            </div>
        </div>

        <form id="form-file" action="{{ route('admin.templates.update', $template->id) }}" method="POST"
            enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            {{-- Ano do processo seletivo --}}
            <div class="form-group mb-3">
                <label for="process_id" class="form-label required">Processo Seletivo:</label>
                <select name="process_id" id="process_id" class="form-control @error('process_id') is-invalid @enderror"
                    required>
                    <option value="">Selecione o processo...</option>
                    @foreach ($vests as $vest)
                        <option value="{{ $vest->id }}" {{ old('process_id') == $vest->id ? 'selected' : '' }}>
                            Vestibulinho {{ $vest->year }}
                        </option>
                    @endforeach
                </select>
                @error('process_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Arquivo relacionado --}}
            <div class="form-group mb-3">
                <label for="file" class="form-label required">Selecione o arquivo do
                    modelo:</label>
                <div class="d-flex align-items-center gap-3">
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                        id="file">
                    <a href="{{ Storage::disk('public')->url($template->file_path) }}" target="_blank"
                        class="text-decoration-none text-nowrap">Arquivo atual <i
                            class="bi bi-box-arrow-up-right ms-1"></i></a>
                </div>
                @error('file')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle me-1"></i>Salvar
                </button>
                <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary btn-sm ms-2">
                    <i class="bi bi-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </form>

    </div>

@endsection
