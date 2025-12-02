@extends('layouts.dash.admin')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Calendário do Processo Seletivo')

@section('dash-content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Edital</h5>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setNewFile">
                <i class="bi bi-plus-circle me-1"></i> Upload
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                                    Edital {{ $calendar->year }}.pdf
                                </a>
                            </td>
                            <td><span
                                    class="badge bg-{{ $notice->status == '1' ? 'success' : 'secondary' }}">{{ $notice->status == '1' ? 'Publicado' : 'Publicar' }}</span>
                            </td>
                            <td>
                                {{-- Botão de publicar (alterar status) --}}
                                <form id="publish-notice-form-{{ $notice->id }}"
                                    action="{{ route('notice.publish', $notice->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button type="button"
                                    class="btn btn-sm btn-{{ $notice->status ? 'secondary' : 'success' }}" title="{{ $notice->status ? 'Ocultar' : 'Publicar' }}"
                                    onclick="confirmNoticePublish({{ $notice->id }}, 'Edital {{ $notice->year }}')">
                                    <i class="bi bi-{{ $notice->status ? 'eye-slash' : 'eye' }} me-1"></i>
                                </button>

                                {{-- Botão de excluir --}}
                                <form id="delete-notice-form-{{ $notice->id }}"
                                    action="{{ route('notice.destroy', $notice->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                    onclick="confirmNoticeDelete({{ $notice->id }}, 'Edital {{ $notice->year }}')">
                                    <i class="bi bi-trash me-1"></i>
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
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="setFileLabel"><i class="bi bi-file-earmark-pdf me-2"></i>Arquivo do
                            Edital</h5>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="form-group">
                                    <form id="form-file" action="{{ route('notice.create') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        {{-- Arquivo relacionado --}}
                                        <div class="form-floating mb-3">
                                            <input type="file" name="file"
                                                class="form-control @error('file') is-invalid @enderror" id="file"
                                                placeholder="Endereço" value="{{ old('file') }}">
                                            <label for="file" class="form-label required">Arquivo relacionado</label>
                                            @error('file')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-check-circle me-1"></i> Gravar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/rules/admin/notice/index.js') }}"></script>
    <script src="{{ asset('assets/swa/notice/delete.js') }}"></script>
    <script src="{{ asset('assets/swa/notice/publish.js') }}"></script>
@endpush