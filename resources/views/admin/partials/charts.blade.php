@php
    /*
     * Resumo de cada gráfico, exibido no card.
     * Assim o card já informa algo útil antes de abrir o modal.
     */
    $lider = function ($colecao, $campo) {
        $top = $colecao->sortByDesc('total')->first();

        return $top ? [data_get($top, $campo), data_get($top, 'total')] : ['—', 0];
    };

    [$cursoNome, $cursoTotal] = $lider($cursos, 'curso');
    [$bairroNome, $bairroTotal] = $lider($bairros, 'burgh');
    [$escolaNome, $escolaTotal] = $lider($escolas, 'school');
    $totalInscritos = $sexos->sum('total');

    $graficos = [
        [
            'modal' => 'modalCursos',
            'titulo' => 'Inscritos por curso',
            'descricao' => 'Quantos candidatos escolheram cada curso.',
            'icone' => 'bi-bar-chart-fill',
            'cor' => 'teal',
            'rotulo' => 'Curso mais procurado',
            'destaque' => $cursoNome,
            'detalhe' => $cursoTotal . ' inscritos',
            'rota' => 'courses',
        ],
        [
            'modal' => 'modalSexos',
            'titulo' => 'Inscritos por sexo',
            'descricao' => 'Distribuição geral dos candidatos.',
            'icone' => 'bi-pie-chart-fill',
            'cor' => 'navy',
            'rotulo' => 'Total de inscritos',
            'destaque' => number_format($totalInscritos, 0, ',', '.'),
            'detalhe' => 'candidatos',
            'rota' => 'genders',
        ],
        [
            'modal' => 'modalSexoPorCurso',
            'titulo' => 'Inscritos por curso e sexo',
            'descricao' => 'Comparativo entre masculino e feminino em cada curso.',
            'icone' => 'bi-bar-chart-steps',
            'cor' => 'amber',
            'rotulo' => 'Cursos comparados',
            'destaque' => $sexoPorCurso->count(),
            'detalhe' => 'cursos',
            'rota' => 'gender-course',
        ],
        [
            'modal' => 'modalBairros',
            'titulo' => 'Bairros com mais candidatos',
            'descricao' => 'Os 10 bairros com maior número de inscritos.',
            'icone' => 'bi-geo-alt-fill',
            'cor' => 'success',
            'rotulo' => 'Bairro líder',
            'destaque' => $bairroNome,
            'detalhe' => $bairroTotal . ' candidatos',
            'rota' => 'burghs',
        ],
        [
            'modal' => 'modalEscolas',
            'titulo' => 'Escolas de origem',
            'descricao' => 'As 10 escolas que mais enviaram candidatos.',
            'icone' => 'bi-building',
            'cor' => 'muted',
            'rotulo' => 'Escola líder',
            'destaque' => $escolaNome,
            'detalhe' => $escolaTotal . ' candidatos',
            'rota' => 'schools',
        ],
    ];
@endphp

<section class="statistics-container charts-page">

    <header class="charts-head">
        <h4 class="charts-title">Gráficos e relatórios</h4>
        <p class="charts-subtitle">
            Escolha um gráfico para ver os detalhes. Para baixar os dados, use o painel de relatórios.
        </p>
    </header>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">

        {{-- Cards de gráficos: apenas visualização --}}
        @foreach ($graficos as $g)
            <div class="col">
                <button type="button" class="chart-card chart-card--{{ $g['cor'] }}" data-bs-toggle="modal"
                    data-bs-target="#{{ $g['modal'] }}" aria-label="Abrir gráfico: {{ $g['titulo'] }}">

                    <span class="chart-card__icon" aria-hidden="true">
                        <i class="bi {{ $g['icone'] }}"></i>
                    </span>

                    <span class="chart-card__title">{{ $g['titulo'] }}</span>
                    <span class="chart-card__desc">{{ $g['descricao'] }}</span>

                    <span class="chart-card__stat">
                        <small>{{ $g['rotulo'] }}</small>
                        <strong title="{{ $g['destaque'] }}">{{ $g['destaque'] }}</strong>
                        <em>{{ $g['detalhe'] }}</em>
                    </span>

                    <span class="chart-card__cta">
                        Ver gráfico <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                    </span>
                </button>
            </div>
        @endforeach

        {{-- Painel de relatórios: botões de exportação fora dos cards --}}
        <div class="col">
            <aside class="export-panel" aria-labelledby="exportTitle">
                <div class="export-panel__head">
                    <span class="export-panel__icon" aria-hidden="true"><i class="bi bi-download"></i></span>
                    <div>
                        <h5 id="exportTitle" class="export-panel__title">Baixar relatórios</h5>
                        <p class="export-panel__hint">Escolha o formato de cada relatório.</p>
                    </div>
                </div>

                <ul class="export-list">
                    @foreach ($graficos as $g)
                        <li class="export-row">
                            <span class="export-row__name">{{ $g['titulo'] }}</span>
                            <span class="export-row__actions">
                                <a href="{{ route('admin.reports.' . $g['rota'] . '.pdf') }}"
                                    class="export-btn export-btn--pdf"
                                    aria-label="Baixar {{ $g['titulo'] }} em PDF">
                                    <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> PDF
                                </a>
                                <a href="{{ route('admin.reports.' . $g['rota'] . '.excel') }}"
                                    class="export-btn export-btn--excel"
                                    aria-label="Baixar {{ $g['titulo'] }} em Excel">
                                    <i class="bi bi-file-earmark-excel" aria-hidden="true"></i> Excel
                                </a>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </div>

    {{-- ==================== MODAIS ==================== --}}
    {{-- IDs dos <canvas> e atributos data-* mantidos: o JS dos gráficos continua funcionando. --}}

    {{-- Modal: Cursos --}}
    <div class="modal fade" id="modalCursos" tabindex="-1" aria-labelledby="modalCursosLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCursosLabel">Inscritos por curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <canvas id="chartCursos" data-labels='@json($cursos->pluck('curso'))'
                        data-values='@json($cursos->pluck('total'))'></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Sexos --}}
    <div class="modal fade" id="modalSexos" tabindex="-1" aria-labelledby="modalSexosLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSexosLabel">Inscritos por sexo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <canvas id="chartSexos" data-labels='@json($sexos->pluck('gender'))'
                        data-values='@json($sexos->pluck('total'))'></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Sexo por Curso --}}
    <div class="modal fade" id="modalSexoPorCurso" tabindex="-1" aria-labelledby="modalSexoPorCursoLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSexoPorCursoLabel">Inscritos por curso e sexo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <canvas id="chartSexoPorCurso" data-cursos='@json($sexoPorCurso->pluck('course'))'
                        data-masculino='@json($sexoPorCurso->pluck('masculino'))'
                        data-feminino='@json($sexoPorCurso->pluck('feminino'))'></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Bairros --}}
    <div class="modal fade" id="modalBairros" tabindex="-1" aria-labelledby="modalBairrosLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBairrosLabel">10 bairros com mais candidatos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <canvas id="chartBairros" data-labels='@json($bairros->pluck('burgh'))'
                        data-values='@json($bairros->pluck('total'))'></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Escolas --}}
    <div class="modal fade" id="modalEscolas" tabindex="-1" aria-labelledby="modalEscolasLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEscolasLabel">10 escolas de origem com mais candidatos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <canvas id="chartEscolas" data-labels='@json($escolas->pluck('school'))'
                        data-values='@json($escolas->pluck('total'))'></canvas>
                </div>
            </div>
        </div>
    </div>
</section>