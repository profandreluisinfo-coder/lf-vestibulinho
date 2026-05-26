<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vestibulinho LF {{ $calendar?->year ?? config('app.year') }} — EM Dr. Leandro Franceschini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/guest/home/index.css') }}" />    
</head>

<body>

    @php
        $open = $calendar?->isInscriptionOpen() ? true : false;
    @endphp

    <!-- ═══════════════════════ NAVBAR ══════════════════════════ -->
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
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
                    <li class="nav-item"><a class="nav-link" href="#cursos">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#como-participar">Como Participar</a></li>
                    <li class="nav-item"><a class="nav-link" href="#calendario">Calendário</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#links-rapidos">Documentos</a></li>
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
    <section class="hero" id="home">
        <div class="hero-circle hero-circle-1"></div>
        <div class="hero-circle hero-circle-2"></div>
        <div class="hero-circle hero-circle-3"></div>

        <!-- ═══════════════════════ ALERTAS ══════════════════════════ -->
        @include('alerts.toasts')

        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-5">
                <!-- Left text -->
                <div class="col-lg-7">
                    <div class="hero-badge mb-3">
                        <span class="live-dot"></span>
                        @if ($open) Inscrições Abertas · @else Inscrições Encerradas · @endif 
                        100% Online · Gratuito
                    </div>
                    <h1 class="mb-3">
                        Sua carreira começa<br>aqui. No <em>Vestibulinho</em><br>{{ config('app.year') }}.
                    </h1>
                    <p class="hero-sub mb-4">
                        4 cursos técnicos gratuitos. Uma oportunidade real de transformar<br class="d-none d-md-block">
                        seu futuro. EM Dr. Leandro Franceschini — inscrição online e acessível.
                    </p>
                    <div class="hero-actions d-flex flex-wrap gap-3">
                        @if ($open) {{-- verifica se o período de inscrições ainda é válido --}}
                        <a href="{{ route('login') }}" class="btn-hero-primary">
                            <i class="bi bi-pencil-square"></i> Inscrever-se Agora
                        </a>
                        @else
                        <span class="btn-cta-main unavailable">
                            <i class="bi bi-dash-circle"></i> Inscrições Encerradas
                        </span>
                        @endif
                        <a href="#cursos" class="btn-hero-outline">
                            <i class="bi bi-grid-3x3-gap"></i> Ver Cursos
                        </a>
                    </div>
                </div>
                <!-- Right stats -->
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-chip delay-1">
                                <div class="num">4</div>
                                <div class="lbl">Cursos Técnicos</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-chip delay-2">
                                <div class="num">100%</div>
                                <div class="lbl">Gratuito</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-chip delay-3">
                                <div class="num" style="color:var(--amber);">Online</div>
                                <div class="lbl">Inscrição Digital</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-chip delay-4">
                                <div class="num">{{ $calendar->year ?? config('app.year') }}</div>
                                <div class="lbl">Processo Seletivo</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll hint -->
        <div class="scroll-hint">
            <div class="mouse">
                <div class="wheel"></div>
            </div>
            <span>rolar</span>
        </div>

    </section>

    <!-- ═══════════════════════ CURSOS ═══════════════════════════ -->
    <section id="cursos">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag justify-content-center">Oferta Acadêmica</div>
                <h2 class="section-title mb-3">Escolha seu <span>Curso Técnico</span></h2>
                <p class="section-lead mx-auto text-center">
                    Todos os cursos são gratuitos, presenciais e emitem certificado de técnico. Escolha sua área e
                    construa sua carreira.
                </p>
            </div>

            <div class="row g-4">
                <!-- Cursos -->
                @foreach ($courses as $course)
                    <div class="col-sm-6 col-lg-3 reveal delay-{{ $course->delay }}">
                        <div class="course-card {{ $course->card }}">
                            <div class="icon-wrap"><i class="bi bi-{{ $course->icone }}"></i></div>
                            <h3>{{ $course->name }}</h3>
                            <p>{{ $course->info }}</p>
                            <span class="tag-vagas"><i
                                    class="bi bi-people-fill me-1"></i>{{ $course->vacancies ? $course->vacancies : '' }}
                                Vagas disponíveis</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ COMO PARTICIPAR ══════════════════ -->
    <section id="como-participar">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 reveal">
                    <div class="section-tag">Passo a Passo</div>
                    <h2 class="section-title mb-3">Como <span>Participar</span><br>do Vestibulinho</h2>
                    <p class="section-lead">
                        O processo é simples, rápido e totalmente gratuito. Siga as etapas abaixo e garanta sua vaga.
                    </p>
                </div>
                <div class="col-lg-6 reveal delay-2 text-lg-end">
                    @if ($open) {{-- verifica se o período de inscrições ainda é válido --}}
                    <a href="{{ route('login') }}" class="btn-faq-more">
                        <i class="bi bi-pencil-fill"></i> Iniciar Inscrição
                    </a>
                    @endif
                </div>
            </div>

            <div class="timeline-wrap">
                <!-- Step 1 -->
                <div class="tl-item reveal-left">
                    <div class="tl-node">1</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-search me-2 text-teal"></i>Leia o Edital</h4>
                        <p>Acesse o edital completo na seção de <a href="#links-rapidos" class="text-decoration-none text-teal">documentos</a>. Leia todas as regras, requisitos de
                            inscrição, datas e critérios de avaliação.</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="tl-item reveal-right">
                    <div class="tl-node amber-node">2</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-person-fill me-2 text-amber"></i>Registre-se</h4>
                        Acesse o <a href="{{ route('register') }}"
                            class="text-decoration-none text-amber">formulário de registro</a>, informe seu e-mail e
                        crie uma senha de acesso. Você receberá um e-mail de confirmação. Clique no <i>link</i> recebido
                        no e-mail para validar seu cadastro. <strong class="text-danger">Sem essa confirmação não será
                            possível realizar a inscrição.</strong>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="tl-item reveal-left">
                    <div class="tl-node">3</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-clipboard-fill me-2 text-teal"></i>Faça sua Inscrição</h4>
                        <p>Após confirmar seu e-mail, acesse a <a href="{{ route('login') }}" class="text-decoration-none text-teal">Área do
                                Candidato</a>, preencha o formulário de inscrição com suas informações pessoais,
                            acadêmicas e demais dados solicitados. Confirme os dados e guarde o número de inscrição gerado.</p>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="tl-item reveal-right">
                    <div class="tl-node amber-node">4</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-book-fill me-2 text-amber"></i>Estude e Prepare-se</h4>
                        <p>Acesse as provas anteriores disponíveis aqui no site para se preparar.</p>
                    </div>
                </div>
                <!-- Step 5 -->
                <div class="tl-item reveal-left">
                    <div class="tl-node">5</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-pen-fill me-2 text-teal"></i>Realize a Prova</h4>
                        <p>Compareça no dia, horário e local indicados no cartão de confirmação. Leve documento com foto
                            original.</p>
                    </div>
                </div>
                <!-- Step 6 -->
                <div class="tl-item reveal-right">
                    <div class="tl-node amber-node">6</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-trophy-fill me-2 text-amber"></i>Acompanhe o Resultado</h4>
                        <p>Acesse a classificação e a convocação para matrícula aqui no site. Se convocado, compareça no
                            prazo indicado com os documentos exigidos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ CALENDÁRIO ═══════════════════════ -->
    <section id="calendario">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag justify-content-center">Datas Importantes</div>
                <h2 class="section-title mb-3">Calendário do <span>Processo Seletivo</span></h2>
                <p class="section-lead mx-auto text-center">Fique atento a todas as datas. Recomendamos salvar os
                    prazos com antecedência.</p>
            </div>

            <div class="row g-3 justify-content-center">
                <div class="col-lg-8">
                    <!-- Item 1 -->
                    <div class="cal-card mb-3 reveal delay-1">
                        <div class="cal-date">
                            <div class="day">{{ $calendar->inscription_start?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->inscription_start?->translatedFormat('M')) }}
                            </div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Início das Inscrições</h5>
                            <p>Abertura do portal de inscrições online — acesso pelo site oficial.</p>
                        </div>
                        <span class="cal-badge badge-open">Abertura</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="cal-card mb-3 reveal delay-2">
                        <div class="cal-date" style="background:var(--teal2);">
                            <div class="day">{{ $calendar->inscription_end?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->inscription_end?->translatedFormat('M')) }}
                            </div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Encerramento das Inscrições</h5>
                            <p>Último dia para realizar a inscrição. Não haverá prorrogação.</p>
                        </div>
                        <span class="cal-badge badge-close">Prazo</span>
                    </div>
                    <!-- Item 3 -->
                    <div class="cal-card mb-3 reveal delay-3">
                        <div class="cal-date" style="background:#7B3FA0;">
                            <div class="day">{{ $calendar->exam_location_publish?->format('d') }}</div>
                            <div class="mon">
                                {{ ucfirst($calendar->exam_location_publish?->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Divulgação dos Locais de Prova</h5>
                            <p>Local e horário de prova disponíveis na Área do Candidato.</p>
                        </div>
                        <span class="cal-badge badge-event">Evento</span>
                    </div>
                    <!-- Item 4 -->
                    <div class="cal-card mb-3 reveal delay-2">
                        <div class="cal-date" style="background:#C0392B;">
                            <div class="day">{{ $calendar->exam_date?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->exam_date?->translatedFormat('M')) }}</div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Dia da Prova</h5>
                            <p>Realização da prova escrita. Levar RG original. Portões fecham às 8h.</p>
                        </div>
                        <span class="cal-badge badge-event">Prova</span>
                    </div>
                    <!-- Item 5 -->
                    <div class="cal-card mb-3 reveal delay-3">
                        <div class="cal-date" style="background:var(--amber2);">
                            <div class="day">{{ $calendar->final_result_publish?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->final_result_publish?->translatedFormat('M')) }}
                            </div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Divulgação da Classificação</h5>
                            <p>Lista de classificados publicada no site e na Área do Candidato.</p>
                        </div>
                        <span class="cal-badge"
                            style="background:rgba(224,122,58,.15);color:var(--amber2);">Resultado</span>
                    </div>
                    <!-- Item 6 -->
                    <div class="cal-card reveal delay-4">
                        <div class="cal-date" style="background:var(--teal);">
                            <div class="day">{{ $calendar->enrollment_start?->format('d') }}</div>
                            <div class="mon">{{ ucfirst($calendar->enrollment_start?->translatedFormat('M')) }}
                            </div>
                        </div>
                        <div class="cal-info flex-grow-1">
                            <h5>Convocação e Matrícula</h5>
                            <p>Candidatos convocados devem realizar a matrícula presencialmente.</p>
                        </div>
                        <span class="cal-badge badge-open">Matrícula</span>
                    </div>
                    <div class="text-center mt-4 reveal delay-4">
                        <a href="{{ route('guest.calendar.show') }}" class="btn-faq-more">
                            Ver todas as datas do Vestibulinho <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ FAQ ══════════════════════════════ -->
    <section id="faq">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 reveal">
                    <div class="section-tag">Dúvidas Comuns</div>
                    <h2 class="section-title mb-3">Perguntas <span>Frequentes</span></h2>
                    <p class="section-lead">Selecionamos as dúvidas mais comuns dos candidatos. Não encontrou o que
                        procurava? Acesse a página completa de FAQ.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @foreach ($faqs as $faq)
                        <div class="faq-item reveal delay-1">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                {{ $faq->question }}
                                <div class="faq-icon"><i class="bi bi-plus-lg"></i></div>
                            </div>
                            <div class="faq-answer">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center mt-4 reveal">
                        <a href="{{ route('guest.faqs.index') }}" class="btn-faq-more">
                            Ver todas as perguntas frequentes <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ LINKS RÁPIDOS ════════════════════ -->
    <section id="links-rapidos">
        <div class="container position-relative" style="z-index:1;">
            <div class="text-center mb-5 reveal">
                <div class="section-tag justify-content-center" style="color:var(--amber);">
                    <span style="background:var(--amber);"></span>Documentos e Acesso
                </div>
                <h2 class="section-title mb-3" style="color:#fff;">Tudo que você <span
                        style="color:var(--teal);">precisa</span> em um lugar</h2>
                <p class="section-lead mx-auto text-center" style="color:rgba(255,255,255,.6);">Acesse documentos,
                    resultados e sua área pessoal de candidato diretamente por aqui.</p>
            </div>

            <div class="row g-4">
                <div class="col-6 col-md-4 col-lg-2 reveal delay-1">
                    <a href="{{ $settings->isNoticeEnabled() && $notice->file ? asset('storage/' . $notice->file) : '#' }}"
                        class="quick-card d-block" @if ($settings->isNoticeEnabled() && $notice->file) target="_blank" @endif>
                        <div class="qc-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                        <h5>Edital</h5>
                        <p>Regras e regulamento completo</p>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal delay-2">
                    <a href="{{ route('guest.archives.index') }}" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                        <h5>Provas Anteriores</h5>
                        <p>Treine com edições passadas</p>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal delay-3">
                    <a href="#" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-bar-chart-fill"></i></div>
                        <h5>Classificação</h5>
                        <p>Resultado e lista de aprovados</p>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal delay-2">
                    <a href="#" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-bell-fill"></i></div>
                        <h5>Convocação</h5>
                        <p>Chamada para matrícula</p>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal delay-3">
                    <a href="{{ route('login') }}" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-person-badge-fill"></i></div>
                        <h5>Área do Candidato</h5>
                        <p>Acompanhe sua inscrição</p>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal delay-4">
                    <a href="{{ route('register') }}" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-person-plus-fill"></i></div>
                        <h5>Registrar-se</h5>
                        <p>Cadastre seus dados de acesso agora</p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ CTA INSCRIÇÃO ════════════════════ -->
    <section id="candidato-cta">
        <div class="container text-center position-relative" style="z-index:1;">
            <div class="reveal">
                <div class="section-tag justify-content-center" style="color:var(--teal);">
                    <span style="background:var(--teal);"></span>Não Perca o Prazo
                </div>
                <h2 class="section-title mb-3">Garanta sua vaga no<br><span style="color:var(--amber);">curso técnico
                        gratuito</span></h2>
                <p class="section-lead mx-auto text-center mb-5">Inscrições encerram em <strong
                        style="color:var(--amber);">{{ $calendar?->inscription_end?->translatedFormat('d \d\e F Y') }}</strong>.
                    Comece agora mesmo — leva menos de 5 minutos.</p>
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

    <!-- ═══════════════════════ FOOTER ═══════════════════════════ -->
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
                        <li><a href="#cursos">Administração</a></li>
                        <li><a href="#cursos">Contabilidade</a></li>
                        <li><a href="#cursos">Informática</a></li>
                        <li><a href="#cursos">Seg. do Trabalho</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2 foot-col">
                    <h6>Candidato</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('register') }}">Inscrever-se</a></li>
                        <li><a href="{{ route('login') }}">Área do Candidato</a></li>
                        <li><a href="#">FAQ Completo</a></li>
                        <li><a href="#como-participar">Como Participar</a></li>
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

    <!-- ═══════════════════════ SCRIPTS ══════════════════════════ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/guest/home/index.js') }}"></script>
</body>

</html>
