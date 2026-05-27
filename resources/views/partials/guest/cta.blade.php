    <section id="candidato-cta">
        <div class="container text-center position-relative" style="z-index:1;">
            <div class="reveal">
                <div class="section-tag justify-content-center" style="color:var(--teal);">
                    <span style="background:var(--teal);"></span>Não Perca o Prazo
                </div>
                <h2 class="section-title mb-3">Garanta sua vaga no<br><span style="color:var(--amber);">curso técnico gratuito</span></h2>
                <p class="section-lead mx-auto text-center mb-5">
                    Inscrições encerram em <strong style="color:var(--amber);">{{ $calendar?->inscription_end?->translatedFormat('d \d\e F Y') }}</strong>. Comece agora mesmo — leva menos de 5 minutos.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <div class="pulse-wrap">
                        @if ($open)
                            <a href="{{ route('login') }}" class="btn-cta-main">
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