@extends('layouts.site')

@section('content')
    <div class="container py-5">

        <h1 class="h4 mb-1">
            Resultado Preliminar{{ $process?->year ? ' — Processo Seletivo ' . $process->year : '' }}
        </h1>

        @if (! $released)

            <div class="alert alert-info mt-4" role="status">
                <i class="bi bi-hourglass-split me-1"></i>
                O resultado ainda não foi divulgado.
                @if ($process?->result_publish)
                    Divulgação prevista para {{ $process->formatDate($process->result_publish) }}.
                @endif
            </div>

        @else

            <p class="text-muted small mb-3">
                Classificação em lista única, por ordem decrescente de pontuação; em caso de
                empate, prevalece o candidato de menor idade. Os candidatos que não constam
                nesta lista estão eliminados do processo seletivo.
            </p>

            {{-- ROTA: lista de não classificados --}}
            <p class="small mb-3">
                <a href="{{ route('site.results.eliminated') }}">
                    Ver candidatos não classificados <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </p>

            <div class="mb-3" style="max-width: 380px;">
                <label for="filter" class="form-label small mb-1">Buscar pelo número da inscrição</label>
                <input type="search" id="filter" class="form-control"
                       inputmode="numeric" placeholder="Digite o início do número" autocomplete="off" aria-describedby="results-count">
            </div>
            <p class="small text-muted mb-3">
                Role a página para carregar mais candidatos. Se preferir, use o botão ao final da lista.
            </p>

            <p id="results-count" class="small text-muted mb-2" aria-live="polite">Exibindo {{ $results->count() }} candidatos</p>

            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle" id="results-table">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 120px;">Posição</th>
                            <th class="text-center">Inscrição</th>
                            <th class="text-center" style="width: 140px;">Pontuação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                            <tr>
                                <td class="text-center">{{ $result->ranking }}º</td>
                                <td class="text-center" data-inscription>{{ $result->inscription_id }}</td>
                                <td class="text-center">{{ $result->score }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="results-loading" class="text-center text-muted py-3" role="status" hidden>
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Carregando candidatos…
            </div>
            <p id="results-end" class="text-center small text-muted py-3 mb-0" hidden>Todos os resultados foram carregados.</p>
            <p id="results-empty" class="text-center text-muted py-4" hidden>Nenhum candidato corresponde a essa inscrição.</p>
            <p id="results-error" class="text-center text-danger small py-3" role="alert" hidden></p>
            <div class="text-center mb-3">
                <button type="button" id="results-more" class="btn btn-outline-primary btn-sm" hidden>Carregar mais candidatos</button>
            </div>
            <div id="results-sentinel" aria-hidden="true"></div>

            <script>
                window.resultsMoreUrl = @json(route('site.results.more'));
            </script>
            @push('scripts')
                <script src="{{ asset('assets/js/site/results/index.js') }}?v={{ filemtime(public_path('assets/js/site/results/index.js')) }}"></script>
            @endpush

        @endif

    </div>
@endsection
