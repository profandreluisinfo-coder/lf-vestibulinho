<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @if (app()->environment('local'))
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif
    <meta name="description" content="Provas Anteriores — Vestibulinho {{ $calendar->year ?? config('app.year') }}">
    <title>Provas Anteriores — Vestibulinho · EM Dr. Leandro Franceschini</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/guest/home/archives.css') }}" />
    
</head>

<body>

    <!-- ═══════════════════════ NAVBAR ══════════════════════════ -->
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div style="width:38px;height:38px;border-radius:10px;background:var(--grad-teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-mortarboard-fill text-white" style="font-size:1.1rem;"></i>
                </div>
                <div>
                    <span class="school text-white">EM Dr. Leandro Franceschini</span>
                    <span class="sub text-white">Vestibulinho {{ $calendar->year ?? config('app.year') }}</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#cursos">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#como-participar">Como Participar</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('guest.calendar.show') }}">Calendário</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('guest.faqs.index') }}">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}/#links-rapidos">Documentos</a></li>
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
                        Faça o download das provas e gabaritos de edições passadas e prepare-se com antecedência para o Vestibulinho.
                    </p>
                    <div class="hero-chips">
                        <div class="hero-chip">
                            <div class="num">{{ $archives->count() }}</div>
                            <div class="lbl">{{ Str::plural('Prova', $archives->count()) }} disponíve{{ $archives->count() === 1 ? 'l' : 'is' }}</div>
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

                        <a href="{{ route('register') }}" class="btn-inscricao">
                            <i class="bi bi-pencil-square"></i> Fazer Inscrição
                        </a>
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
                                <input
                                    type="text"
                                    id="paSearch"
                                    placeholder="Buscar por ano..."
                                    oninput="filterArchives(this.value)"
                                    autocomplete="off"
                                />
                            </div>
                            <div class="pa-count-badge">
                                <i class="bi bi-collection-fill"></i>
                                <strong id="visibleCount">{{ $archives->count() }}</strong>
                                {{ Str::plural('prova', $archives->count()) }} disponíve{{ $archives->count() === 1 ? 'l' : 'is' }}
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
                            <p>Tente buscar por outro ano ou <button onclick="clearSearch()" style="background:none;border:none;color:var(--teal);font-weight:700;cursor:pointer;padding:0;">limpe a busca</button>.</p>
                        </div>

                        <div class="pa-list" id="paList">

                            @foreach ($archives as $index => $archive)

                                @if ($index === 1)
                                    <div class="pa-divider" data-divider>Edições anteriores</div>
                                @endif

                                <div
                                    class="pa-item {{ $archive->id === $recenteId ? 'recent' : '' }} reveal delay-{{ min($index + 1, 4) }}"
                                    data-year="{{ $archive->year }}"
                                >
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
                                        <a class="pa-btn prova"
                                           href="{{ asset('storage/' . $archive->file) }}"
                                           target="_blank"
                                           rel="noopener">
                                            <i class="bi bi-download"></i> Prova
                                        </a>

                                        @if ($archive->answer?->file)
                                            <a class="pa-btn gabarito"
                                               href="{{ asset('storage/' . $archive->answer->file) }}"
                                               target="_blank"
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
    <section id="pa-cta">
        <div class="container text-center position-relative" style="z-index:1;">
            <div class="reveal">
                <div class="section-tag justify-content-center" style="color:var(--teal);">
                    <span style="background:var(--teal);"></span> Pronto para se inscrever?
                </div>
                <h2 style="font-family:var(--font-head);font-size:clamp(1.6rem,3.5vw,2.4rem);font-weight:800;color:#fff;margin-bottom:1rem;">
                    Garanta sua vaga no <span style="color:var(--amber);">curso técnico gratuito</span>
                </h2>
                <p style="color:rgba(255,255,255,.65);font-size:1rem;max-width:480px;margin:0 auto 2.5rem;line-height:1.7;">
                    Inscrições encerram em <strong style="color:var(--amber);">{{ $calendar?->inscription_end?->translatedFormat('d \d\e F Y') ?? '—' }}</strong>.
                    Comece agora — leva menos de 5 minutos.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <div class="pulse-wrap">
                        @if ($open)
                        <a href="{{ route('register') }}" class="btn-cta-main">
                            <i class="bi bi-pencil-square"></i> Fazer Inscrição Agora
                        </a>
                        @else
                        <span class="btn-cta-main unavailable">
                            <i class="bi bi-dash-circle"></i> Inscrições Encerradas
                        </span>
                        @endif
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
                        <li><a href="{{ route('home') }}/#calendario">Calendário</a></li>
                        <li><a href="{{ route('home') }}/#links-rapidos">Provas Anteriores</a></li>
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
    <script src="{{ asset('assets/js/guest/home/archives.js') }}"></script>
</body>
</html>