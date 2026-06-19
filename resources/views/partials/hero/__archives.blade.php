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
