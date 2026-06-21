{{-- ═══════════════════════ FOOTER ═══════════════════════════ --}}
<footer>
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="brand mb-2">EM Dr. Leandro Franceschini<small>Escola Municipal · Vestibulinho
                        {{ $selection_process->year }}</small></div>
                <p style="font-size:.82rem;line-height:1.7;" class="mb-3">
                    Oferecendo educação técnica de qualidade e oportunidades reais de crescimento profissional para
                    toda a comunidade.
                </p>
                <div class="d-flex gap-2">
                    <a href="https://www.instagram.com/emdrleandrofranceschini/"
                        class="d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;"
                        onmouseover="this.style.background='rgba(0,168,150,.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,.07)'" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.facebook.com/emDrLeandroFranceschini/?locale=pt_BR"
                        class="d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;"
                        onmouseover="this.style.background='rgba(0,168,150,.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,.07)'" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.youtube.com/@emdrleandrofranceschini"
                        class="d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;"
                        onmouseover="this.style.background='rgba(0,168,150,.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,.07)'" target="_blank">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
            <div class="col-6 col-lg-2 foot-col">
                <h6>Processo Seletivo</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">

                    @if ($selection_process->edital)
                        <li>
                            <a href="{{ asset('storage/' . $selection_process->edital) }}" target="_blank">
                                Edital
                            </a>
                        </li>
                    @endif
                    @if ($selection_process->is_active)
                        <li><a href="{{ route('site.calendar.show') }}">Calendário</a></li>
                    @endif
                    <li><a href="{{ route('site.archives.index') }}">Provas Anteriores</a></li>
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
                    @if ($selection_process?->isInscriptionOpen())
                        <li><a href="{{ route('register') }}">Registrar-se</a></li>
                    @endif
                    <li><a href="{{ route('login') }}">Área do Candidato</a></li>
                    <li><a href="{{ route('site.faqs.index') }}">FAQ Completo</a></li>
                    @if ($selection_process?->isInscriptionOpen())
                        <li><a href="{{ route('home') }}/#como-participar">Como Participar</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-6 col-lg-2 foot-col">
                <h6>Contato</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li>
                        <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br"
                            title="emdrleandrofranceschini@educacaosumare.com.br">
                            <i class="bi bi-envelope me-1"></i>emdrleandrofranceschini@...
                        </a>
                    </li>
                    <li><a href="#"><i class="bi bi-telephone me-1"></i>(19) 3873-2605</a></li>
                    <li><a href="#"><i class="bi bi-geo-alt me-1"></i>Ver endereço</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0">© {{ $year }} EM Dr. Leandro Franceschini · Todos os direitos reservados.</p>
        </div>
    </div>
</footer>
