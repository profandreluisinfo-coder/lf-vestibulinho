{{--
  ─── partials/dash/navbar.blade.php ────────────────────────
  Navbar da Área do Candidato.
  Derivada da navbar pública — mantém o navbar-brand original.
  Depende de: theme.css  (tokens e .navbar-custom)
  ────────────────────────────────────────────────────────────
--}}

<nav class="navbar navbar-expand-lg navbar-custom fixed-top" id="mainNav">
    <div class="container">

        {{-- ── Brand (idêntico ao site público) ──────────────── --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="javascript:void(0);">
            <div style="width:38px;height:38px;border-radius:10px;background:var(--grad-teal);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-mortarboard-fill text-white" style="font-size:1.1rem;"></i>
            </div>
            <div>
                <span class="school text-white">EM Dr. Leandro Franceschini</span>
                <span class="sub text-white">Vestibulinho {{ $calendar?->year }}</span>
            </div>
        </a>

        {{-- ── Toggle mobile ──────────────────────────────────── --}}
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navMenuDash"
                aria-controls="navMenuDash"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
        </button>

        {{-- ── Itens de navegação ─────────────────────────────── --}}
        <div class="collapse navbar-collapse" id="navMenuDash">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">

                @auth

                    {{-- Separador visual (apenas desktop) --}}
                    <li class="nav-item d-none d-lg-flex align-items-center px-1">
                        <span style="width:1px;height:22px;background:rgba(255,255,255,.15);display:block;"></span>
                    </li>

                    {{-- Dropdown do usuário logado --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                           href="#"
                           id="userDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">

                            {{-- Avatar com iniciais --}}
                            @php
                                $nameParts = explode(' ', trim($displayName ?? 'U'));
                                $initials  = strtoupper(substr($nameParts[0], 0, 1))
                                           . strtoupper(substr($nameParts[1] ?? '', 0, 1));
                            @endphp

                            <span style="
                                width:30px;height:30px;border-radius:50%;
                                background:var(--grad-teal);
                                color:#fff;font-size:.72rem;font-weight:800;
                                display:inline-flex;align-items:center;justify-content:center;
                                flex-shrink:0;letter-spacing:.03em;
                                font-family:var(--font-heading);
                                box-shadow:0 2px 8px rgba(0,168,150,.4);">
                                {{ $initials }}
                            </span>

                            {{-- Nome: completo em md+, truncado em mobile --}}
                            <span class="d-none d-md-inline text-truncate" style="max-width:180px;">
                                {{ $displayName ?? 'Candidato sem inscrição' }}
                            </span>
                            <span class="d-inline d-md-none">
                                Minha conta
                            </span>

                        </a>

                        {{-- Menu dropdown --}}
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

                            {{-- Cabeçalho informativo --}}
                            <li>
                                <div style="padding:.6rem .85rem .5rem;border-bottom:1px solid var(--color-light-mid);">
                                    <p style="font-size:.72rem;font-weight:700;color:var(--color-muted);
                                              text-transform:uppercase;letter-spacing:.07em;margin:0 0 .15rem;">
                                        Logado como
                                    </p>
                                    <p style="font-size:.84rem;font-weight:700;color:var(--color-navy);
                                              margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                                              max-width:200px;">
                                        {{ $displayName ?? 'Candidato' }}
                                    </p>
                                </div>
                            </li>

                            {{-- Alterar senha → abre modal --}}
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                   href="#"
                                   data-bs-toggle="modal"
                                   data-bs-target="#changePasswordModal">
                                    <i class="bi bi-lock" style="color:var(--color-teal);"></i>
                                    <span>Alterar Senha</span>
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            {{-- Logout --}}
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button class="dropdown-item text-danger d-flex align-items-center gap-2"
                                            type="submit">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Sair</span>
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </li>

                @else

                    {{-- Usuário não autenticado --}}
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn-nav-cta" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                        </a>
                    </li>

                @endauth

            </ul>
        </div>

    </div>
</nav>