@extends('layouts.site')

@section('title', 'Provas Anteriores — Vestibulinho · EM Dr. Leandro Franceschini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/archives/index.css') }}" />
@endpush

@section('content')

    <!-- ═══════════════════════ HERO ════════════════════════════ -->
    <section class="pa-hero">
        <div class="hero-circle hc1"></div>
        <div class="hero-circle hc2"></div>
        <div class="hero-circle hc3"></div>

        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-5">
                <div class="col-lg-8">
                    <div class="hero-badge">
                        <span class="live-dot"></span>
                        Material de Estudo · Processo Seletivo
                    </div>
                    <h1>Provas<br><em>Anteriores</em></h1>
                    <p class="lead">
                        Faça o download das provas e gabaritos de edições passadas e prepare-se com antecedência para o
                        Vestibulinho.
                    </p>
                    <div class="hero-chips">
                        <div class="hero-chip">
                            <div class="num">{{ $archives->count() }}</div>
                            <div class="lbl">{{ Str::plural('Prova', $archives->count()) }}
                                disponíve{{ $archives->count() === 1 ? 'l' : 'is' }}</div>
                        </div>
                        <div class="hero-chip">
                            <div class="num">{{ $archives->whereNotNull('answer')->count() }}</div>
                            <div class="lbl">Com gabarito</div>
                        </div>
                        <div class="hero-chip">
                            <div class="num">100%</div>
                            <div class="lbl">Gratuito</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ CONTEÚDO PRINCIPAL ═══════════════ -->
    <div class="pa-main">
        <div class="container">
            <div class="row g-5 align-items-start">

                <!-- ── SIDEBAR ── -->
                <div class="col-lg-3 reveal">
                    <div class="info-card">
                        <div class="info-card-title">Dicas de estudo</div>

                        <div class="info-tip">
                            <div class="tip-icon" style="background:rgba(0,168,150,.1);color:var(--teal);">
                                <i class="bi bi-lightbulb-fill"></i>
                            </div>
                            <span>Resolva as provas simulando condições reais — sem consultas e com tempo controlado.</span>
                        </div>

                        <div class="info-tip">
                            <div class="tip-icon" style="background:rgba(244,162,97,.12);color:var(--amber2);">
                                <i class="bi bi-journal-check"></i>
                            </div>
                            <span>Confira o gabarito após resolver e anote os erros para revisão dirigida.</span>
                        </div>

                        <div class="info-tip">
                            <div class="tip-icon" style="background:rgba(27,62,114,.1);color:var(--navy2);">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <span>Priorize as edições mais recentes — o padrão da prova tende a se repetir.</span>
                        </div>

                        <div class="info-tip">
                            <div class="tip-icon" style="background:rgba(0,127,114,.1);color:var(--teal2);">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>
                            <span>Os arquivos estão em PDF. Você pode imprimir ou resolver diretamente no tablet.</span>
                        </div>
                        @if ($process?->isInscriptionOpen())
                        <a href="{{ route('register') }}" class="btn-inscricao">
                            <i class="bi bi-pencil-square"></i> Fazer Inscrição
                        </a>
                        @endif
                    </div>
                </div>

                <!-- ── LISTA DE PROVAS ── -->
                <div class="col-lg-9">

                    @php $recenteId = $archives->first()?->id; @endphp

                    <!-- Toolbar -->
                    <div class="pa-toolbar reveal">
                        <div class="pa-toolbar-left">
                            <h2>Provas Anteriores</h2>
                            <p>Faça o download das provas e gabaritos disponíveis</p>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="pa-search-wrap">
                                <i class="bi bi-search si"></i>
                                <input type="text" id="paSearch" placeholder="Buscar por ano..."
                                    oninput="filterArchives(this.value)" autocomplete="off" />
                            </div>
                            <div class="pa-count-badge">
                                <i class="bi bi-collection-fill"></i>
                                <strong id="visibleCount">{{ $archives->count() }}</strong>
                                {{ Str::plural('prova', $archives->count()) }}
                                disponíve{{ $archives->count() === 1 ? 'l' : 'is' }}
                            </div>
                        </div>
                    </div>

                    @if ($archives->isEmpty())
                        <!-- Empty State -->
                        <div class="pa-empty reveal">
                            <div class="pa-empty-icon"><i class="bi bi-folder2-open"></i></div>
                            <h4>Nenhuma prova disponível</h4>
                            <p>As provas de edições anteriores serão disponibilizadas em breve.</p>
                        </div>
                    @else
                        <!-- Empty state de busca -->
                        <div class="pa-empty reveal" id="searchEmpty" style="display:none;">
                            <div class="pa-empty-icon"><i class="bi bi-search"></i></div>
                            <h4>Nenhuma edição encontrada</h4>
                            <p>Tente buscar por outro ano ou <button onclick="clearSearch()"
                                    style="background:none;border:none;color:var(--teal);font-weight:700;cursor:pointer;padding:0;">limpe
                                    a busca</button>.</p>
                        </div>

                        <div class="pa-list" id="paList">

                            @foreach ($archives as $index => $archive)
                                @if ($index === 1)
                                    <div class="pa-divider" data-divider>Edições anteriores</div>
                                @endif

                                <div class="pa-item {{ $archive->id === $recenteId ? 'recent' : '' }} reveal delay-{{ min($index + 1, 4) }}"
                                    data-year="{{ $archive->year }}">
                                    <!-- Ano -->
                                    <div class="pa-year-wrap">
                                        <div class="pa-year">{{ $archive->year }}</div>
                                        @if ($archive->id === $recenteId)
                                            <div class="pa-recent-tag">
                                                <i class="bi bi-star-fill" style="font-size:.5rem;"></i> Recente
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="pa-info">
                                        <strong>Edição {{ $archive->year }}</strong>
                                        <span class="{{ $archive->answer?->file ? 'has-gabarito' : 'no-gabarito' }}">
                                            @if ($archive->answer?->file)
                                                <i class="bi bi-check-circle-fill" style="font-size:.75rem;"></i>
                                                Prova e gabarito disponíveis
                                            @else
                                                <i class="bi bi-dash-circle" style="font-size:.75rem;"></i>
                                                Prova disponível — gabarito não associado
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Botões -->
                                    <div class="pa-btn-group">
                                        <a class="pa-btn prova" href="{{ asset('storage/' . $archive->file) }}"
                                            target="_blank" rel="noopener">
                                            <i class="bi bi-download"></i> Prova
                                        </a>

                                        @if ($archive->answer?->file)
                                            <a class="pa-btn gabarito"
                                                href="{{ asset('storage/' . $archive->answer->file) }}" target="_blank"
                                                rel="noopener">
                                                <i class="bi bi-download"></i> Gabarito
                                            </a>
                                        @else
                                            <span class="pa-btn unavailable">
                                                <i class="bi bi-dash-circle"></i> Sem gabarito
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <p class="pa-footer-note">
                            <i class="bi bi-info-circle" style="flex-shrink:0;"></i>
                            Provas sem gabarito associado exibem o aviso "Sem gabarito".
                            Entre em contato caso identifique alguma inconsistência.
                        </p>

                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- ═══════════════════════ CTA ══════════════════════════════ -->
    @if ($process?->isInscriptionOpen())
        <section id="pa-cta">
            <div class="container text-center position-relative" style="z-index:1;">
                <div class="reveal">
                    <div class="section-tag justify-content-center" style="color:var(--teal);">
                        <span style="background:var(--teal);"></span> Pronto para se inscrever?
                    </div>
                    <h2
                        style="font-family:var(--font-head);font-size:clamp(1.6rem,3.5vw,2.4rem);font-weight:800;color:#fff;margin-bottom:1rem;">
                        Garanta sua vaga no <span style="color:var(--amber);">curso técnico gratuito</span>
                    </h2>
                    <p
                        style="color:rgba(255,255,255,.65);font-size:1rem;max-width:480px;margin:0 auto 2.5rem;line-height:1.7;">
                        Inscrições encerram em <strong
                            style="color:var(--amber);">{{ $process?->latestEvent?->end?->translatedFormat('d \d\e F Y') ?? '—' }}</strong>.
                        Comece agora — leva menos de 5 minutos.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <div class="pulse-wrap">
                            <a href="{{ route('register') }}" class="btn-cta-main">
                                <i class="bi bi-pencil-square"></i> Fazer Inscrição Agora
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/site/archives/index.js') }}"></script>
@endpush
