{{-- ════════════════ SEÇÃO ════════════════ --}}
<section id="fique-atento">
    <div class="container">

        {{-- ── Cabeçalho ───────────────────────────────────────── --}}
        <div class="row mb-4">
            <div class="col-12 reveal">

                <div class="fa-header-accent">
                    <i class="bi bi-bell-fill"></i>
                    Fique Atento!
                </div>

                <h2 class="section-title">
                    Comunicados <span>Importantes</span>
                </h2>

                <p class="section-lead mt-2">
                    Acompanhe as últimas novidades e avisos sobre o Vestibulinho.
                </p>

            </div>
        </div>

        {{-- ── Lista de comunicados ─────────────────────────────── --}}
        <div class="row">
            <div class="col-12 reveal">

                @if ($comunicados->isEmpty())

                    {{-- Estado vazio --}}
                    <div class="fa-empty">
                        <i class="bi bi-inbox"></i>
                        Nenhum comunicado no momento.
                    </div>

                @else

                    <div class="comunicado-list">
                        @foreach ($comunicados->take(3) as $item)

                            @php
                                /* Mapeamento tipo → ícone Bootstrap Icons */
                                $iconeMap = [
                                    'info'         => 'bi-info-circle-fill',
                                    'alerta'       => 'bi-exclamation-triangle-fill',
                                    'urgente'      => 'bi-exclamation-octagon-fill',
                                    'aviso'        => 'bi-exclamation-triangle-fill',
                                    'importante'   => 'bi-bookmark-star-fill',
                                    'prazo'        => 'bi-clock-fill',
                                    'edital'       => 'bi-file-earmark-text-fill',
                                    'resultado'    => 'bi-trophy-fill',
                                    'aprovacao'    => 'bi-patch-check-fill',
                                    'inscricao'    => 'bi-pencil-square',
                                    'documento'    => 'bi-folder-fill',
                                    'calendario'   => 'bi-calendar-event-fill',
                                    'prova'        => 'bi-journal-check',
                                    'convocacao'   => 'bi-person-lines-fill',
                                    'cancelamento' => 'bi-x-octagon-fill',
                                    'manutencao'   => 'bi-tools',
                                    'sistema'      => 'bi-cpu-fill',
                                    'novidade'     => 'bi-stars',
                                    'sucesso'      => 'bi-check-circle-fill',
                                    'erro'         => 'bi-bug-fill',
                                    'financeiro'   => 'bi-cash-stack',
                                    'local'        => 'bi-geo-alt-fill',
                                ];

                                $icone = $iconeMap[$item->tipo] ?? 'bi-megaphone-fill';

                                /* Label do badge */
                                $labelMap = [
                                    'info'         => 'Informativo',
                                    'alerta'       => 'Atenção',
                                    'urgente'      => 'Urgente',
                                    'aviso'        => 'Aviso',
                                    'importante'   => 'Importante',
                                    'prazo'        => 'Prazo',
                                    'edital'       => 'Edital',
                                    'resultado'    => 'Resultado',
                                    'aprovacao'    => 'Aprovação',
                                    'inscricao'    => 'Inscrições',
                                    'documento'    => 'Documentação',
                                    'calendario'   => 'Calendário',
                                    'prova'        => 'Prova',
                                    'convocacao'   => 'Convocação',
                                    'cancelamento' => 'Cancelamento',
                                    'manutencao'   => 'Manutenção',
                                    'sistema'      => 'Sistema',
                                    'novidade'     => 'Novidade',
                                    'sucesso'      => 'Concluído',
                                    'erro'         => 'Erro',
                                    'financeiro'   => 'Financeiro',
                                    'local'        => 'Local de Prova',
                                ];
                                $label = $labelMap[$item->tipo] ?? 'Aviso';
                            @endphp

                            {{-- Cada item fica em uma `div` — a seta aponta para a página interna de exibição --}}
                            <div class="comunicado-item delay-{{ ($loop->index % 4) + 1 }}">

                                {{-- Ícone colorido --}}
                                <div class="comunicado-icon tipo-{{ $item->tipo ?? 'info' }}">
                                    <i class="bi {{ $icone }}"></i>
                                </div>

                                {{-- Corpo --}}
                                <div class="comunicado-body">
                                    <div class="comunicado-titulo">{{ $item->titulo }}</div>

                                    @if (!empty($item->resumo))
                                        <p class="comunicado-resumo">{!! $item->resumo !!}</p>
                                    @endif

                                    <div class="comunicado-meta">
                                        <span class="comunicado-data">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ \Carbon\Carbon::parse($item->data)->format('d/m/Y') }}
                                        </span>
                                        <span class="comunicado-badge badge-{{ $item->tipo ?? 'info' }}">
                                            {{ $label }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Seta: sempre aponta para a página interna de exibição do comunicado --}}
                                <a href="{{ route('guest.communicates.show', $item) }}" class="comunicado-arrow" title="Abrir comunicado">
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                                {{-- Se existir URL externa, mantenha um ícone discreto que abre em nova aba --}}
                                {{-- @if (!empty($item->url))
                                    <a href="{{ $item->url }}" class="comunicado-external ms-2" target="_blank" rel="noopener" title="Abrir link externo">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                @endif --}}

                            </div>

                        @endforeach
                    </div>

                    {{-- ── Botão "ver todos" ──────────────────────── --}}
                    <div class="text-center mt-4 reveal">
                        <a href="{{ route('guest.communicates.index') }}" class="btn-ver-todos">
                            Ver todos os comunicados
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                @endif

            </div>
        </div>

    </div>
</section>