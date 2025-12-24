<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-white shadow-sm m-0 py-1">
    <div class="container">
        <a class="navbar-brand text-primary" href="{{ route('home') }}" title="Página Inicial">
            <i class="bi bi-mortarboard me-2"></i>Vestibulinho LF {{ $calendar->year ?? '' }}
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
                    <a class="nav-link" href="{{ route('login') }}" title="Acesso para candidatos">
                        <i class="bi bi-person-lock"></i> Área do Candidato
                    </a>
                </li>
                <a class="nav-link js-confirm-external"
                    href="https://leandrofranceschini.com.br/#form-contato"
                    title="Entre em contato conosco"
                    target="_blank">
                    <i class="bi bi-telephone"></i> Contato
                </a>
            </ul>
        </div>
    </div>
</nav>