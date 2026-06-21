{{-- ═══════════════════════ NAVBAR ══════════════════════════ --}}
<nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div
                style="width:38px;height:38px;border-radius:10px;background:var(--grad-teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-mortarboard-fill text-white" style="font-size:1.1rem;"></i>
            </div>
            <div>
                <span class="school text-white">EM Dr. Leandro Franceschini</span>
                <span class="sub text-white">Vestibulinho</span>
            </div>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}/#cursos"><i class="bi bi-mortarboard me-1"></i>Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}/#como-participar">Como Participar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}/#calendario">Calendário</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}/#faq"><i class="bi bi-question-circle me-1"></i> FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}/#links-rapidos">Documentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('site.archives.index') }}"><i class="bi bi-card-list me-1"></i> Provas Anteriores</a>
                </li>
                @if ($selection_process?->status)
                <li class="nav-item ms-lg-2">
                    <a class="nav-link btn-nav-cta" href="{{ route('login') }}">
                        <i class="bi bi-person-circle me-1"></i> Área do Candidato
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>