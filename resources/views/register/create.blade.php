@extends('layouts.home.master')

@push('metas')
    @if (app()->environment('local'))
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif
    <meta name="description"
        content="Registro de acesso para candidatos do {{ config('app.name') }} {{ $calendar->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Registrar Dados de Acesso')

@section('content')

    @include('partials.videos.back-login')

    <main class="auth mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow">
                        <header
                            class="card-header d-flex flex-column justify-content-center align-items-center border-0 pt-4">
                            <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;" aria-hidden="true"></i>
                            <h2 class="h3 text-center">{{ config('app.name') }} {{ $calendar->year }}</h2>
                        </header>
                        <div class="card-body">

                            <h1 class="h4 mb-4 text-center">
                                <span class="d-inline-flex align-items-center title">
                                    <i class="bi bi-person-plus animate__animated animate__fadeIn me-2"></i> Registrar Dados de Acesso
                                </span>
                            </h1>
                        @include('components.alerts')
                            <div>
                                <p class="text-muted text-center">
                                    Para registrar seus dados de acesso, preencha o formulário abaixo:
                                </p>
                            </div>

                            <form id="form-register" method="POST" action="{{ route('register') }}" autocomplete="off">
                                @csrf
                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label">E-mail</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror" id="registerEmail"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="registerPassword" class="form-label">Senha</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="registerPassword">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="registerRepeatPassword" class="form-label">Repetir senha</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="registerRepeatPassword">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-group col-md-12">
                                        <small class="text-muted">
                                            ATENÇÃO: Sua senha deve conter no mínimo 6 caracteres e no máximo 8 caracteres,
                                            incluindo pelo menos uma letra maiúscula, uma letra minúscula e um número.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" name="terms"
                                        class="form-check-input @error('terms') is-invalid @enderror" id="agreeTerms">
                                    <label class="form-check-label" for="agreeTerms">Li e concordo com os termos do <a
                                            href="#" class="text-decoration-none" target='_blank'>edital</a></label>
                                    @error('terms')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-person-plus animate__animated animate__fadeIn me-1"></i> Cadastrar
                                    </button>
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="{{ route('login') }}" class="text-decoration-none">Já tenho registro</a> |
                                    <a href="{{ route('resend.email') }}" class="text-decoration-none">Não recebeu o e-mail
                                        de
                                        confirmação?</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="mini-footer">
        @include('home.mini-footer')
    </footer>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/auth/register.js') }}"></script>
@endpush
