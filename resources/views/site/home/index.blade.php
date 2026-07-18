@extends('layouts.site')

@section('title', 'Vestibulinho ' . $process?->year . ' · EM Dr. Leandro Franceschini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/home/index.css') }}" />
@endpush

@section('content')

    @php
        $event = $process?->latestEvent;
    @endphp
    
    <section class="hero" id="home">
        <div class="hero-circle hero-circle-1"></div>
        <div class="hero-circle hero-circle-2"></div>
        <div class="hero-circle hero-circle-3"></div>

        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-5">
                {{-- Left text --}}
                <div class="col-lg-7">
                    <div class="hero-badge mb-3">
                        <span class="live-dot"></span>
                        100% Online · Gratuito
                    </div>
                    <h1 class="mb-3">
                        <em>Vestibulinho LF</em> {{ $process?->year }}<br>
                        Sua carreira começa<br>aqui.
                    </h1>
                    <p class="hero-sub mb-4">
                        4 cursos técnicos gratuitos. Uma oportunidade real de transformar<br class="d-none d-md-block">
                        seu futuro. EM Dr. Leandro Franceschini — inscrição online e acessível.
                    </p>
                    <div class="hero-actions d-flex flex-wrap gap-3">

                        @if ($process?->status && $process?->isInscriptionOpen())
                            <a href="{{ route('login') }}" class="btn-hero-primary">
                                <i class="bi bi-pencil-square"></i> Inscrever-se Agora
                            </a>
                        @endif

                        <a href="#cursos" class="btn-hero-outline">
                            <i class="bi bi-grid-3x3-gap"></i> Ver Cursos
                        </a>
                    </div>
                </div>
                {{-- Right stats --}}
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

                        @if ($process?->isInscriptionOpen())
                            <div class="col-6">
                                <div class="stat-chip delay-4">
                                    <div class="num">{{ $process?->year }}</div>
                                    <div class="lbl">Processo Seletivo</div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll hint --}}
        <div class="scroll-hint">
            <div class="mouse">
                <div class="wheel"></div>
            </div>
            <span>rolar</span>
        </div>
    </section>

    <!-- ===== SEÇÃO NOTÍCIAS ===== -->
    @if ($posts?->count() > 0)
    <section class="news-section" id="noticias">
        <div class="container-lg">
            <div class="section-header">
                <div class="section-tag">
                    Notícias e Comunicados
                </div>

                <h2 class="section-title">
                    Últimos
                </h2>

                <p class="section-lead">
                    Acompanhe os acontecimentos, eventos e informações importantes do Vestibulinho.
                </p>
            </div>

            <div class="news-grid">
                @php
                    $i = 0;
                @endphp
                @foreach ($posts as $post)
                    <div class="reveal delay-{{ $i++ }}">
                        <div class="news-card">
                            <div class="news-card-image news-card-image-teal">

                                @if ($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="Imagem da noticia">
                                @else
                                    📰
                                @endif
                            </div>

                            <div class="news-card-body">
                                <span class="news-card-badge news-card-badge-teal">
                                    Notícia
                                </span>

                                <h3 class="news-card-title">
                                    {{ $post->title }}
                                </h3>

                                <p class="news-card-desc">
                                    {{ $post->resume }}
                                </p>

                                <div class="news-card-meta">
                                    <span class="news-card-date">Há 2 dias</span>
                                    <a href="{{ route('site.posts.show', $post->slug) }}"
                                        class="news-card-link news-card-link-teal">
                                        Ler mais →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- CTA Notícias -->
            <div style="text-align: center; margin-top: 3rem;">
                <a href="{{ route('site.posts.index') }}" class="btn-hero-primary">
                    Ver Todos <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════ CURSOS ════════════════════════════ --}}
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
                @foreach ($courses as $course)
                    <div class="col-sm-6 col-lg-3 reveal delay-{{ $course->delay }}">
                        <div class="course-card {{ $course->card }}">
                            <div class="icon-wrap"><i class="bi bi-{{ $course->icone }}"></i></div>
                            <h3>{{ $course->name }}</h3>
                            <p>{{ $course->info }}</p>
                            @if ($course?->vacancies && $process?->status)
                                <span class="tag-vagas">
                                    <i class="bi bi-people-fill me-1"></i>{{ $course?->vacancies }} Vagas disponíveis
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Verifica se as inscrições estão abertas -->
    @if ($process?->status && $process?->isInscriptionOpen())
        {{-- ═══════════════════════ COMO PARTICIPAR ═════════════════════ --}}
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
                        <a href="{{ route('login') }}" class="btn-faq-more">
                            <i class="bi bi-pencil-fill"></i> Iniciar Inscrição
                        </a>
                    </div>
                </div>

                <div class="timeline-wrap">
                    <div class="tl-item reveal-left">
                        <div class="tl-node">1</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-search me-2 text-teal"></i>Leia o Edital</h4>
                            <p>Acesse o edital completo na seção de <a href="#links-rapidos"
                                    class="text-decoration-none text-teal">documentos</a>. Leia todas as regras, requisitos
                                de inscrição, datas e critérios de avaliação.</p>
                        </div>
                    </div>
                    <div class="tl-item reveal-right">
                        <div class="tl-node amber-node">2</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-person-fill me-2 text-amber"></i>Registre-se</h4>
                            <p>Acesse o <a href="{{ route('register') }}" class="text-amber">formulário de
                                    registro</a>,
                                informe seu e-mail e crie uma senha de acesso. Você receberá um e-mail de confirmação.
                                Clique no <i>link</i> recebido no e-mail para validar seu cadastro. <strong
                                    class="text-danger">Sem essa confirmação não será possível realizar a
                                    inscrição.</strong></p>
                        </div>
                    </div>
                    <div class="tl-item reveal-left">
                        <div class="tl-node">3</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-clipboard-fill me-2 text-teal"></i>Faça sua Inscrição</h4>
                            <p>Após confirmar seu e-mail, acesse a <a href="{{ route('login') }}"
                                    class="text-decoration-none text-teal">Área do Candidato</a>, preencha o formulário de
                                inscrição com suas informações pessoais, acadêmicas e demais dados solicitados. Confirme os
                                dados e guarde o número de inscrição gerado.</p>
                        </div>
                    </div>
                    <div class="tl-item reveal-right">
                        <div class="tl-node amber-node">4</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-book-fill me-2 text-amber"></i>Estude e Prepare-se</h4>
                            <p>Acesse as provas anteriores disponíveis aqui no site para se preparar.</p>
                        </div>
                    </div>
                    <div class="tl-item reveal-left">
                        <div class="tl-node">5</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-pen-fill me-2 text-teal"></i>Realize a Prova</h4>
                            <p>Compareça no dia, horário e local indicados no cartão de confirmação. Leve documento com foto
                                original.</p>
                        </div>
                    </div>
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
        {{-- ═══════════════════════ CALENDÁRIO ═══════════════════════ --}}
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
                        <div class="cal-card mb-3 reveal delay-1">
                            <div class="cal-date">
                                <div class="day">{{ $event?->start?->format('d') }}</div>
                                <div class="mon">{{ ucfirst($event?->start?->translatedFormat('M')) }}
                                </div>
                            </div>
                            <div class="cal-info flex-grow-1">
                                <h5>Início das Inscrições</h5>
                                <p>Abertura do portal de inscrições online — acesso pelo site oficial.</p>
                            </div>
                            <span class="cal-badge badge-open">Abertura</span>
                        </div>
                        <div class="cal-card mb-3 reveal delay-2">
                            <div class="cal-date" style="background:var(--teal2);">
                                <div class="day">{{ $event?->end?->format('d') }}</div>
                                <div class="mon">{{ ucfirst($event?->end?->translatedFormat('M')) }}
                                </div>
                            </div>
                            <div class="cal-info flex-grow-1">
                                <h5>Encerramento das Inscrições</h5>
                                <p>Último dia para realizar a inscrição. Não haverá prorrogação.</p>
                            </div>
                            <span class="cal-badge badge-close">Prazo</span>
                        </div>
                        <div class="cal-card mb-3 reveal delay-3">
                            <div class="cal-date" style="background:#7B3FA0;">
                                <div class="day">{{ $event?->location_publish?->format('d') }}</div>
                                <div class="mon">
                                    {{ ucfirst($event?->location_publish?->translatedFormat('M')) }}</div>
                            </div>
                            <div class="cal-info flex-grow-1">
                                <h5>Divulgação dos Locais de Prova</h5>
                                <p>Local e horário de prova disponíveis na Área do Candidato.</p>
                            </div>
                            <span class="cal-badge badge-event">Evento</span>
                        </div>
                        <div class="cal-card mb-3 reveal delay-2">
                            <div class="cal-date" style="background:#C0392B;">
                                <div class="day">{{ $event?->exam_date?->format('d') }}</div>
                                <div class="mon">{{ ucfirst($event?->exam_date?->translatedFormat('M')) }}</div>
                            </div>
                            <div class="cal-info flex-grow-1">
                                <h5>Dia da Prova</h5>
                                <p>Realização da prova escrita. Levar RG original. Portões fecham às 8h.</p>
                            </div>
                            <span class="cal-badge badge-event">Prova</span>
                        </div>
                        <div class="cal-card mb-3 reveal delay-3">
                            <div class="cal-date" style="background:var(--amber2);">
                                <div class="day">{{ $event?->result_publish?->format('d') }}</div>
                                <div class="mon">{{ ucfirst($event?->result_publish?->translatedFormat('M')) }}
                                </div>
                            </div>
                            <div class="cal-info flex-grow-1">
                                <h5>Divulgação da Classificação</h5>
                                <p>Lista de classificados publicada no site e na Área do Candidato.</p>
                            </div>
                            <span class="cal-badge"
                                style="background:rgba(224,122,58,.15);color:var(--amber2);">Resultado</span>
                        </div>
                        <div class="cal-card reveal delay-4">
                            <div class="cal-date" style="background:var(--teal);">
                                <div class="day">{{ $event?->enrol_start?->format('d') }}</div>
                                <div class="mon">{{ ucfirst($event?->enrol_start?->translatedFormat('M')) }}
                                </div>
                            </div>
                            <div class="cal-info flex-grow-1">
                                <h5>Convocação e Matrícula</h5>
                                <p>Candidatos convocados devem realizar a matrícula presencialmente.</p>
                            </div>
                            <span class="cal-badge badge-open">Matrícula</span>
                        </div>
                        <div class="text-center mt-4 reveal delay-4">
                            <a href="{{ route('site.process.show') }}" class="btn-faq-more">
                                Ver todas as datas do Vestibulinho <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════ FAQ ════════════════════════════════ --}}
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
                        <a href="{{ route('site.faqs.index') }}" class="btn-faq-more">
                            Ver todas as perguntas frequentes <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Verifica se as inscrições estão abertas -->
    @if ($process?->status && $process?->isInscriptionOpen())
        {{-- ═══════════════════════ DOCUMENTOS ════════════════════ --}}
        <section id="documentos">
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
                        <a href="{{ $process?->status && $process?->edital ? Storage::url($process?->edital) : '#' }}"
                            class="quick-card d-block" @if ($process?->status && $process?->edital) target="_blank" @endif>
                            <div class="qc-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                            <h5>Edital</h5>
                            <p>Regras e regulamento completo</p>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 reveal delay-2">
                        <a href="{{ route('site.archives.index') }}" class="quick-card d-block">
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
        {{-- ═══════════════════════ CTA INSCRIÇÃO ════════════════════ --}}
        <section id="candidato-cta">
            <div class="container text-center position-relative" style="z-index:1;">
                <div class="reveal">
                    <div class="section-tag justify-content-center" style="color:var(--teal);">
                        <span style="background:var(--teal);"></span>Não Perca o Prazo
                    </div>
                    <h2 class="section-title mb-3">Garanta sua vaga no<br><span style="color:var(--amber);">curso
                            técnico
                            gratuito</span></h2>

                    <p class="section-lead mx-auto text-center mb-5">
                        Inscrições encerram em <strong
                            style="color:var(--amber);">{{ $event?->end?->translatedFormat('d \d\e F Y') }}</strong>.
                        Comece agora mesmo — leva menos de 5 minutos.
                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <div class="pulse-wrap">
                            <a href="{{ route('login') }}" class="btn-cta-main">
                                <i class="bi bi-pencil-square"></i> Fazer Inscrição Agora
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

{{-- ── JS específico desta página ───────────────────────────── --}}
@push('scripts')
    <script src="{{ asset('assets/js/site/home/index.js') }}"></script>
@endpush
