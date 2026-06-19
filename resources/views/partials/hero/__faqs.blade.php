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
                    Dúvidas Frequentes · Vestibulinho {{ $calendar?->year }}
                </div>
                <h1 class="faq-hero-title mb-3">
                    Perguntas Frequentes<br><em>Processo Seletivo</em>
                    {{-- <br>
                <span class="year-chip">{{ $calendar?->year }}</span> --}}
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
