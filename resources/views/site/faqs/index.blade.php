{{-- ═══════════════════════════════════════════════════════════════
     Herança do layout master
════════════════════════════════════════════════════════════════ --}}
@extends('layouts.site')

{{-- ── Título da página ──────────────────────────────────────── --}}
@section('title', 'Vestibulinho - Perguntas Frequentes')

{{-- ── CSS específico desta página ──────────────────────────── --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/faqs/index.css') }}" />
@endpush

{{-- ══════════════════════════════════════════════════════════════
     CONTEÚDO PRINCIPAL
══════════════════════════════════════════════════════════════ --}}
@section('content')
    <!-- ═══════════════════════ HERO ════════════════════════════ -->
    <section class="faq-hero">
        <div class="hero-circle hero-c1"></div>
        <div class="hero-circle hero-c2"></div>
        <div class="hero-circle hero-c3"></div>

        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb faq-breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                            <li class="breadcrumb-item active" aria-current="page">FAQs</li>
                        </ol>
                    </nav>
                    <div class="hero-badge mb-3">
                        <span class="live-dot"></span>
                        Dúvidas Frequentes · Vestibulinho {{ $selection_process->year }}
                    </div>
                    <h1 class="faq-hero-title mb-3">
                        Perguntas Frequentes<br><em>Processo Seletivo</em>
                        {{-- <br>
                <span class="year-chip">{{ $selection_process->year }}</span> --}}
                    </h1>
                    <h1>Encontre a resposta<br>para sua <em>dúvida</em><br>aqui.</h1>
                    <p class="lead mt-3">Reunimos as perguntas mais frequentes dos candidatos. Use a busca abaixo ou
                        navegue pelas categorias.</p>

                    <!-- CAMPO DE BUSCA -->
                    <div class="search-wrap">
                        <div class="search-box" id="searchBox">
                            <input type="text" id="faqSearch" placeholder="Buscar por palavra-chave..."
                                autocomplete="off" oninput="handleSearch(this)" />
                            <i class="bi bi-search search-icon"></i>
                            <button class="search-clear" onclick="clearSearch()" title="Limpar busca">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="search-count" id="searchCount"></div>

                        <!-- Filtros por categoria -->
                        <div class="cat-pills" id="catPills">
                            <button class="cat-pill active" data-cat="all" onclick="filterCat(this,'all')">
                                <i class="bi bi-grid-3x3-gap-fill me-1"></i> Todas
                            </button>
                            <button class="cat-pill" data-cat="geral" onclick="filterCat(this,'geral')">
                                <i class="bi bi-question-circle-fill me-1"></i> Geral
                            </button>
                            <button class="cat-pill" data-cat="inscricao" onclick="filterCat(this,'inscricao')">
                                <i class="bi bi-pencil-fill me-1"></i> Inscrição
                            </button>
                            <button class="cat-pill" data-cat="prova" onclick="filterCat(this,'prova')">
                                <i class="bi bi-file-earmark-text-fill me-1"></i> Prova
                            </button>
                            <button class="cat-pill" data-cat="cursos" onclick="filterCat(this,'cursos')">
                                <i class="bi bi-mortarboard-fill me-1"></i> Cursos
                            </button>
                            <button class="cat-pill" data-cat="resultado" onclick="filterCat(this,'resultado')">
                                <i class="bi bi-trophy-fill me-1"></i> Resultado
                            </button>
                            <button class="cat-pill" data-cat="matricula" onclick="filterCat(this,'matricula')">
                                <i class="bi bi-journal-check me-1"></i> Matrícula
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="col-lg-5">
                    <div class="hero-stats justify-content-lg-end">
                        <div class="hstat">
                            <div class="num" id="statTotal">—</div>
                            <div class="lbl">Perguntas</div>
                        </div>
                        <div class="hstat">
                            <div class="num" id="statCategories">—</div>
                            <div class="lbl">Categorias</div>
                        </div>
                        <div class="hstat">
                            <div class="num">100%</div>
                            <div class="lbl">Gratuito</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ TICKER ══════════════════════════ -->
    <div class="ticker-wrap">
        <div class="ticker-track" id="tickerTrack">
            <!-- preenchido pelo JS -->
        </div>
    </div>

    <!-- ═══════════════════════ CONTEÚDO PRINCIPAL ═══════════════ -->
    <div class="faq-main">
        <div class="container">
            <div class="row g-5">

                <!-- ── SIDEBAR ── -->
                <div class="col-lg-3">
                    <div class="faq-sidebar reveal">

                        <div class="sidebar-card">
                            <div class="sidebar-title">Categorias</div>
                            <nav class="cat-nav" id="sidebarCatNav">
                                <!-- preenchido pelo JS -->
                            </nav>
                        </div>

                        <div class="no-answer-card mt-3">
                            <div class="nac-icon"><i class="bi bi-chat-dots-fill"></i></div>
                            <h6>Não achou sua resposta?</h6>
                            <p>Fale diretamente com a nossa equipe por e-mail ou telefone.</p>
                            <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br" class="btn-contact">
                                <i class="bi bi-envelope-fill"></i> Entrar em Contato
                            </a>
                        </div>

                    </div>
                </div>

                <!-- ── LISTA DE FAQs ── -->
                <div class="col-lg-9">

                    <!-- Empty state -->
                    <div class="empty-state" id="emptyState">
                        <div class="empty-icon"><i class="bi bi-search"></i></div>
                        <h4>Nenhuma pergunta encontrada</h4>
                        <p>Tente palavras diferentes ou <button onclick="clearSearch()"
                                style="background:none;border:none;color:var(--teal);font-weight:700;cursor:pointer;padding:0;">limpe
                                a busca</button> para ver todas as perguntas.</p>
                    </div>

                    <!-- Seções de FAQ -->
                    <div id="faqSections">
                        <!-- preenchido pelo JS -->
                    </div>

                    <!-- Load More -->
                    <div class="load-more-wrap" id="loadMoreWrap" style="display:none;">
                        <button class="btn-load-more" id="btnLoadMore" onclick="loadMore()">
                            <span class="spinner-border spinner-border-sm"
                                style="width:.85rem;height:.85rem;border-width:2px;"></span>
                            <i class="bi bi-arrow-down-circle"></i>
                            Carregar mais perguntas
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if ($selection_process?->informations?->isInscriptionOpen())
        <!-- ═══════════════════════ CTA ══════════════════════════════ -->
        <section id="faq-cta">
            <div class="container text-center position-relative" style="z-index:1;">
                <div class="reveal">
                    <div class="section-tag justify-content-center" style="color:var(--teal);">
                        <span style="background:var(--teal);"></span>Pronto para começar?
                    </div>
                    <h2
                        style="font-family:var(--font-head);font-size:clamp(1.6rem,3.5vw,2.4rem);font-weight:800;color:#fff;margin-bottom:1rem;">
                        Sua vaga no <span style="color:var(--amber);">curso técnico gratuito</span> espera por você
                    </h2>
                    <p
                        style="color:rgba(255,255,255,.65);font-size:1rem;max-width:480px;margin:0 auto 2.5rem;line-height:1.7;">
                        Tire todas as suas dúvidas, leia o edital e garanta sua inscrição antes do prazo.
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
    <script>
        const FAQ_DATA = {!! $faqsJson !!};
    </script>
    <script src="{{ asset('assets/js/site/faqs/index.js') }}"></script>
@endpush
