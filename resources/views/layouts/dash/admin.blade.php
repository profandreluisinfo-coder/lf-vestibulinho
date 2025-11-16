<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

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
    <style>
        /* ==================== VARIÁVEIS ==================== */
        :root {
            --sidebar-width: 260px;
            --topbar-height: 60px;
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-text: #cbd5e1;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius: 8px;
            --transition: all 0.3s ease;
        }

        /* ==================== BASE ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            overflow-y: auto;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            z-index: 1000;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #0f172a;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #475569;
            border-radius: 3px;
        }

        /* ====== Marca ====== */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px;
            border-bottom: 1px solid #1e293b;
        }

        .sidebar-brand img {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
        }

        .sidebar-brand h4 {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin: 0;
            line-height: 1.2;
        }

        /* ====== Menu ====== */
        .sidebar-menu {
            padding: 20px 0;
        }

        /* ====== Seções ====== */
        .menu-section {
            margin-bottom: 24px;
        }

        .menu-section-title {
            padding: 8px 20px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ====== Links e Dropdowns ====== */
        .menu-item {
            padding: 0 16px;
            margin-bottom: 4px;
        }

        .menu-dropdown {
            padding: 0 16px;
            margin-bottom: 4px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            color: var(--sidebar-text);
            border-radius: var(--radius);
            background: transparent;
            border: none;
            transition: var(--transition);
            cursor: pointer;
            text-align: left;
            font-size: 15px;
        }

        .dropdown-toggle-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            color: var(--sidebar-text);
            border-radius: var(--radius);
            background: transparent;
            border: none;
            transition: var(--transition);
            cursor: pointer;
            text-align: left;
            font-size: 15px;
        }

        .menu-link i,
        .dropdown-toggle-custom i:first-child {
            font-size: 18px;
            width: 20px;
            flex-shrink: 0;
        }

        .menu-link:hover,
        .dropdown-toggle-custom:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .menu-link.active {
            background: var(--primary-color);
            color: #fff;
        }

        /* ====== Dropdown interno ====== */
        .dropdown-toggle-custom .bi-chevron-down {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .dropdown-toggle-custom[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }

        .dropdown-menu-custom {
            padding-left: 48px;
            padding-right: 16px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .dropdown-menu-custom.show {
            max-height: 500px;
            padding-top: 4px;
            padding-bottom: 8px;
        }

        .dropdown-item-custom {
            display: block;
            color: #94a3b8;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            transition: var(--transition);
            margin-bottom: 2px;
        }

        .dropdown-item-custom:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        /* ====== Botão de sair dentro do form ====== */
        .sidebar form {
            padding: 0 16px;
            margin-bottom: 4px;
        }

        .sidebar form button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            color: #cbd5e1;
            border: none;
            border-radius: var(--radius);
            background: transparent;
            transition: var(--transition);
            text-align: left;
            cursor: pointer;
            font-size: 15px;
        }

        .sidebar form button:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar form button i {
            font-size: 18px;
            width: 20px;
        }

        /* ==================== TOPBAR ==================== */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 900;
            transition: var(--transition);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 24px;
            color: #475569;
            cursor: pointer;
            display: none;
        }

        /* ====== Ícones ====== */
        .topbar-icon {
            position: relative;
            background: none;
            border: none;
            font-size: 20px;
            color: #475569;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .topbar-icon:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        .topbar-icon .badge {
            position: absolute;
            top: 6px;
            right: 6px;
            padding: 3px 5px;
            font-size: 10px;
        }

        /* ====== Usuário ====== */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: var(--radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .user-menu:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #7c3aed);
            color: #fff;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.2;
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 24px;
            min-height: calc(100vh - var(--topbar-height));
            transition: var(--transition);
        }

        /* ==================== OFFCANVAS MENU ==================== */
        .offcanvas {
            width: 380px !important;
        }

        .offcanvas-header {
            padding: 20px 24px;
        }

        .offcanvas-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .offcanvas-body {
            padding: 24px;
        }

        /* Cards de ações rápidas */
        .offcanvas-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 12px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-dark);
            transition: var(--transition);
            gap: 12px;
            height: 100%;
        }

        .offcanvas-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .offcanvas-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .offcanvas-card span {
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }

        /* Links úteis */
        .list-group-item {
            color: var(--text-dark);
            transition: var(--transition);
            padding: 12px 0;
        }

        .list-group-item:hover {
            color: var(--primary-color);
            background: transparent;
            padding-left: 8px;
        }

        .list-group-item i {
            font-size: 18px;
        }

        /* ==================== OVERLAY (MOBILE) ==================== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ==================== FORMS ==================== */
        .form-label {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* ==================== TABLES ==================== */
        .table th,
        .table td {
            text-align: center;
        }

        .table th {
            vertical-align: middle
        }

        .freezed-table thead th {
            position: sticky !important;
            top: 0;
            z-index: 5;
            background-color: var(--bs-table-bg, #d1e7dd);
        }

        /* ==================== OTHERS ==================== */
        .d-flex h4 {
            color: #475569;
        }

        i {
            align-self: center;
        }

        /* ==================== RESPONSIVIDADE ==================== */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .topbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .user-info {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .topbar {
                padding: 0 16px;
            }

            .main-content {
                padding: 16px;
            }

            .offcanvas {
                width: 100% !important;
            }

            .offcanvas-body {
                padding: 20px;
            }
        }
    </style>
    @stack('styles')

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('head-scripts')
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo" width="32" height="32">
            <h4>{{ config('app.name') }} {{ $calendar?->year }}</h4>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Principal</div>
                <div class="menu-item">
                    <a href="{{ route('admin.painel') }}" class="menu-link active">
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
                    <div class="dropdown-menu-custom" id="menuVestibulinho">
                        <a href="{{ route('calendar.index') }}" class="dropdown-item-custom">Calendário</a>
                        <a href="{{ route('courses.index') }}" class="dropdown-item-custom">Cursos</a>
                        <a href="{{ route('notice.index') }}" class="dropdown-item-custom">Edital</a>
                        <a href="{{ route('faq.index') }}" class="dropdown-item-custom">Registrar FAQ</a>
                        <a href="{{ route('archive.index') }}" class="dropdown-item-custom">Acervo de Provas</a>
                        {{-- <a href="{{ route('system.index') }}" class="dropdown-item-custom">Redefinir Dados</a> --}}
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
                    <div class="dropdown-menu-custom" id="menuUsuarios">
                        <a href="{{ route('users.index') }}" class="dropdown-item-custom">Lista de Usuários</a>
                    </div>
                </div>

                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuInscricoes')">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Inscrições</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="menuInscricoes">
                        <a href="{{ route('inscriptions.index') }}" class="dropdown-item-custom">Lista Geral</a>
                        <a href="{{ route('inscriptions.pcd') }}" class="dropdown-item-custom">Pessoas com
                            Deficiência</a>
                        <a href="{{ route('inscriptions.social-name') }}" class="dropdown-item-custom">Nome
                            Social</a>
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
                    <div class="dropdown-menu-custom" id="menuProvas">
                        <a href="{{ route('exam.locations') }}" class="dropdown-item-custom">Locais</a>
                        <a href="{{ route('exam.schedule') }}" class="dropdown-item-custom">Agendar</a>
                        <a href="{{ route('export.users') }}" class="dropdown-item-custom">Planilha de Notas</a>
                    </div>
                </div>

                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuResultados')">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Resultados</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom" id="menuResultados">
                        <a href="{{ route('import.results') }}" class="dropdown-item-custom">Importar Notas</a>
                        <a href="{{ route('ranking') }}" class="dropdown-item-custom">Classificação Geral</a>
                    </div>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Matrícula</div>
                <div class="menu-item">
                    <a href="{{ route('callings.create') }}" class="menu-link">
                        <i class="bi bi-broadcast-pin"></i>
                        <span>Chamadas</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Sistema</div>
                <div class="menu-item">
                    <a href="{{ route('system.index') }}" class="menu-link">
                        <i class="bi bi-gear"></i>
                        <span>Configurações</span>
                    </a>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sair</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Overlay para mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-toggle" onclick="toggleSidebar()">
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
                        <div class="user-name">{{ auth()->user()->social_name ?? auth()->user()->name }}</div>
                        <div class="user-role">Administrador</div>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal">
                            <i class="bi bi-key me-2"></i>Alterar Senha
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
    <main class="main-content">
        @yield('dash-content')

        {{-- Modal Alterar Senha --}}
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Alterar Senha</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="change-password" method="POST" action="{{ route('alterar.senha') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Senha atual</label>
                                <input type="password" name="current_password" class="form-control"
                                    id="currentPassword" placeholder="••••••••">
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Nova senha</label>
                                <input type="password" name="new_password" class="form-control" id="newPassword"
                                    placeholder="••••••••">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar senha</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="password_confirmation" placeholder="••••••••">
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
                        <a href="{{ route('calendar.index') }}" class="offcanvas-card">
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
                        <a href="{{ route('ranking') }}" class="offcanvas-card">
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
                    <a href="{{ route('courses.index') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-book me-2"></i>Gerenciar Cursos
                    </a>
                    <a href="{{ route('faq.index') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-question-circle me-2"></i>Registrar FAQ
                    </a>
                    <a href="{{ route('exam.schedule') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-calendar-check me-2"></i>Agendar Prova
                    </a>
                    <a href="{{ route('ranking') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-list-ol me-2"></i>Ver Classificação
                    </a>
                    <a href="{{ route('callings.create') }}"
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

            {{-- Seção de Notificações --}}
            {{-- <div>
                <h6 class="text-muted mb-3 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Notificações Recentes</h6>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="flex-shrink-0">
                            <div style="width: 36px; height: 36px; background: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1" style="font-size: 14px;">Nova inscrição</div>
                            <div class="text-muted" style="font-size: 12px;">Maria Silva realizou inscrição</div>
                            <small class="text-muted">Há 5 minutos</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <div class="flex-shrink-0">
                            <div style="width: 36px; height: 36px; background: #d1fae5; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1" style="font-size: 14px;">Importação concluída</div>
                            <div class="text-muted" style="font-size: 12px;">Notas importadas com sucesso</div>
                            <small class="text-muted">Há 1 hora</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <div class="flex-shrink-0">
                            <div style="width: 36px; height: 36px; background: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #f59e0b;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1" style="font-size: 14px;">Atenção</div>
                            <div class="text-muted" style="font-size: 12px;">Prazo de inscrições terminando</div>
                            <small class="text-muted">Há 2 horas</small>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    {{-- === JS === --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>

    {{-- Scripts --}}
    <script src="{{ asset('assets/auth/change-password.js') }}"></script>
    <script src="{{ asset('assets/swa/alerts-admin.js') }}"></script>

    <script>
        /* =========================================================
               FUNÇÃO GLOBAL SWEETALERT2
               Exibe alertas personalizados de sucesso, erro, aviso e info
               ========================================================= */
        // function showAlert(icon, title, text, confirmButtonText = 'Ok', allowOutsideClick = true) {
        //     Swal.fire({
        //         icon: icon,
        //         title: title,
        //         html: text,
        //         confirmButtonText: confirmButtonText,
        //         allowOutsideClick: allowOutsideClick,
        //         confirmButtonColor: icon === 'success' ? '#10b981' : 
        //                            icon === 'error' ? '#ef4444' : 
        //                            icon === 'warning' ? '#f59e0b' : 
        //                            '#2563eb',
        //         customClass: {
        //             popup: 'animate__animated animate__fadeInDown animate__faster',
        //             confirmButton: 'btn btn-lg px-4'
        //         },
        //         showClass: {
        //             popup: 'animate__animated animate__fadeInDown'
        //         },
        //         hideClass: {
        //             popup: 'animate__animated animate__fadeOutUp'
        //         }
        //     });
        // }
        /* =========================================================
           SIDEBAR & DROPDOWNS - VESTIBULINHO LF
           Controla o comportamento da sidebar responsiva e
           os menus dropdown internos.
           ========================================================= */

        // Funções globais para toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar && overlay) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        }

        // Função global para toggleDropdown
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            if (!dropdown) return;

            const button = dropdown.previousElementSibling;
            const allDropdowns = document.querySelectorAll('.dropdown-menu-custom');
            const allButtons = document.querySelectorAll('.dropdown-toggle-custom');

            // Fecha todos os outros dropdowns
            allDropdowns.forEach(d => {
                if (d.id !== id) {
                    d.classList.remove('show');
                }
            });

            allButtons.forEach(b => {
                if (b !== button) {
                    b.setAttribute('aria-expanded', 'false');
                }
            });

            // Toggle do dropdown clicado
            const isCurrentlyOpen = dropdown.classList.contains('show');

            if (isCurrentlyOpen) {
                dropdown.classList.remove('show');
                button.setAttribute('aria-expanded', 'false');
            } else {
                dropdown.classList.add('show');
                button.setAttribute('aria-expanded', 'true');
            }
        }

        // Quando o DOM estiver pronto
        document.addEventListener('DOMContentLoaded', function() {

            // Fechar sidebar ao clicar em links (mobile)
            const menuLinks = document.querySelectorAll('.menu-link, .dropdown-item-custom');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeSidebar();
                    }
                });
            });

            // Fechar sidebar ao redimensionar para desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    closeSidebar();
                }
            });

            // Listener para overlay
            const overlay = document.getElementById('sidebarOverlay');
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Listener para botão toggle
            const toggleBtn = document.querySelector('.menu-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }

            // Fechar offcanvas ao clicar em links internos
            const offcanvasLinks = document.querySelectorAll('#offcanvasMenu a');
            const offcanvasElement = document.getElementById('offcanvasMenu');

            offcanvasLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (bsOffcanvas) {
                        bsOffcanvas.hide();
                    }
                });
            });
        });
    </script>

    @stack('plugins')
    @include('partials.alerts.admins')
    @stack('scripts')
</body>

</html>
