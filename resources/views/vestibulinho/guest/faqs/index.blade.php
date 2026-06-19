{{-- ═══════════════════════════════════════════════════════════════
     Herança do layout master
════════════════════════════════════════════════════════════════ --}}
@extends('layouts.guest')

{{-- ── Título da página ──────────────────────────────────────── --}}
{{-- @section('title', 'FAQ — Vestibulinho ' . $calendar?->year) --}}

{{-- ── CSS específico desta página ──────────────────────────── --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guest/faqs.css') }}" />
@endpush

{{-- ══════════════════════════════════════════════════════════════
     CONTEÚDO PRINCIPAL
══════════════════════════════════════════════════════════════ --}}
@section('content')

    <!-- ═══════════════════════ HERO ════════════════════════════ -->
    @include('partials.hero.faqs')

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
                        <a href="{{ route('guest.register') }}" class="btn-cta-main">
                            <i class="bi bi-pencil-square"></i> Fazer Inscrição Agora
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // JSON com HTML não escapado para renderização correta do Summernote
        // const FAQ_DATA = {!! $faqsJson ?? '[]' !!};
        const FAQ_DATA = {!! $faqsJson !!};
        console.log(FAQ_DATA);
    </script>
    <script src="{{ asset('assets/js/vestibulinho/faqs/index.js') }}"></script>
@endpush
