@extends('layouts.site')

@section('title', 'EM Dr. Leandro Franceschini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/home/index.css') }}" />
@endpush

@section('content')

    @php
        $event = $process?->latestEvent;
        $isInscriptionOpen = $process?->isInscriptionOpen() ?? false;
        $posts = $posts ?? collect();
        $faqs = $faqs ?? collect();
        $courses = $courses ?? collect();
        $hasCalendarDates = $event !== null && collect([
            $event->start,
            $event->end,
            $event->location_publish,
            $event->exam_date,
            $event->result_publish,
        ])->contains(fn ($date) => $date !== null);
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
                        Gratuito · Inscrição 100% Online · Prova Presencial
                    </div>
                    <h1 class="mb-3">
                        <em>Vestibulinho LF</em> {{ $process?->year }}<br>
                        Sua carreira começa<br>aqui.
                    </h1>
                    <p class="hero-sub mb-4">
                        4 cursos técnicos gratuitos integrados ao ensino médio. Uma oportunidade real de <br
                            class="d-none d-md-block">transformar
                        seu futuro. EM Dr Leandro Franceschini — inscrição online e acessível.
                    </p>
                    <div class="hero-actions d-flex flex-wrap gap-3">

                        @if ($isInscriptionOpen)
                            <a href="{{ route('login') }}" class="btn-hero-primary js-inscription-link">
                                <i class="bi bi-pencil-square"></i> Inscrever-se Agora
                            </a>
                        @else
                            <span class="btn-hero-primary" aria-disabled="true">
                                <i class="bi bi-info-circle"></i> Inscrições indisponíveis
                            </span>
                        @endif

                        <a href="#cursos" class="btn-hero-outline">
                            <i class="bi bi-grid-3x3-gap"></i> Ver Cursos
                        </a>
                    </div>
                </div>
                {{-- Right stats --}}
                <div class="col-lg-5">
                    <div class="row g-3">
                        @if ($process?->year)
                            <div class="col-6">
                                <div class="stat-chip delay-4">
                                    <div class="num">{{ $process->year }}</div>
                                    <div class="lbl">Processo Seletivo</div>
                                </div>
                            </div>
                        @endif

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

                        @if ($event?->exam_date)
                            <div class="col-6">
                                <div class="stat-chip delay-3">
                                    <div class="num" style="color:var(--amber);">Prova</div>
                                    <div class="lbl">
                                        {{ ucfirst($event->exam_date->translatedFormat('d/m')) }}
                                    </div>
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
    @if ($posts->isNotEmpty())
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
                    @foreach ($posts as $post)
                        @php
                            $hasImage = filled($post->image);
                        @endphp
                        <div class="reveal delay-{{ min($loop->index, 5) }}">
                            <div class="news-card">
                                <div class="news-card-image news-card-image-teal">
                                    @if ($hasImage)
                                        <img
                                            src="{{ Storage::url($post->image) }}"
                                            alt="{{ $post->title }}"
                                            onerror="this.remove(); const fallback = this.parentElement.querySelector('.news-card-fallback-icon'); if (fallback) { fallback.classList.remove('d-none'); }">
                                    @endif
                                    <i class="bi bi-newspaper news-card-fallback-icon @if ($hasImage) d-none @endif" aria-hidden="true"></i>
                                </div>

                                <div class="news-card-body">
                                    <span class="news-card-badge news-card-badge-teal">
                                        {{ $post->type === 'comunicado' ? 'Comunicado' : 'Notícia' }}
                                    </span>

                                    <h3 class="news-card-title">
                                        {{ $post->title }}
                                    </h3>

                                    <p class="news-card-desc">
                                        {{ $post->resume }}
                                    </p>

                                    <div class="news-card-meta">
                                        <span class="news-card-date">{{ $post->published_at?->diffForHumans() ?? '—' }}</span>
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
                    Todos os cursos são gratuitos, presenciais, integrados ao ensino médio e emitem certificado de técnico.
                    Escolha sua área e
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
                            @if ($course->vacancies > 0)
                                <span class="tag-vagas">
                                    <i class="bi bi-people-fill me-1"></i>{{ $course->vacancies }} Vagas disponíveis
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Instruções exibidas enquanto o período de inscrição está válido. --}}
    @if ($isInscriptionOpen)
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
                        <a href="{{ route('login') }}" class="btn-faq-more js-inscription-link">
                            <i class="bi bi-pencil-fill"></i> Iniciar Inscrição
                        </a>
                    </div>
                </div>

                <div class="timeline-wrap">
                    <div class="tl-item reveal-left">
                        <div class="tl-node">1</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-search me-2 text-teal"></i>Leia o Edital</h4>
                            <p>Acesse o edital completo na seção de <a href="#documentos"
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
                                    class="text-danger">Sem essa confirmação não será possível realizar sua
                                    inscrição.</strong></p>
                        </div>
                    </div>
                    <div class="tl-item reveal-left">
                        <div class="tl-node">3</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-clipboard-fill me-2 text-teal"></i>Faça sua Inscrição</h4>
                            <p>Após confirmar seu e-mail, acesse a <a href="{{ route('login') }}"
                                    class="text-decoration-none text-teal">Área do Candidato</a>, preencha o formulário de
                                inscrição com suas informações pessoais, acadêmicas e demais dados solicitados.</p>
                        </div>
                    </div>
                    <div class="tl-item reveal-right">
                        <div class="tl-node amber-node">4</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-book-fill me-2 text-amber"></i>Estude e Prepare-se</h4>
                            <p>Acesse as <a href="{{ route('site.archives.index') }}" class="text-amber">provas
                                    anteriores</a> disponíveis aqui no site para se preparar.</p>
                        </div>
                    </div>
                    <div class="tl-item reveal-left">
                        <div class="tl-node">5</div>
                        <div class="tl-content">
                            <h4><i class="bi bi-pen-fill me-2 text-teal"></i>Realize a Prova</h4>
                            <p>Compareça no dia, horário e local indicados no cartão de confirmação. Leve documento com foto
                                original e atual.</p>
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
    @endif

    {{-- O calendário continua disponível após o encerramento das inscrições. --}}
    @if ($hasCalendarDates)
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
                        @if ($event?->start)
                            <div class="cal-card mb-3 reveal delay-1">
                                <div class="cal-date">
                                    <div class="day">{{ $event->start->format('d') }}</div>
                                    <div class="mon">{{ ucfirst($event->start->translatedFormat('M')) }}</div>
                                </div>
                                <div class="cal-info flex-grow-1">
                                    <h5>Início das Inscrições</h5>
                                    <p>Abertura do portal de inscrições online — acesso pelo site oficial.</p>
                                </div>
                                <span class="cal-badge badge-open">Abertura</span>
                            </div>
                        @endif

                        @if ($event?->end)
                            <div class="cal-card mb-3 reveal delay-2">
                                <div class="cal-date" style="background:var(--teal2);">
                                    <div class="day">{{ $event->end->format('d') }}</div>
                                    <div class="mon">{{ ucfirst($event->end->translatedFormat('M')) }}</div>
                                </div>
                                <div class="cal-info flex-grow-1">
                                    <h5>Encerramento das Inscrições</h5>
                                    <p>Último dia para realizar a inscrição. Não haverá prorrogação.</p>
                                </div>
                                <span class="cal-badge badge-close">Prazo</span>
                            </div>
                        @endif

                        @if ($event?->location_publish)
                            <div class="cal-card mb-3 reveal delay-3">
                                <div class="cal-date" style="background:#7B3FA0;">
                                    <div class="day">{{ $event->location_publish->format('d') }}</div>
                                    <div class="mon">{{ ucfirst($event->location_publish->translatedFormat('M')) }}</div>
                                </div>
                                <div class="cal-info flex-grow-1">
                                    <h5>Divulgação dos Locais de Prova</h5>
                                    <p>Local e horário de prova disponíveis na Área do Candidato.</p>
                                </div>
                                <span class="cal-badge badge-event">Evento</span>
                            </div>
                        @endif

                        @if ($event?->exam_date)
                            <div class="cal-card mb-3 reveal delay-2">
                                <div class="cal-date" style="background:#C0392B;">
                                    <div class="day">{{ $event->exam_date->format('d') }}</div>
                                    <div class="mon">{{ ucfirst($event->exam_date->translatedFormat('M')) }}</div>
                                </div>
                                <div class="cal-info flex-grow-1">
                                    <h5>Dia da Prova</h5>
                                    <p>Realização da prova escrita. Levar RG original. Portões fecham às 8h.</p>
                                </div>
                                <span class="cal-badge badge-event">Prova</span>
                            </div>
                        @endif

                        @if ($event?->result_publish)
                            <div class="cal-card mb-3 reveal delay-3">
                                <div class="cal-date" style="background:var(--amber2);">
                                    <div class="day">{{ $event->result_publish->format('d') }}</div>
                                    <div class="mon">{{ ucfirst($event->result_publish->translatedFormat('M')) }}</div>
                                </div>
                                <div class="cal-info flex-grow-1">
                                    <h5>Divulgação da Classificação</h5>
                                    <p>Lista de classificados publicada no site e na Área do Candidato.</p>
                                </div>
                                <span class="cal-badge" style="background:rgba(224,122,58,.15);color:var(--amber2);">Resultado</span>
                            </div>
                        @endif

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
                    <div class="faq-item reveal delay-1">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            O que devo fazer antes de efetuar minha inscrição?
                            <div class="faq-icon"><i class="bi bi-plus-lg"></i></div>
                        </div>
                        <div class="faq-answer">
                            Acesse o edital completo na seção de <a href="#documentos"
                                class="text-decoration-none text-teal">documentos</a> e leia todas as regras, requisitos de
                            inscrição, datas e critérios de avaliação. Certifique-se de ter todos os documentos necessários
                            em mãos antes de iniciar sua inscrição. Acesse <a href="{{ route('register') }}"
                                class="text-decoration-none text-teal">o formulário de registro</a>, cadastre seu endereço
                            de e-mail e senha e siga as instruções para validar seus dados de acesso. Somente após a
                            validação desse procedimento será possível acessar a <a href="{{ route('login') }}"
                                class="text-decoration-none text-teal">Área do
                                Candidato</a> e realizar sua inscrição.
                        </div>
                    </div>

                    <div class="faq-item reveal delay-1">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            Não recebi o e-mail de confirmação do registro. O que fazer?
                            <div class="faq-icon"><i class="bi bi-plus-lg"></i></div>
                        </div>
                        <div class="faq-answer">
                            Acesse <a href="{{ route('resend.email') }}" class="text-decoration-none text-teal">o
                                formulário
                                de reenvio</a> de e-mail de confirmação, informe o endereço de e-mail cadastrado e clique em
                            <strong>"Reenviar E-mail de Verificação"</strong>. Verifique sua caixa de spam ou
                            lixo eletrônico.
                        </div>
                    </div>

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
                @if ($process?->edital)
                    <div class="col-6 col-md-4 col-lg-2 reveal delay-1">
                        <a href="{{ Storage::url($process->edital) }}" class="quick-card d-block" target="_blank" rel="noopener noreferrer">
                            <div class="qc-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                            <h5>Edital</h5>
                            <p>Regras e regulamento completo</p>
                        </a>
                    </div>
                @endif
                <div class="col-6 col-md-4 col-lg-2 reveal delay-4">
                    <a href="{{ route('register') }}" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-person-plus-fill"></i></div>
                        <h5>Registrar-se</h5>
                        <p>Cadastre seus dados de acesso agora</p>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal delay-3">
                    <a href="{{ route('login') }}" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-person-badge-fill"></i></div>
                        <h5>Área do Candidato</h5>
                        <p>Acompanhe sua inscrição</p>
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
                    <a href="{{ route('site.results.index') }}" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-bar-chart-fill"></i></div>
                        <h5>Classificação</h5>
                        <p>Resultado e lista de aprovados</p>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal delay-2">
                    <a href="{{ route('site.calls.index') }}" class="quick-card d-block">
                        <div class="qc-icon"><i class="bi bi-bell-fill"></i></div>
                        <h5>Convocação</h5>
                        <p>Chamada para matrícula</p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════ CTA INSCRIÇÃO ════════════════════ --}}
    @if ($isInscriptionOpen && $event?->end)
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
                            style="color:var(--amber);">{{ $event->end->translatedFormat('d \d\e F Y') }}</strong>.
                        Comece agora mesmo — leva menos de 5 minutos.
                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <div class="pulse-wrap">
                            <a href="{{ route('login') }}" class="btn-cta-main js-inscription-link">
                                <i class="bi bi-pencil-square"></i> Fazer Inscrição Agora
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

{{-- ── Constantes JS específicas desta página ───────────────────────────── --}}
@push('consts')
    <script>
        const loginUrl = @json(route('login'));
        const registerUrl = @json(route('register'));
    </script>
@endpush

{{-- ── JS específico desta página ───────────────────────────── --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/site/home/index.js') }}"></script>
    <script src="{{ asset('assets/js/site/home/confirm.js') }}"></script>
@endpush
