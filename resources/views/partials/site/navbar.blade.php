<nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div
                style="width:38px;height:38px;border-radius:10px;background:var(--grad-teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-bank text-white" style="font-size:1.1rem;"></i>
            </div>
            <div>
                <span class="school text-white">EM Dr. Leandro Franceschini</span>
                <span class="sub text-white">Sumaré - SP</span>
            </div>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}/#cursos"><i
                            class="bi bi-mortarboard me-1"></i>
                        Cursos</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}/#noticias"><i class="bi bi-newspaper me-1"></i>
                        Notícias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}/#comunicados"><i class="bi bi-chat-dots me-1"></i>
                        Comunicados</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('guest.home') }}"><i
                            class="bi bi-mortarboard"></i> Vestibulinho</a></li>
                <li class="nav-item d-none d-lg-block mx-2">
                    <div class="vr text-white opacity-50"></div>
                </li>
            </ul>
            <div class="social-links-container d-flex align-items-center gap-2 ms-lg-3">
                <li class="nav-item">
                    <a href="https://www.facebook.com/suaescola" target="_blank" class="nav-link social-link"
                        aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="https://www.instagram.com/suaescola" target="_blank" class="nav-link social-link"
                        aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="https://www.youtube.com/@suaescola" target="_blank" class="nav-link social-link"
                        aria-label="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="https://wa.me/5519999999999" target="_blank" class="nav-link social-link"
                        aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </li>
            </div>
        </div>
    </div>
</nav>
