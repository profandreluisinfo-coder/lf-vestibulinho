@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/exam/public/styles.css') }}">
@endpush

@php
    $recenteId = $archives?->first()?->id;
@endphp

<div class="modal fade" id="archivesModal" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="archivesModalLabel" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0" id="archivesModalLabel">
                        Provas Anteriores
                    </h5>

                    <small class="text-muted">
                        Faça o download das provas e gabaritos disponíveis
                    </small>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                    <div class="pa-count-badge">
                        <strong>{{ $archives->count() }}</strong>
                        {{ Str::plural('prova', $archives->count()) }}
                        disponíve{{ $archives->count() === 1 ? 'l' : 'is' }}
                    </div>

                </div>

                @if ($archives->isEmpty())

                    <div class="pa-empty text-center py-5">
                        <i class="bi bi-folder2-open fs-2 d-block mb-2 text-muted"></i>
                        Nenhuma prova encontrada.
                    </div>
                @else
                    <div class="pa-list">

                        @foreach ($archives as $index => $archive)
                            @if ($index === 1)
                                <div class="pa-divider">Edições anteriores</div>
                            @endif

                            <div class="pa-item {{ $archive->id === $recenteId ? 'recent' : '' }}">

                                <div>
                                    <div class="pa-year">{{ $archive->year }}</div>

                                    @if ($archive->id === $recenteId)
                                        <span class="pa-recent-tag">Recente</span>
                                    @endif
                                </div>

                                <div class="pa-info">
                                    <strong>Edição {{ $archive->year }}</strong>

                                    <span>
                                        @if ($archive->answer?->file)
                                            Prova e gabarito disponíveis
                                        @else
                                            Prova disponível — gabarito não associado
                                        @endif
                                    </span>
                                </div>

                                <div class="pa-btn-group">

                                    <a class="pa-btn" href="{{ asset('storage/' . $archive->file) }}" target="_blank">

                                        <i class="bi bi-download"></i>
                                        Prova
                                    </a>

                                    @if ($archive->answer?->file)
                                        <a class="pa-btn" href="{{ asset('storage/' . $archive->answer->file) }}"
                                            target="_blank">

                                            <i class="bi bi-download"></i>
                                            Gabarito
                                        </a>
                                    @else
                                        <span class="pa-btn unavailable">
                                            <i class="bi bi-dash-circle"></i>
                                            Sem gabarito
                                        </span>
                                    @endif

                                </div>

                            </div>
                        @endforeach

                    </div>

                    <p class="pa-footer-note mt-4">
                        (*) Provas sem gabarito associado exibem o aviso "Sem gabarito".
                    </p>

                @endif

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>

    </div>

</div>