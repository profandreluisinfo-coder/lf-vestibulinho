    <section id="links-rapidos">
        <div class="container position-relative" style="z-index:1;">
            <div class="text-center mb-5 reveal">
                <div class="section-tag justify-content-center" style="color:var(--amber);">
                    <span style="background:var(--amber);"></span>Documentos e Acesso
                </div>
                <h2 class="section-title mb-3" style="color:#fff;">Tudo que você <span style="color:var(--teal);">precisa</span> em um lugar</h2>
                <p class="section-lead mx-auto text-center" style="color:rgba(255,255,255,.6);">Acesse documentos, resultados e sua área pessoal de candidato diretamente por aqui.</p>
            </div>

            <div class="row g-4">
                <div class="col-6 col-md-4 col-lg-2 reveal delay-1">
                    <a href="{{ $settings->isNoticeEnabled() && $notice->file ? asset('storage/' . $notice->file) : '#' }}"
                        class="quick-card d-block"
                        @if ($settings->isNoticeEnabled() && $notice->file) target="_blank" @endif>
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