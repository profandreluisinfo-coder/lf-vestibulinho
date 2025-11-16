@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Calendário do Processo Seletivo')

@section('dash-content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Edital do Processo Seletivo</h4>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setNewFile">
                <i class="bi bi-plus-circle me-1"></i> Upload de Edital
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-success">
                    <tr>
                        <th scope="col">Arquivo</th>
                        <th scope="col">Status</th>
                        <th scope="col">Opções</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notices as $notice)
                        <tr>
                            <td>
                                <a href="{{ asset('storage/' . $notice->file) }}" target="_blank">
                                    Edital {{ config('app.year') }}.pdf
                                </a>
                            </td>
                            <td><span
                                    class="badge bg-{{ $notice->status == '1' ? 'success' : 'warning' }}">{{ $notice->status == '1' ? 'Publicado' : 'Publicar' }}</span>
                            </td>
                            <td>
                                {{-- Botão de publicar (alterar status) --}}
                                <form id="publish-notice-form-{{ $notice->id }}"
                                    action="{{ route('notice.publish', $notice->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button type="button"
                                    class="btn btn-sm btn-{{ $notice->status ? 'warning' : 'success' }} me-2"
                                    onclick="confirmNoticePublish({{ $notice->id }}, 'Edital {{ $notice->year }}')">
                                    <i class="bi bi-{{ $notice->status ? 'eye-slash' : 'eye' }} me-1"></i>
                                    {{ $notice->status ? 'Ocultar' : 'Publicar' }}
                                </button>

                                {{-- Botão de excluir --}}
                                <form id="delete-notice-form-{{ $notice->id }}"
                                    action="{{ route('notice.destroy', $notice->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmNoticeDelete({{ $notice->id }}, 'Edital {{ $notice->year }}')">
                                    <i class="bi bi-trash me-1"></i> Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhum edital cadastrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal de definição de local --}}
        <div class="modal fade" id="setNewFile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setFileLabel">Arquivo do Edital</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <form id="form-file" action="{{ route('notice.create') }}" method="POST"
                                    enctype="multipart/form-data" novalidate>
                                    @csrf

                                    {{-- Arquivo relacionado --}}
                                    <div class="form-floating mb-3">
                                        <input type="file" name="file"
                                            class="form-control @error('file') is-invalid @enderror" id="file"
                                            placeholder="Endereço" value="{{ old('file') }}" required>
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

@push('scripts')
    <script src="{{ asset('assets/swa/notice/delete.js') }}"></script>
    <script src="{{ asset('assets/swa/notice/publish.js') }}"></script>
@endpush
