@extends('layouts.admin')

@section('page-title', 'Modelos de Autorização de Uso de Nome Social')

@section('breadcrumb')
    @include('partials.admin.breadcrumb', [
        'items' => [
            ['label' => 'Início', 'url' => route('admin.index')],
            ['label' => 'Modelos', 'url' => route('admin.templates.index')],
            ['label' => 'Editar'],
        ],
    ])
@endsection

@section('content')

    <div class="container">
        {{-- Cabeçalho da página --}}
        <div class="page-header">
            <div class="page-header-title">
                <div class="icon-wrap">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <h4>Editar Modelo</h4>
                    <span>Edite o modelo de autorização de uso de nome social</span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="form-file" action="{{ route('admin.templates.update', ['template' => $template]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Ano do processo seletivo --}}
                    <div class="form-group mb-3">
                        <label for="process_id" class="form-label required">Processo Seletivo</label>
                        <select name="process_id" id="process_id"
                            class="form-control @error('process_id') is-invalid @enderror"
                            aria-label="Selecione o processo seletivo">
                            <option value="">Selecione o processo...</option>
                            @foreach ($vests as $vest)
                                <option value="{{ $vest->id }}"
                                    {{ old('process_id', $template->process_id) == $vest->id ? 'selected' : '' }}>
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
                        <label for="file" class="form-label required">Arquivo do modelo</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                id="file" aria-label="Selecione o arquivo do modelo">
                            <a href="{{ Storage::disk('public')->url($template->file_path) }}" target="_blank"
                                class="text-decoration-none text-nowrap"><i class="bi bi-eye"></i> Arquivo atual</a>
                        </div>
                        @error('file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-lg"></i>Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
