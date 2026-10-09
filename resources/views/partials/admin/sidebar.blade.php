@php
    $exam_results_count = \App\Models\ExamResult::count();
@endphp
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand" onclick="toggleSidebarCollapse()">
        <!-- IMAGEM PARA RECOLHER/EXPANDIR -->
        <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo" height="32">
        <h4>Vestibulinho LF {{ $process?->year }}</h4>
    </div>

    <nav class="sidebar-menu">

        <!-- ====== SEÇÃO: VESTIBULINHO ====== -->
        <div class="menu-section">
            <div class="menu-section-title">
                <i class="bi bi-book-half me-2"></i> Vestibulinho
            </div>

            <div class="menu-item">
                <a href="{{ route('admin.index') }}"
                    class="menu-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Início</span>
                </a>
            </div>

            <div class="menu-item">
                <a href="{{ route('admin.posts.index') }}"
                    class="menu-link {{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
                    <i class="bi bi-list"></i>
                    <span>Notícias e Comunicados</span>
                </a>
            </div>

            <!-- Gerenciar -->
            <div class="menu-dropdown">

                <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuVestibulinho')">
                    <i class="bi bi-wrench"></i>
                    <span>Gerenciar</span>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="dropdown-menu-custom {{ request()->routeIs(['admin.process.*', 'admin.courses.*', 'admin.notices.*', 'admin.faqs.*', 'admin.publications.*']) ? 'show' : '' }}"
                    id="menuVestibulinho">
                    <a href="{{ route('admin.process.show') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.process.*') ? 'active' : '' }}">
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
                    <a href="{{ route('admin.publications.index') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.publications.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone me-1"></i> Publicações
                    </a>
                </div>
            </div>
            <!-- Gerenciar Usuários -->
            <div class="menu-item">
                <a href="{{ route('admin.users.index') }}"
                    class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Usuários</span>
                </a>
            </div>
            <!-- Gerenciar Inscrições -->
            <div class="menu-dropdown">
                <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuInscricoes')">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Inscrições</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="dropdown-menu-custom {{ request()->routeIs('admin.inscriptions.index') || request()->routeIs('admin.inscriptions.show')
                    ? 'show'
                    : '' }}"
                    id="menuInscricoes">
                    <a href="{{ route('admin.inscriptions.index') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.inscriptions.index') ? 'active' : '' }}">
                        <i class="bi bi-people me-1"></i> Candidatos
                    </a>
                </div>
            </div>
            <!-- Gerenciar Análises -->
            <div class="menu-dropdown">
                <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuAnalises')">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Análises</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="dropdown-menu-custom {{ request()->routeIs('admin.inscriptions.pcds') ||
                request()->routeIs('admin.inscriptions.lgbts') ||
                request()->routeIs('admin.analyses.*') ||
                request()->routeIs('admin.appeals.*')
                    ? 'show'
                    : '' }}"
                    id="menuAnalises">

                    <a href="{{ route('admin.inscriptions.pcds') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.inscriptions.pcds') || request()->routeIs('admin.analyses.report.*')
                            ? 'active'
                            : '' }}">
                        <i class="bi bi-universal-access me-1"></i> Laudos/Relatórios (PCDs)
                    </a>

                    <a href="{{ route('admin.inscriptions.lgbts') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.inscriptions.lgbts') ? 'active' : '' }}">
                        <i class="bi bi-gender-trans me-1"></i> Autorizações (LGBTQIA+)
                    </a>

                    <a href="{{ route('admin.appeals.index') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.appeals.*') ? 'active' : '' }}">
                        <i class="bi bi-folder2-open me-1"></i> Recursos
                    </a>

                </div>
            </div>

            <div class="menu-dropdown">
                <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuResultados')">
                    <i class="bi bi-database"></i>
                    <span>Dados</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="dropdown-menu-custom {{ request()->routeIs(['admin.export.*', 'admin.import.*']) ? 'show' : '' }}"
                    id="menuResultados">
                    @if ($exam_results_count > 0)
                        <a href="javascript:void(0)" id="exportLink"
                            class="dropdown-item-custom {{ request()->routeIs('admin.export.excel') ? 'active' : '' }}"
                            onclick="handleExport(event, '{{ route('admin.export.excel') }}')">
                            <i class="bi bi-file-excel me-1"></i> Planilha de Notas
                        </a>
                    @endif
                    <a href="{{ route('admin.import.home') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.import.home') ? 'active' : '' }}">
                        <i class="bi bi-upload me-1"></i> Importar Notas
                    </a>
                </div>
            </div>

            <div class="menu-dropdown">
                <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuRelatorios')">
                    <i class="bi bi-clipboard-data"></i>
                    <span>Relatórios</span>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="dropdown-menu-custom {{ request()->routeIs('admin.reports.*') ? 'show' : '' }}"
                    id="menuRelatorios">

                    @php
                        $isGeneral = request()->routeIs('admin.reports.general');
                        $hasShortcut = request()->hasAny(['type', 'group_by', 'pcd', 'social_name']);
                    @endphp

                    <a href="{{ route('admin.reports.general') }}"
                        class="dropdown-item-custom {{ $isGeneral && !$hasShortcut ? 'active' : '' }}">
                        <i class="bi bi-funnel me-1"></i> Relatório Geral
                    </a>

                    <a href="{{ route('admin.reports.general', ['type' => 'summary', 'group_by' => 'course']) }}"
                        class="dropdown-item-custom {{ $isGeneral && request('type') === 'summary' && request('group_by') === 'course' ? 'active' : '' }}">
                        <i class="bi bi-bar-chart me-1"></i> Candidatos por Curso
                    </a>

                    <a href="{{ route('admin.reports.general', ['pcd' => 'all']) }}"
                        class="dropdown-item-custom {{ $isGeneral && request('pcd') === 'all' ? 'active' : '' }}">
                        <i class="bi bi-universal-access me-1"></i> Pessoas com Deficiência (PCDs)
                    </a>

                    <a href="{{ route('admin.reports.general', ['social_name' => 'all']) }}"
                        class="dropdown-item-custom {{ $isGeneral && request('social_name') === 'all' ? 'active' : '' }}">
                        <i class="bi bi-gender-trans me-1"></i> Nome Social (LGBTQIA+)
                    </a>

                    <a href="{{ route('admin.reports.classification') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.reports.classification') ? 'active' : '' }}">
                        <i class="bi bi-list-ol me-1"></i> Classificação
                    </a>
                </div>
            </div>

            <div class="menu-dropdown">

                <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuProvas')">
                    <i class="bi bi-journal-check"></i>
                    <span>Provas</span>
                    <i class="bi bi-chevron-down"></i>
                </button>

                <div class="dropdown-menu-custom {{ request()->routeIs(['admin.local.*', 'admin.exam.*', 'admin.archives.*']) ? 'show' : '' }}"
                    id="menuProvas">
                    <a href="{{ route('admin.local.index') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.local.index') ? 'active' : '' }}">
                        <i class="bi bi-geo me-1"></i> Locais
                    </a>
                    <a href="{{ route('admin.exam.create') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.exam.create') ? 'active' : '' }}">
                        <i class="bi bi-calendar2-week me-1"></i> Agendar
                    </a>

                    <a href="{{ route('admin.archives.index') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.archives.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Publicar
                    </a>
                </div>
            </div>

            <div class="menu-dropdown">
                <button class="dropdown-toggle-custom" onclick="toggleDropdown('menuModelos')">
                    <i class="bi bi-file-earmark"></i>
                    <span>Modelos</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="dropdown-menu-custom {{ request()->routeIs(['admin.templates.*']) ? 'show' : '' }}"
                    id="menuModelos">
                    <a href="{{ route('admin.templates.index') }}"
                        class="dropdown-item-custom {{ request()->routeIs('admin.templates.index') ? 'active' : '' }}">
                        <i class="bi bi-gender-trans me-1"></i> Nome Social
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
                    <a href="{{ route('admin.system.backups.index') }}"
                        class="menu-link {{ request()->routeIs('admin.system.backups.index') ? 'active' : '' }}">
                        <i class="bi bi-hdd"></i>
                        <span>Backup</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.system.index') }}"
                        class="menu-link {{ request()->routeIs('admin.system.index') ? 'active' : '' }}">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>Redefinir</span>
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
