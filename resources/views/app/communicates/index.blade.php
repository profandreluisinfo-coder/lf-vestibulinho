@extends('layouts.admin')

@section('page-title', 'Comunicados')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endpush

@section('dash-content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-megaphone me-2"></i>Comunicados</h5>
        </div>

        <div class="table-responsive">

            <table id="communicates" class="table-striped table-hover table caption-top">
                <caption>{{ config('app.name') }} — Lista de Comunicados</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">Título</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Status</th>
                        <th scope="col">Publicado em</th>
                        <th scope="col">Autor</th>
                        <th scope="col" class="w-25">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">

                    @forelse ($comunicados as $comunicado)
                        <tr>
                            <td>{{ $comunicado->titulo }}</td>
                            <td class="text-center">{{ $comunicado->tipo }}</td>
                            <td class="text-center">
                                @if ($comunicado->estaPublicado())
                                    <span class="badge bg-success">Publicado</span>
                                @else
                                    <span class="badge bg-secondary">Rascunho</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $comunicado->published_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="text-center">{{ $comunicado->user->name }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    @php
                                        $communicateData = [
                                            'titulo' => $comunicado->titulo,
                                            'resumo' => $comunicado->resumo,
                                            'tipo' => $comunicado->tipo,
                                            'url' => $comunicado->url,
                                            'status' => $comunicado->status,
                                            'attachments' => $comunicado->attachments
                                                            ->map(fn($attachment) => [
                                                                'name' => $attachment->name,
                                                                'url'  => Storage::url($attachment->path),
                                                            ])
                                                            ->values(),
                                        ];
                                    @endphp

                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#viewCommunicate" data-communicate='@json($communicateData)'>
                                        <i class="bi bi-eye" title="Ver Detalhes"></i>
                                        Detalhes
                                    </button>

                                    {{-- Editar --}}
                                    <a href="{{ route('app.communicates.edit', $comunicado->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square" title="Editar"></i> Editar
                                    </a>

                                    {{-- Excluir --}}
                                    <form id="delete-communicate-form-{{ $comunicado->id }}"
                                        action="{{ route('app.communicates.destroy', $comunicado->id) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                        onclick="confirmCommunicateDelete({{ $comunicado->id }}, '{{ addslashes($comunicado->titulo) }}')">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">Nenhum comunicado cadastrado.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

            {{-- Paginação --}}
            <div class="mt-3">
                {{ $comunicados->links() }}
            </div>

        </div>

        {{-- ═══ Modal: Detalhes do Comunicado ═════════════════════ --}}
        <div class="modal fade" id="viewCommunicate" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-eye me-2"></i>Detalhes do Comunicado
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <p><strong>Título:</strong> <span id="view-titulo"></span></p>
                                <div class="mb-3">
                                    <strong>Resumo:</strong>
                                    <div id="view-resumo" class="mt-2"></div>
                                </div>
                                {{-- <p><strong>Tipo:</strong> <span id="view-tipo"></span></p> --}}
                                <p><strong>Link:</strong> <span id="view-url"></span></p>
                                {{-- <p><strong>Status:</strong> <span id="view-status"></span></p> --}}
                                <p><strong>Anexos:</strong></p>
                                <div id="view-attachments">
                                    —
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-pt-BR.min.js"></script>
    <script src="{{ asset('assets/js/rules/communicates/index.js') }}"></script>
@endpush
