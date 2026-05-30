@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF')

@section('dash-content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Edital</h5>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setNewNotice">
                <i class="bi bi-plus-circle me-1"></i> Cadastrar Edital
            </a>
        </div>

        @if ($notices->isNotEmpty())

            <div class="table-responsive">

                <table class="table align-middle">
                    <thead class="table-success">
                        <tr>
                            <th scope="col">Arquivo</th>
                            <th scope="col">Status</th>
                            <th scope="col">Opções</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($notices as $notice)

                            <tr>
                                <td>
                                    <a href="{{ asset('storage/' . $notice->file) }}" target="_blank">
                                        Edital {{ $calendar?->year }}.pdf
                                    </a>
                                </td>
                                <td><span
                                        class="badge bg-{{ $settings->isNoticeEnabled() == '1' ? 'success' : 'warning' }}">{{ $settings->isNoticeEnabled() == '1' ? 'Publicado' : 'Publicar' }}</span>
                                </td>
                                <td>

                                    {{-- Botão de publicar (alterar status) --}}
                                    <form id="publish-notice-form-{{ $settings->id }}"
                                        action="{{ route('app.system.publish.notice') }}" method="POST" class="d-none">
                                        @csrf
                                        @method('PUT')
                                    </form>

                                    {{-- Botão de publicação --}}
                                    <button type="button"
                                        class="btn btn-sm btn-{{ $settings->isNoticeEnabled() ? 'secondary' : 'success' }}"
                                        title="{{ $settings->isNoticeEnabled() ? 'Ocultar' : 'Publicar' }}"
                                        onclick="confirmNoticePublish({{ $settings->id }} )">
                                        <i class="bi bi-{{ $settings->isNoticeEnabled() ? 'eye-slash' : 'eye' }}"></i>
                                        {{ $settings->isNoticeEnabled() ? 'Ocultar' : 'Publicar' }}
                                    </button>

                                    {{-- Botão de excluir --}}
                                    <form id="delete-notice-form-{{ $notice->id }}"
                                        action="{{ route('app.notices.destroy', $notice->id) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                        onclick="confirmNoticeDelete({{ $notice->id }} )">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>

                                </td>
                            </tr>
                            
                        @endforeach

                    </tbody>
                </table>

            </div>

        @else

            <div id="meu-alert" class="alert alert-info d-flex align-items-start border-0 rounded-3 p-3" role="alert">
                <div class="me-3 fs-3" aria-hidden="true">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">Informação Importante</h5>
                    <p class="mb-0">
                        Voce ainda nao possui nenhum edital cadastrado.
                    </p>
                    <p class="mb-0 mt-2 small opacity-75">
                        Em caso de dúvidas, entre em contato com o suporte técnico.
                    </p>
                </div>
                <button type="button" class="btn-close ms-3" aria-label="Fechar alerta" data-bs-dismiss="alert"></button>
            </div>

        @endif

        {{-- Modal de definição de local --}}
        <div class="modal fade" id="setNewNotice" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="modalLabel" aria-hidden="true" data-bs-scroll="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title" id="setFileLabel"><i class="bi bi-file-earmark-pdf me-2"></i>Cadastrar
                            Edital</h5>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">

                                <form id="form-file" action="{{ route('app.notices.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    {{-- Arquivo relacionado --}}
                                    <div class="form-floating mb-3">
                                        <input type="file" name="path"
                                            class="form-control @error('path') is-invalid @enderror" id="path"
                                            placeholder="Endereço">
                                        <label for="path" class="form-label required">Arquivo relacionado</label>
                                        @error('path')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i>Salvar
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/rules/notice/index.js') }}"></script>
    <script src="{{ asset('assets/js/swa/notice/delete.js') }}"></script>
    <script src="{{ asset('assets/js/swa/notice/publish.js') }}"></script>
@endpush
