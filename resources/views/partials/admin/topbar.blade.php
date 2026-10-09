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
