@extends('layouts.site')

@section('content')
    <div class="container py-5">

        <h1 class="h4 mb-1">
            Classificação {{ $process?->year ? ' — Processo Seletivo ' . $process->year : '' }}
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
                Divulgado em {{ $process->result_released_at->format('d/m/Y \à\s H:i') }}.
                Classificação em lista única, por ordem decrescente de pontuação; em caso de
                empate, prevalece o candidato de menor idade. Os candidatos que não constam
                nesta lista estão eliminados do processo seletivo.
            </p>

            <div class="mb-3" style="max-width: 320px;">
                <label for="filter" class="form-label small mb-1">Buscar pelo número da inscrição</label>
                <input type="search" id="filter" class="form-control form-control-sm"
                       inputmode="numeric" placeholder="Ex.: 123" autocomplete="off">
            </div>

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
                        @forelse ($results as $result)
                            <tr>
                                <td class="text-center">{{ $result->ranking }}º</td>
                                <td class="text-center" data-inscription>{{ $result->inscription_id }}</td>
                                <td class="text-center">{{ $result->score }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Nenhum candidato classificado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <script>
                document.getElementById('filter').addEventListener('input', function () {
                    const term = this.value.trim();

                    document.querySelectorAll('#results-table tbody tr').forEach(function (row) {
                        const cell = row.querySelector('[data-inscription]');
                        row.hidden = term !== '' && cell !== null && !cell.textContent.trim().startsWith(term);
                    });
                });
            </script>

        @endif

    </div>
@endsection