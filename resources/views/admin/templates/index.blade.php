@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF ' . config('app.year') . ' - Modelos de Autorização de Uso de Nome Social')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-zip text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Modelo de Autorização de Uso de Nome Social</h6>
            </div>

            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setFile">
                <i class="bi bi-upload me-1"></i> Importar
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-striped table caption-top">
                <caption>Modelos de Documentos</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ano do Processo</th>
                        <th scope="col">Arquivo</th>
                        <th scope="col">Status</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">

                    @forelse($files as $file)
                        <tr>
                            <td scope="row">{{ $file->year }}</td>
                            <td>
                                Vestibulinho {{ $file?->process->year }}
                            </td>
                            <td>
                                <a href="{{ route('admin.templates.preview', $file) }}" target="_blank">
                                    Visualizar <i class="bi bi-box-arrow-up-right ms-2"></i>
                                </a>
                            </td>
                            <td><span
                                    class="badge bg-{{ $file->status === 'active' ? 'success' : 'warning' }}">{{ $file->status === 'active' ? 'publicado' : 'não publicado' }}</span>
                            </td>
                            <td class="d-flex align-items-center justify-content-center gap-2">

                                {{-- Botão de publicar (alterar status) --}}
                                <form id="archive-form-{{ $file->id }}"
                                    action="{{ route('admin.templates.publish', $file->id) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button type="button"
                                    class="btn btn-sm btn-{{ $file->status === 'active' ? 'secondary' : 'success' }} l"
                                    title="{{ $file->status === 'active' ? 'Ocultar' : 'Publicar' }}"
                                    onclick="confirmFilePublish({{ $file->id }}, 'Vestibulinho {{ $file->year }}')">
                                    <i class="bi bi-{{ $file->status === 'active' ? 'eye-slash' : 'eye' }} me-1"></i>
                                    {{ $file->status === 'active' ? 'Ocultar' : 'Publicar' }}
                                </button>

                                <a href="{{ route('admin.templates.edit', $file->id) }}" class="btn btn-sm btn-primary l"
                                    title="Editar">
                                    <i class="bi bi-pencil-square me-1"></i> Editar
                                </a>

                                <form action="{{ route('admin.templates.destroy', $file->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger l" title="Excluir">
                                        <i class="bi bi-trash me-1"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum modelo encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="setFile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="createArchiveModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="setFileLabel"><i class="bi bi-file-earmark-zip me-1"></i>Importar
                            Modelo
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">

                                <form id="form-file" action="{{ route('admin.templates.store') }}" method="POST"
                                    enctype="multipart/form-data" novalidate>
                                    @csrf

                                    {{-- Ano do processo seletivo --}}
                                    <div class="form-group mb-3">
                                        <label for="process_id" class="form-label required">Processo Seletivo:</label>
                                        <select name="process_id" id="process_id"
                                            class="form-control @error('process_id') is-invalid @enderror" required>
                                            <option value="">Selecione o processo</option>
                                            @foreach ($vests as $vest)
                                                <option value="{{ $vest->id }}"
                                                    {{ old('process_id') == $vest->id ? 'selected' : '' }}>
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
                                        <input type="file" name="file"
                                            class="form-control @error('file') is-invalid @enderror" id="file"
                                            required>
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
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
                                class="bi bi-x-circle me-1"></i>Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- <script src="{{ asset('assets/js/rules/templates/index.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/swa/templates/publish.js') }}"></script> --}}
@endpush
