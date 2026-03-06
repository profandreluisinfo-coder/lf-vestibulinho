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
    <link rel="stylesheet" href="{{ asset('assets/css/layouts/app/user.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/user/inscription.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/steps/hsteps.css') }}">
@stack('styles')
@stack('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/rules/registration/reloadif.js') }}"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg shadow-sm fixed-top navbar-dark">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center gap-2 py-0" href="javascript:void(0);">
                <i class="bi bi-mortarboard fs-5"></i>
                <span class="text-light d-none d-md-inline fs-6">{{ config('app.name') }} {{ $calendar?->year }}</span>
                <span class="text-light d-inline d-md-none fs-6">{{ config('app.name') }}</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon text-light"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5"></i>
                                <span class="text-light">{{ auth()->user()->social_name ?? auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="#"
                                        data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="bi bi-lock text-secondary"></i>
                                        <span>Alterar Senha</span>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button class="dropdown-item d-flex align-items-center gap-2" type="submit">
                                            <i class="bi bi-box-arrow-right text-danger"></i>
                                            <span class="text-danger">Sair</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') ?? '/login' }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Entrar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <div class="container border border-0 rounded-3 bg-light p-4">
        <section>
            <article>            
                @include('alerts.toasts')
            </article>
        <section>
            <article>
                <div class="row justify-content-center">
                    <div class="col-central col-12">
                        <div class="text-danger fst-italic mb-1 text-end">
                            <span class="required"></span> Indica um campo obrigatório
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 py-3">
                        <div class="card shadow-lg">
                            <div class="card-header text-white">
                                <h6 class="mb-0">
                                    <span class="badge rounded-pill {{ $bg }} me-2">{{ $step }}</span>{{ $title }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-3 mb-3 p-3">
                                        @include('partials.forms.steps')
                                        @php
                                            $totalSteps = 8;
                                            $completed = 0;

                                            for ($i = 1; $i <= $totalSteps; $i++) {
                                                if (session()->has('step' . $i . '_done')) {
                                                    $completed++;
                                                }
                                            }

                                            $progress = $completed / $totalSteps;
                                        @endphp
                                        @include('partials.forms.hsteps')
                                    </div>
                                    <div class="forms col-md-8 col-lg-9 p-3">
                                        @include('partials.forms.progress-bar')
                                        @yield('forms') <!-- Aqui vai o formulário -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </div>

    {{-- Modal Alterar Senha --}}
    @include('partials.forms.change-password')

    <div class="container small text-muted mt-3 py-2">
        &copy; {{ $currentYear }} ala
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/addons/cleave-phone.br.js"></script>
    <script src="{{ asset('assets/js/registration.js') }}" type="module"></script>
    {{-- @stack('plugins') --}}
    @stack('scripts')
</body>

</html>