<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Perguntas Frequentes — Vestibulinho {{ config('app.year') }}">
    <title>FAQ — Vestibulinho {{ $calendar?->year ?? config('app.year') }} · EM Dr. Leandro Franceschini</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/guest/home/faqs.css') }}" />   
</head>

<body>

    <!-- ═══════════════════════ NAVBAR ══════════════════════════ -->
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div
                    style="width:38px;height:38px;border-radius:10px;background:var(--grad-teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-mortarboard-fill text-white" style="font-size:1.1rem;"></i>
                </div>
                <div>
                    <span class="school text-white">EM Dr. Leandro Franceschini</span>
                    <span class="sub text-white">Vestibulinho {{ config('app.year') }}</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#cursos">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#como-participar">Como
                            Participar</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#calendario">Calendário</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('guest.faqs.index') }}">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#links-rapidos">Documentos</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn-nav-cta" href="{{ route('login') }}">
                            <i class="bi bi-person-circle me-1"></i> Área do Candidato
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ═══════════════════════ HERO ════════════════════════════ -->
    <section class="faq-hero">
        <div class="hero-circle hero-c1"></div>
        <div class="hero-circle hero-c2"></div>
        <div class="hero-circle hero-c3"></div>

        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="faq-hero-badge">
                        <span class="live-dot"></span>
                        Dúvidas Frequentes · Vestibulinho {{ config('app.year') }}
                    </div>
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

    <!-- ═══════════════════════ FOOTER ══════════════════════════ -->
    <footer>
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="brand mb-2">EM Dr. Leandro Franceschini<small>Escola Municipal · Vestibulinho
                            {{ config('app.year') }}</small></div>
                    <p style="font-size:.82rem;line-height:1.7;" class="mb-3">
                        Oferecendo educação técnica de qualidade e oportunidades reais de crescimento profissional para
                        toda a comunidade.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="https://www.instagram.com/emdrleandrofranceschini/" class="d-flex align-items-center justify-content-center"
                            style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;"
                            onmouseover="this.style.background='rgba(0,168,150,.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,.07)'" target="_blank"><i
                                class="bi bi-instagram"></i></a>
                        <a href="https://www.facebook.com/emDrLeandroFranceschini/?locale=pt_BR" class="d-flex align-items-center justify-content-center"
                            style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;"
                            onmouseover="this.style.background='rgba(0,168,150,.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,.07)'" target="_blank"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://www.youtube.com/@emdrleandrofranceschini" class="d-flex align-items-center justify-content-center"
                            style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;"
                            onmouseover="this.style.background='rgba(0,168,150,.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,.07)'" target="_blank"><i
                                class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2 foot-col">
                    <h6>Processo Seletivo</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ asset('storage/' . $notice?->file) }}" target="_blank">Edital</a></li>
                        <li><a href="#">Calendário</a></li>
                        <li><a href="#">Provas Anteriores</a></li>
                        <li><a href="#">Classificação</a></li>
                        <li><a href="#">Convocação</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2 foot-col">
                    <h6>Cursos</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('home') }}/#cursos">Administração</a></li>
                        <li><a href="{{ route('home') }}/#cursos">Contabilidade</a></li>
                        <li><a href="{{ route('home') }}/#cursos">Informática</a></li>
                        <li><a href="{{ route('home') }}/#cursos">Seg. do Trabalho</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2 foot-col">
                    <h6>Candidato</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('register') }}">Inscrever-se</a></li>
                        <li><a href="{{ route('login') }}">Área do Candidato</a></li>
                        <li><a href="{{ route('guest.faqs.index') }}">FAQ Completo</a></li>
                        <li><a href="{{ route('home') }}/#como-participar">Como Participar</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2 foot-col">
                    <h6>Contato</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="mailto:emdrleandrofranceschini@educacaosumare.com.br" title="emdrleandrofranceschini@educacaosumare.com.br"><i class="bi bi-envelope me-1"></i>emdrleandrofranceschini@...</a></li>
                        <li><a href="#"><i class="bi bi-telephone me-1"></i>(19) 3873-2605</a></li>
                        <li><a href="#"><i class="bi bi-geo-alt me-1"></i>Ver endereço</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="mb-0">© {{ $currentYear }} EM Dr. Leandro Franceschini · Todos os direitos reservados.
                </p>
                {{-- <p class="mb-0"><a href="#">Política de Privacidade</a> · <a
                        href="#">Acessibilidade</a></p> --}}
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const FAQ_DATA = @json($faqs ?? []);
    </script>
    <script src="{{ asset('assets/js/guest/home/faqs.js') }}" data-faqs='@json($faqs ?? [])'></script>
</body>

</html>
