@extends('layouts.home.master')

@push('metas')
    @if (app()->environment('local'))
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif
    <meta name="description"
        content="Área de acesso exclusivo para administradores do {{ config('app.name') }} {{ $calendar->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Painel Administrativo')

@section('content')

    @include('partials.videos.back-login')

    <main class="auth mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <article class="card shadow-sm">
                        <header
                            class="card-header d-flex flex-column justify-content-center align-items-center border-0 pt-4">
                            <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;" aria-hidden="true"></i>
                            <h2 class="h4 text-center">{{ config('app.name') }} {{ $calendar->year }}</h2>
                        </header>

                        <div class="card-body">
                            <h1 class="h4 mb-4 text-center">
                                <span class="d-inline-flex align-items-center title">
                                    <i class="bi bi-person-gear me-2" aria-hidden="true"></i>
                                    Painel Administrativo
                                </span>
                            </h1>

                            <div>
                                <p class="text-center">Informe seus dados de acesso:</p>
                            </div>

                            @include('components.alerts')

                            <form id="form-login" method="POST" action="{{ route('login') }}" autocomplete="off">
                                @csrf
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label">E-mail</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror" id="loginEmail"
                                        value="{{ old('email') }}" required
                                        autocomplete="username" aria-describedby="@error('email') emailError @enderror">
                                    @error('email')
                                        <div id="emailError" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label">Senha</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="loginPassword"
                                        required autocomplete="current-password"
                                        aria-describedby="@error('password') passwordError @enderror">
                                    @error('password')
                                        <div id="passwordError" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                                    <label class="form-check-label" for="rememberMe">Lembrar de mim</label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                                        Entrar
                                    </button>
                                </div>
                            </form>

                        </div>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <footer class="mini-footer mt-auto">
        @include('guest.home.mini-footer')
    </footer>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/auth/login.js') }}"></script>
@endpush