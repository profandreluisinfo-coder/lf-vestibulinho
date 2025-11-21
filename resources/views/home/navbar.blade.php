<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-white shadow-sm m-0 py-1">
    <div class="container">
        <a class="navbar-brand text-primary" href="{{ route('home') }}" title="Página Inicial">
            <i class="bi bi-mortarboard me-2"></i>Vestibulinho LF {{ $calendar?->year ?? '' }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li>
                    <a class="nav-link" href="{{ route('home') }}" title="Página principal do Vestibulinho"><i
                            class="bi bi-house-door"></i> Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}" title="Acesso para candidatos">Área do Candidato</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ Route::is('home') ? '#cursos' : route('home') .'/#cursos' }}" title="Conheça os cursos disponíveis">Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ Route::is('home') ? '#calendary' : route('calendary') .'/#calendary' }}" title="Confira o calendário do Vestibulinho">Calendário</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ Route::is('home') ? '#faq' : route('questions') }}"
                        title="Perguntas frequentes">
                        Dúvidas?
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://leandrofranceschini.com.br/#form-contato"
                        title="Entre em contato conosco" target="_blank">Contato</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://leandrofranceschini.com.br/" target="_blank"
                        title="Página Inicial do Site da EM Dr. Leandro Franceschini">Site da LF</a>
                </li>
                <div class="d-flex align-items-center">
                    <a class="nav-link text-muted me-2"
                        href="https://www.facebook.com/emDrLeandroFranceschini/?locale=pt_BR"
                        title="Siga-nos no Facebook" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a class="nav-link text-muted me-2" href="https://www.instagram.com/emdrleandrofranceschini/"
                        title="Siga-nos no Instagram" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a class="nav-link text-muted" title="Siga-nos no YouTube"
                        href="https://www.youtube.com/@emdrleandrofranceschini" target="_blank">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </ul>
        </div>
    </div>
</nav>
