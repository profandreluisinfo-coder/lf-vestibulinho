<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('error_title', 'Erro') — Vestibulinho LF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    {{-- Tokens e estilos base do site --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/guest/home/index.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/layouts/errors/styles.css') }}" />
    @stack('styles')
</head>

<body class="err-body">

    <!-- ═══════════════════════ NAVBAR ═══════════════════════════ -->
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div class="err-brand-icon">
                    <i class="bi bi-mortarboard-fill text-white" style="font-size:1.1rem;"></i>
                </div>
                <div>
                    <span class="school text-white">EM Dr. Leandro Franceschini</span>
                    <span class="sub text-white">Vestibulinho {{ config('app.year') }}</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navMenu">
                <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#cursos">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#como-participar">Como Participar</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('guest.calendar.show') }}">Calendário</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#faq">FAQ</a></li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn-nav-cta" href="{{ route('login') }}">
                            <i class="bi bi-person-circle me-1"></i> Área do Candidato
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ═══════════════════════ CONTEÚDO DO ERRO ═════════════════ -->
    <main class="err-main">

        {{-- Círculos decorativos de fundo (mesmo visual do hero) --}}
        <div class="err-circle err-circle-1"></div>
        <div class="err-circle err-circle-2"></div>
        <div class="err-circle err-circle-3"></div>

        <div class="container position-relative" style="z-index:1;">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-6 text-center">

                    {{-- Código do erro (ex: "404") --}}
                    <div class="err-code" aria-hidden="true">
                        @yield('error_code', '?')
                    </div>

                    {{-- Ícone semântico do tipo de erro --}}
                    <div class="err-icon-wrap mb-4">
                        <i class="bi @yield('error_icon', 'bi-exclamation-circle')"></i>
                    </div>

                    {{-- Título legível --}}
                    <h1 class="err-title mb-3">
                        @yield('error_heading', 'Algo deu errado')
                    </h1>

                    {{-- Mensagem de apoio --}}
                    <p class="err-message mb-5">
                        @yield('error_message', 'Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.')
                    </p>

                    {{-- Ações — cada view filha pode sobrescrever este bloco --}}
                    @section('error_actions')
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('home') }}" class="err-btn-primary">
                                <i class="bi bi-house-fill me-2"></i> Voltar ao Início
                            </a>
                            <a href="javascript:history.back()" class="err-btn-outline">
                                <i class="bi bi-arrow-left me-2"></i> Página Anterior
                            </a>
                        </div>
                    @show

                    {{-- Bloco opcional para dicas / links adicionais --}}
                    @hasSection('error_hints')
                        <div class="err-hints mt-5">
                            @yield('error_hints')
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </main>

    <!-- ═══════════════════════ FOOTER MINIMALISTA ═══════════════ -->
    <footer class="err-footer">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="mb-0">
                    © {{ date('Y') }} EM Dr. Leandro Franceschini · Todos os direitos reservados.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('home') }}">Início</a>
                    <a href="{{ route('login') }}">Área do Candidato</a>
                    <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br">Contato</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>
</html>