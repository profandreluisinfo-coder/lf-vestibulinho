@extends('layouts.admin')

@section('page-title', 'Classificação')

@section('content')
    @php
        $released = $process->isResultReleased();
        $canRelease = (int) $stats->classified_count > 0;
        $confirmMessage = $released
            ? 'Retirar o resultado do ar? Os candidatos deixarão de vê-lo.'
            : 'Liberar o resultado para todos os candidatos?';
    @endphp

    <div class="container-fluid">

        {{-- Cabeçalho e ações --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h5 class="mb-1">
                    <i class="bi bi-list-ol me-2 text-primary"></i>
                    Classificação
                </h5>
                <p class="text-muted small mb-0">
                    Notas importadas e situação de cada candidato no processo seletivo.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                {{-- ROTA: exportação da planilha --}}
                <a href="{{ route('admin.export.excel') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Exportar planilha
                </a>

                {{-- ROTA: formulário de importação (create) --}}
                <a href="{{ route('admin.import.create') }}"
                    class="btn btn-outline-primary btn-sm {{ $released ? 'disabled' : '' }}"
                    @if ($released) aria-disabled="true" tabindex="-1" @endif>
                    <i class="bi bi-upload me-1"></i> Importar notas
                </a>

                {{-- Toggle de liberação do resultado --}}
                <form method="POST" action="{{ route('admin.process.release', 'result') }}"
                    onsubmit='return confirm(@json($confirmMessage))'>
                    @csrf
                    <button type="submit"
                        class="btn btn-sm {{ $released ? 'btn-outline-danger' : 'btn-success' }}"
                        @if (! $released && ! $canRelease) disabled title="Importe as notas antes de liberar." @endif>
                        <i class="bi {{ $released ? 'bi-eye-slash' : 'bi-megaphone' }} me-1"></i>
                        {{ $released ? 'Retirar resultado do ar' : 'Liberar resultado' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Situação da publicação --}}
        @if ($released)
            <div class="alert alert-success py-2 small" role="status">
                <i class="bi bi-check-circle-fill me-1"></i>
                Resultado liberado aos candidatos em {{ $process->result_released_at->format('d/m/Y \à\s H:i') }}.
                A importação de notas fica bloqueada enquanto ele estiver no ar.
            </div>
        @else
            <div class="alert alert-secondary py-2 small" role="status">
                <i class="bi bi-eye-slash me-1"></i>
                Resultado ainda não liberado: os candidatos não veem notas nem classificação.
            </div>
        @endif

        @if ((int) $stats->scored === 0)

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                    <h6 class="mt-3">Nenhuma nota importada</h6>
                    <p class="text-muted mb-3">
                        Exporte a planilha, preencha os pontos e importe o arquivo para gerar a classificação.
                    </p>
                    <a href="{{ route('admin.import.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-upload me-1"></i> Importar notas
                    </a>
                </div>
            </div>

        @else

            {{-- Resumo --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Candidatos com nota</div>
                            <div class="fs-4 fw-semibold">{{ $stats->scored }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Classificados</div>
                            <div class="fs-4 fw-semibold text-success">{{ $stats->classified_count }}</div>
                            <div class="text-muted small">
                                Limite do edital: {{ $limit }}
                                ({{ \App\Services\ExamRankingService::LIST_MULTIPLIER }} × {{ $vacancies }} vagas),
                                mais os empatados com o último
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Nota de corte</div>
                            @if ($stats->scored > $limit)
                                <div class="fs-4 fw-semibold">{{ $stats->cutoff }}</div>
                                <div class="text-muted small">Menor nota entre os classificados</div>
                            @else
                                <div class="fs-4 fw-semibold">—</div>
                                <div class="text-muted small">
                                    Limite do edital não atingido: todos os candidatos com nota estão classificados.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100 {{ $tied->isNotEmpty() ? 'border border-warning' : '' }}">
                        <div class="card-body">
                            <div class="text-muted small">Empates em nota e idade</div>
                            <div class="fs-4 fw-semibold {{ $tied->isNotEmpty() ? 'text-warning' : '' }}">
                                {{ $tied->sum('total') }}
                            </div>
                            <div class="text-muted small">
                                {{ $tied->isNotEmpty() ? 'Dividem a mesma posição; a comissão deve decidir.' : 'Nenhum empate a decidir.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filtro por situação e busca --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <ul class="nav nav-pills small">
                    @foreach (['all' => 'Todos', 'classified' => 'Classificados', 'eliminated' => 'Eliminados'] as $key => $label)
                        <li class="nav-item">
                            {{-- ROTA: esta mesma página (index) --}}
                            <a class="nav-link py-1 {{ $situation === $key ? 'active' : '' }}"
                                href="{{ route('admin.import.index', array_filter([
                                    'situation' => $key === 'all' ? null : $key,
                                    'q' => $search,
                                ])) }}">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- ROTA: esta mesma página (index) --}}
                <form method="GET" action="{{ route('admin.import.index') }}" class="d-flex gap-2" role="search">
                    @if ($situation !== 'all')
                        <input type="hidden" name="situation" value="{{ $situation }}">
                    @endif
                    <input type="search" name="q" value="{{ $search }}" class="form-control form-control-sm"
                        style="min-width: 260px;" placeholder="Nome ou número da inscrição"
                        aria-label="Buscar candidato">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i><span class="visually-hidden">Buscar</span>
                    </button>
                    @if ($search !== '')
                        <a href="{{ route('admin.import.index', array_filter(['situation' => $situation === 'all' ? null : $situation])) }}"
                            class="btn btn-outline-secondary btn-sm">Limpar</a>
                    @endif
                </form>
            </div>

            @if ($search !== '')
                <p class="text-muted small">
                    {{ $results->total() }} resultado(s) para “{{ $search }}”.
                </p>
            @endif

            {{-- Tabela --}}
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 90px;">Posição</th>
                                <th class="text-center" style="width: 110px;">Inscrição</th>
                                <th>Candidato</th>
                                <th class="text-center" style="width: 130px;">Nascimento</th>
                                <th class="text-center" style="width: 110px;">Pontuação</th>
                                <th class="text-center" style="width: 150px;">Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $result)
                                @php($isTied = $tiedKeys->has($result->score . '|' . $result->birth))
                                <tr class="{{ $isTied ? 'table-warning' : '' }}">
                                    <td class="text-center">{{ $result->ranking }}º</td>
                                    <td class="text-center text-muted">{{ $result->inscription_id }}</td>
                                    <td class="text-break">
                                        {{ $result->social_name ?? $result->name }}
                                        @if ($isTied)
                                            <i class="bi bi-exclamation-triangle text-warning ms-1"
                                                title="Empate em nota e idade"></i>
                                            <span class="visually-hidden">Empate em nota e idade</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($result->birth)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">{{ $result->score }}</td>
                                    <td class="text-center">
                                        @if ($result->classified)
                                            <span class="badge bg-success">Classificado</span>
                                        @else
                                            <span class="badge bg-secondary">Eliminado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        {{ $search !== '' ? 'Nenhum candidato encontrado para a busca.' : 'Nenhum candidato nesta situação.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $results->links() }}
            </div>

        @endif

    </div>
@endsection