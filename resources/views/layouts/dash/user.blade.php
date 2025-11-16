<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('metas')

    <title>@yield('page-title', 'Vestibulinho LF')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    @stack('datatable-styles')

    <link rel="stylesheet" href="{{ asset('assets/css/layouts/app/user.css') }}">

    @stack('styles')

    @stack('head-scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    {{-- CONTEÚDO PRINCIPAL --}}
    <div class="container">

        {{-- Topbar --}}
        <nav
            class="navbar navbar-light bg-light shadow-sm px-3 d-flex flex-column flex-sm-row justify-content-between align-items-center fixed-top">
            <span class="navbar-text d-flex justify-content-center align-items-center gap-2 p-0">
                {{-- <img src="{{ asset('assets/img/logo.webp') }}" alt="Avatar Logo" style="width:50px;" class="img-fluid"> --}}
                <i class="bi bi-mortarboard me-2 fs-4"></i>
                <h5 class="m-0 text-secondary">{{ config('app.name') }} {{ $calendar?->year }}</h5>
            </span>

            @auth
                <div class="dropdown mt-2 mt-sm-0">
                    <a class="text-decoration-none text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <span class="text-muted">{{ auth()->user()->social_name ?? auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">
                                <i class="bi bi-key me-2"></i> Alterar Senha
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">@csrf
                                <button class="dropdown-item" type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </nav>

        {{-- Conteúdo da Dashboard --}}
        <div class="container">
            @auth
                @yield('dash-content')
                {{-- Modal Alterar Senha --}}
                <div class="modal fade" id="changePasswordModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
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
                                        <input type="password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="currentPassword" placeholder="••••••••">
                                        @error('current_password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">Nova senha</label>
                                        <input type="password" name="new_password"
                                            class="form-control @error('new_password') is-invalid @enderror"
                                            id="newPassword" placeholder="••••••••">
                                        @error('new_password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="repeatPassword" class="form-label">Repetir senha</label>
                                        <input type="password" name="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="repeatPassword" placeholder="••••••••">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-circle me-2"></i> Alterar
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    {{-- JS --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
    <script src="{{ asset('assets/auth/change-password.js') }}"></script>

    @stack('plugins')

    @include('partials.alerts.users')

    @stack('scripts')
</body>

</html>
