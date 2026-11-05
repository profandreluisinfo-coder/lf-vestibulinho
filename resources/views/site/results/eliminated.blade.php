@extends('layouts.site')

@section('content')
    <div class="container py-5">

        <h1 class="h4 mb-1">
            Candidatos não classificados{{ $process?->year ? ' — Processo Seletivo ' . $process->year : '' }}
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
                Relação dos candidatos que não alcançaram a classificação, identificados apenas pelo número da
                inscrição. Os candidatos não classificados estão eliminados do processo seletivo, conforme o edital.
            </p>

            {{-- ROTA: lista de classificados --}}
            <p class="small mb-3">
                <a href="{{ route('site.results.index') }}">
                    <i class="bi bi-arrow-left me-1"></i>Ver candidatos classificados
                </a>
            </p>

            @if ($numbers->isEmpty())

                <div class="alert alert-secondary" role="status">
                    <i class="bi bi-info-circle me-1"></i>
                    Nenhum candidato ficou fora da classificação: todos os candidatos com nota estão na lista de
                    classificados.
                </div>

            @else

                <div class="mb-3" style="max-width: 320px;">
                    <label for="filter" class="form-label small mb-1">Buscar pelo número da inscrição</label>
                    <input type="search" id="filter" class="form-control form-control-sm"
                           inputmode="numeric" placeholder="Ex.: 123" autocomplete="off">
                </div>

                <p class="text-muted small mb-2">{{ $numbers->count() }} inscrição(ões) não classificada(s).</p>

                <ul class="list-unstyled d-flex flex-wrap gap-2" id="numbers">
                    @foreach ($numbers as $number)
                        <li class="border rounded px-3 py-1 bg-light" data-inscription>{{ $number }}</li>
                    @endforeach
                </ul>

                <script>
                    document.getElementById('filter').addEventListener('input', function () {
                        const term = this.value.trim();

                        document.querySelectorAll('#numbers [data-inscription]').forEach(function (item) {
                            item.hidden = term !== '' && !item.textContent.trim().startsWith(term);
                        });
                    });
                </script>

            @endif

        @endif

    </div>
@endsection