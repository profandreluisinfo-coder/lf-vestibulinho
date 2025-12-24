<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', 'Painel Administrativo - Vestibulinho LF')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Bootstrap & Ícones --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- Estilos adicionais --}}
    @stack('datatable-styles')

    {{-- Estilos --}}
    <link rel="stylesheet" href="{{ asset('assets/css/layouts/dash/admin.css') }}">

    @stack('styles')

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('head-scripts')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand" onclick="toggleSidebarCollapse()">
            <!-- IMAAGEM PARA RECOLHER/EXPANDIR -->
            <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo" height="32">
            <h4>{{ config('app.name') }} {{ $calendar->year }}</h4>
        </div>

        <nav class="sidebar-menu">

            <div class="menu-section">
                <div class="menu-section-title">Principal</div>
                <div class="menu-item">
                    <a href="{{ route('dash.admin.home') }}"
                        class="menu-link {{ request()->routeIs('painel') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        <span>Início</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Vestibulinho</div>
                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuVestibulinho')">
                        <i class="bi bi-calendar-event"></i>
                        <span>Gerenciar</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom {{ request()->routeIs(['calendar.admin.*', 'courses.*', 'notice.*', 'faqs.admin.*']) ? 'show' : '' }}"
                        id="menuVestibulinho">
                        <a href="{{ route('calendar.admin.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('calendar.admin.*') ? 'active' : '' }}">
                            Calendário
                        </a>
                        <a href="{{ route('courses.admin.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                            Cursos
                        </a>
                        <a href="{{ route('notice.admin.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('notice.admin.*') ? 'active' : '' }}">
                            Edital
                        </a>
                        <a href="{{ route('faqs.admin.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('faqs.admin.*') ? 'active' : '' }}">
                            Registrar FAQ
                        </a>
                    </div>
                </div>
            </div>

            <div class="menu-section">

                <div class="menu-section-title">Usuários e Inscrições</div>

                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuUsuarios')">
                        <i class="bi bi-people-fill"></i>
                        <span>Usuários</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom {{ request()->routeIs('users.*') ? 'show' : '' }}"
                        id="menuUsuarios">
                        <a href="{{ route('users.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            Lista de Usuários
                        </a>
                    </div>
                </div>

                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuInscricoes')">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Inscrições</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom {{ request()->routeIs('inscriptions.*') ? 'show' : '' }}"
                        id="menuInscricoes">
                        <a href="{{ route('inscriptions.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('inscriptions.index') ? 'active' : '' }}">
                            Lista Geral
                        </a>
                        <a href="{{ route('inscriptions.pcd') }}"
                            class="dropdown-item-custom {{ request()->routeIs('inscriptions.pcd') ? 'active' : '' }}">
                            Pessoas com Deficiência
                        </a>
                        <a href="{{ route('inscriptions.social.name') }}"
                            class="dropdown-item-custom {{ request()->routeIs('inscriptions.social.name') ? 'active' : '' }}">
                            Nome Social
                        </a>
                    </div>
                </div>
            </div>

            <div class="menu-section">

                <div class="menu-section-title">Provas e Resultados</div>

                <div class="menu-dropdown">

                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuProvas')">
                        <i class="bi bi-journal-check"></i>
                        <span>Provas</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="dropdown-menu-custom {{ request()->routeIs(['local.admin.*', 'exam.admin.*', 'export.users', 'archives.*']) ? 'show' : '' }}"
                        id="menuProvas">
                        <a href="{{ route('local.admin.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('local.admin.index') ? 'active' : '' }}">
                            Locais
                        </a>
                        <a href="{{ route('exam.admin.create') }}"
                            class="dropdown-item-custom {{ request()->routeIs('exam.admin.create') ? 'active' : '' }}">
                            Agendar
                        </a>
                        <a href="#" id="exportLink"
                            class="dropdown-item-custom {{ request()->routeIs('export.users') ? 'active' : '' }}"
                            onclick="handleExport(event, '{{ route('export.users') }}')">
                            Planilha de Notas
                        </a>
                        <a href="{{ route('archives.admin.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('archives.*') ? 'active' : '' }}">
                            Arquivos
                        </a>
                    </div>
                </div>

                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuResultados')">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Resultados</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom {{ request()->routeIs(['import.admin.home', 'ranking']) ? 'show' : '' }}"
                        id="menuResultados">
                        <a href="{{ route('import.admin.home') }}"
                            class="dropdown-item-custom {{ request()->routeIs('import.admin.home') ? 'active' : '' }}">
                            Importar Notas
                        </a>
                        <a href="{{ route('result.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('ranking') ? 'active' : '' }}">
                            Classificação Geral
                        </a>
                    </div>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Matrícula</div>
                <div class="menu-item">
                    <a href="{{ route('calls.admin.create') }}"
                        class="menu-link {{ request()->routeIs('calls.admin.*') ? 'active' : '' }}">
                        <i class="bi bi-broadcast-pin"></i>
                        <span>Chamadas</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Sistema</div>
                <div class="menu-item">
                    <a href="{{ route('system.index') }}"
                        class="menu-link {{ request()->routeIs('system.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Configurações</span>
                    </a>
                </div>
                <div class="menu-item p-0">
                    <form action="{{ route('logout') }}" method="POST" class="menu-link float-start">
                        @csrf
                        <button type="submit" class="text-danger">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </aside>
    
    <!-- Overlay para mobile -->
    <div id="sidebarOverlay" class="sidebar-overlay"  onclick="closeSidebar()"></div>

    <!-- Topbar -->
    <header class="topbar">

        <div class="topbar-left">
            <button type="button" class="menu-toggle">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <div class="topbar-right">

            <!-- Botão Offcanvas -->
            <button class="topbar-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu"
                aria-controls="offcanvasMenu">
                <i class="bi bi-grid-3x3-gap"></i>
            </button>

            <div class="dropdown">

                <div class="user-menu" data-bs-toggle="dropdown">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">Administrador</div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </div>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal">
                            <i class="bi bi-lock me-2"></i>Alterar Senha
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit">
                                <i class="bi bi-box-arrow-right me-2"></i>Sair
                            </button>
                        </form>
                    </li>
                </ul>

            </div>

        </div>

    </header>

    <!-- Conteúdo -->
    <main class="main-content py-5">

        @yield('dash-content')

    </main>

    {{-- Offcanvas Menu --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">
                <i class="bi bi-grid-3x3-gap me-2"></i>Menu Rápido
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>
        <div class="offcanvas-body">

            {{-- Seção de Ações Rápidas --}}
            <div class="mb-4">
                <h6 class="text-muted mb-3 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Ações
                    Rápidas</h6>
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('calendar.admin.index') }}" class="offcanvas-card">
                            <div class="offcanvas-card-icon" style="background: #dbeafe; color: #2563eb;">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <span>Calendário</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('users.index') }}" class="offcanvas-card">
                            <div class="offcanvas-card-icon" style="background: #fce7f3; color: #db2777;">
                                <i class="bi bi-people"></i>
                            </div>
                            <span>Usuários</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('inscriptions.index') }}" class="offcanvas-card">
                            <div class="offcanvas-card-icon" style="background: #d1fae5; color: #10b981;">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <span>Inscrições</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('result.index') }}" class="offcanvas-card">
                            <div class="offcanvas-card-icon" style="background: #fef3c7; color: #f59e0b;">
                                <i class="bi bi-bar-chart-line"></i>
                            </div>
                            <span>Ranking</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Seção de Links Úteis --}}
            <div class="mb-4">
                <h6 class="text-muted mb-3 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Links Úteis
                </h6>
                <div class="list-group list-group-flush">
                    <a href="{{ route('courses.admin.index') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-book me-2"></i>Gerenciar Cursos
                    </a>
                    <a href="{{ route('faqs.admin.index') }}" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-question-circle me-2"></i>Registrar FAQ
                    </a>
                    <a href="{{ route('exam.admin.index') }}" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-calendar-check me-2"></i>Agendar Prova
                    </a>
                    <a href="{{ route('result.index') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-list-ol me-2"></i>Ver Classificação
                    </a>
                    <a href="{{ route('calls.admin.create') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-broadcast-pin me-2"></i>Convocação para matrícula
                    </a>
                </div>
            </div>

            {{-- Seção de Estatísticas --}}
            <div class="mb-4">
                <h6 class="text-muted mb-3 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                    Estatísticas Rápidas</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <div>
                            <div class="text-muted" style="font-size: 12px;">Total de Inscrições</div>
                            <div class="fw-bold" style="font-size: 20px; color: #1e293b;">{{ $totalInscriptions }}
                            </div>
                        </div>
                        <i class="bi bi-file-earmark-text" style="font-size: 32px; color: #cbd5e1;"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <div>
                            <div class="text-muted" style="font-size: 12px;">Usuários Sem Inscrição</div>
                            <div class="fw-bold" style="font-size: 20px; color: #1e293b;">
                                {{ $usersWithoutInscription }}</div>
                        </div>
                        <i class="bi bi-people" style="font-size: 32px; color: #cbd5e1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Alterar Senha --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel"><i class="bi bi-lock me-2"></i>Alterar
                        Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="change-password" method="POST" action="{{ route('update.password') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Senha atual</label>
                            <input type="password" name="current_password" class="form-control"
                                id="currentPassword">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nova senha</label>
                            <input type="password" name="new_password" class="form-control" id="newPassword">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar senha</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="password_confirmation">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Alterar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- === JS === --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>

    {{-- Scripts --}}
    <script src="{{ asset('assets/components/sidebar.js') }}"></script>
    <script src="{{ asset('assets/auth/change-password.js') }}"></script>
    <script src="{{ asset('assets/swa/alerts-admin.js') }}"></script>
    <script src="{{ asset('assets/js/export/export-handler.js') }}"></script>

    @stack('plugins')

    @include('partials.alerts.admins')

    @stack('scripts')

</body>

</html>
