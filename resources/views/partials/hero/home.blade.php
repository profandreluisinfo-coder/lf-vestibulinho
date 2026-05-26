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
                        @if ($open) Inscrições Abertas · @else Inscrições Encerradas · @endif
                        100% Online · Gratuito
                    </div>
                    <h1 class="mb-3">
                        Sua carreira começa<br>aqui. No <em>Vestibulinho</em><br>{{ $calendar?->year }}.
                    </h1>
                    <p class="hero-sub mb-4">
                        4 cursos técnicos gratuitos. Uma oportunidade real de transformar<br class="d-none d-md-block">
                        seu futuro. EM Dr. Leandro Franceschini — inscrição online e acessível.
                    </p>
                    <div class="hero-actions d-flex flex-wrap gap-3">
                        @if ($open)
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

        {{-- Scroll hint --}}
        <div class="scroll-hint">
            <div class="mouse">
                <div class="wheel"></div>
            </div>
            <span>rolar</span>
        </div>
    </section>