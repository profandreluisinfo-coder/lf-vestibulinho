<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', 'Painel Administrativo | Vestibulinho LF')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Bootstrap & Ícones --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Estilos adicionais --}}
    @stack('datatable-styles')

    {{-- Estilos --}}
    <link rel="stylesheet" href="{{ asset('assets/css/layouts/admin/styles.css') }}">

    @stack('styles')

    @stack('head-scripts')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand" onclick="toggleSidebarCollapse()">
            <!-- IMAGEM PARA RECOLHER/EXPANDIR -->
            <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo" height="32">
            <h4>{{ config('app.name') }} {{ $process?->year }}</h4>
        </div>

        <nav class="sidebar-menu">

            <!-- ====== SEÇÃO: SITE ====== -->
            <div class="menu-section">
                <div class="menu-section-title">
                    <i class="bi bi-globe me-2"></i> Site
                </div>

                <div class="menu-item">
                    <a href="{{ route('admin.posts.index') }}"
                        class="menu-link {{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
                        <i class="bi bi-list"></i>
                        <span>Notícias e Comunicados</span>
                    </a>
                </div>

                {{-- <div class="menu-item">
                    <a href="{{ route('admin.infos.create') }}"
                        class="menu-link {{ request()->routeIs('admin.communicates.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i>
                        <span>Novo comunicado</span>
                    </a>
                </div> --}}

            </div>

            <!-- ====== SEÇÃO: VESTIBULINHO ====== -->
            <div class="menu-section">
                <div class="menu-section-title">
                    <i class="bi bi-book-half me-2"></i> Vestibulinho
                </div>

                <div class="menu-item">
                    <a href="{{ route('admin.index') }}"
                        class="menu-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        <span>Início</span>
                    </a>
                </div>

                <!-- Gerenciar -->
                <div class="menu-dropdown">

                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuVestibulinho')">
                        <i class="bi bi-wrench"></i>
                        <span>Gerenciar</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="dropdown-menu-custom {{ request()->routeIs(['admin.events.*', 'admin.courses.*', 'admin.notices.*', 'admin.faqs.*']) ? 'show' : '' }}"
                        id="menuVestibulinho">
                        <a href="{{ route('admin.process.show') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event me-1"></i> Eventos
                        </a>
                        <a href="{{ route('admin.courses.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                            <i class="bi bi-book me-1"></i> Cursos
                        </a>
                        <a href="{{ route('admin.faqs.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                            <i class="bi bi-question-circle me-1"></i> FAQs
                        </a>
                    </div>
                </div>

                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuUsuarios')">
                        <i class="bi bi-people-fill"></i>
                        <span>Usuários</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom {{ request()->routeIs('admin.users.*') ? 'show' : '' }}"
                        id="menuUsuarios">
                        <a href="{{ route('admin.users.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people me-1"></i> Lista de Usuários
                        </a>
                    </div>
                </div>

                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuInscricoes')">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Inscrições</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom {{ request()->routeIs('admin.inscriptions.*') ? 'show' : '' }}"
                        id="menuInscricoes">
                        <a href="{{ route('admin.inscriptions.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.inscriptions.index') ? 'active' : '' }}">
                            <i class="bi bi-people me-1"></i> Candidatos
                        </a>
                        <a href="{{ route('admin.inscriptions.pcd') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.inscriptions.pcd') ? 'active' : '' }}">
                            <i class="bi bi-universal-access me-1"></i> Pessoas com Deficiência
                        </a>
                        <a href="{{ route('admin.inscriptions.lgbts') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.inscriptions.social.name') ? 'active' : '' }}">
                            <i class="bi bi-gender-trans me-1"></i> Nome Social
                        </a>
                    </div>
                </div>

                <div class="menu-dropdown">

                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuProvas')">
                        <i class="bi bi-journal-check"></i>
                        <span>Provas</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="dropdown-menu-custom {{ request()->routeIs(['admin.local.*', 'admin.exam.*', 'admin.archives.*', 'admin.export.*']) ? 'show' : '' }}"
                        id="menuProvas">
                        <a href="{{ route('admin.local.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.local.index') ? 'active' : '' }}">
                            <i class="bi bi-geo me-1"></i> Locais
                        </a>
                        <a href="{{ route('admin.exam.create') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.exam.create') ? 'active' : '' }}">
                            <i class="bi bi-calendar2-week me-1"></i> Agendar
                        </a>
                        <a href="javascript:void(0)" id="exportLink"
                            class="dropdown-item-custom {{ request()->routeIs('admin.export.excel') ? 'active' : '' }}"
                            onclick="handleExport(event, '{{ route('admin.export.excel') }}')">
                            <i class="bi bi-file-excel me-1"></i> Planilha de Notas
                        </a>
                        <a href="{{ route('admin.archives.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.archives.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Arquivos
                        </a>
                    </div>
                </div>

                <div class="menu-dropdown">
                    <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuResultados')">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Resultados</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-custom {{ request()->routeIs(['admin.import.*', 'admin.results.*']) ? 'show' : '' }}"
                        id="menuResultados">
                        <a href="{{ route('admin.import.home') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.import.home') ? 'active' : '' }}">
                            <i class="bi bi-upload me-1"></i> Importar Notas
                        </a>
                        <a href="{{ route('admin.results.index') }}"
                            class="dropdown-item-custom {{ request()->routeIs('admin.results.index') ? 'active' : '' }}">
                            <i class="bi bi-list-ol me-1"></i> Classificação
                        </a>
                    </div>
                </div>

                <div class="menu-item">
                    <a href="{{ route('admin.calls.index') }}"
                        class="menu-link {{ request()->routeIs('admin.calls.*') ? 'active' : '' }}">
                        <i class="bi bi-broadcast-pin"></i>
                        <span>Chamadas</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Sistema</div>
                    <div class="menu-item">
                        <a href="{{ route('admin.system.index') }}"
                            class="menu-link {{ request()->routeIs('admin.system.*') ? 'active' : '' }}">
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
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

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
    <main class="main-content">

        @include('shared.toasts')

        @yield('content')

        {{-- Modal Alterar Senha --}}
        @include('partials.forms.change-password')

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
                        <a href="{{ route('admin.process.show') }}" class="offcanvas-card">
                            <div class="offcanvas-card-icon" style="background: #dbeafe; color: #2563eb;">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <span>Calendário</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.users.index') }}" class="offcanvas-card">
                            <div class="offcanvas-card-icon" style="background: #fce7f3; color: #db2777;">
                                <i class="bi bi-people"></i>
                            </div>
                            <span>Usuários</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.inscriptions.index') }}" class="offcanvas-card">
                            <div class="offcanvas-card-icon" style="background: #d1fae5; color: #10b981;">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <span>Inscrições</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.results.index') }}" class="offcanvas-card">
                            <div class="offcanvas-card-icon" style="background: #fef3c7; color: #f59e0b;">
                                <i class="bi bi-bar-chart-line"></i>
                            </div>
                            <span>Classificação</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Seção de Links Úteis --}}
            <div class="mb-4">
                <h6 class="text-muted mb-3 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Links Úteis
                </h6>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.courses.index') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-book me-2"></i>Gerenciar Cursos
                    </a>
                    <a href="{{ route('admin.faqs.index') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-question-circle me-2"></i>Registrar FAQ
                    </a>
                    <a href="{{ route('admin.exam.index') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-calendar-check me-2"></i>Agendar Prova
                    </a>
                    <a href="{{ route('admin.results.index') }}"
                        class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-list-ol me-2"></i>Ver Classificação
                    </a>
                    <a href="{{ route('admin.calls.index') }}"
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

    {{-- === PLUGGINS === --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>

    {{-- === PLUGGINS ESPECÍFICOS === --}}
    @stack('plugins')

    {{-- === JS === --}}
    <script src="{{ asset('assets/js/admin/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/shared/toasts.js') }}"></script>
    <script type="module" src="{{ asset('assets/js/shared/change-password.js') }}"></script>
    <script src="{{ asset('assets/js/shared/popovers.js') }}"></script>

    {{-- === JS ESPECÍFICOS === --}}
    @stack('scripts')

    <script>
        const resetUrl = "{{ route('admin.system.reset') }}";
    </script>

    @if (session('open_modal') === 'password')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
            });
        </script>
    @endif
    
    <script src="{{ asset('assets/js/swa/system/reset.js') }}"></script>
    <script src="{{ asset('assets/js/admin/export/handler.js') }}"></script>
</body>

</html>