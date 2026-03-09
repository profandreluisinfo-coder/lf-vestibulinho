@extends('layouts.auth.master')

@push('metas')
    @if (app()->environment('local'))
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif
    <meta name="description" content="Registro de dados de acesso para vistantes">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Registrar Dados de Acesso')

@section('content')

    @include('partials.videos.back-login')

    <main class="auth">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow">
                        <header
                            class="card-header d-flex flex-column justify-content-center align-items-center border-0 pt-4">
                            <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;" aria-hidden="true"></i>
                            <h2 class="h3 text-center">{{ config('app.name') }} {{ $calendar->year }}</h2>
                        </header>
                        <div class="card-body">
                            <h1 class="h4 mb-4 text-center">
                                <span class="d-inline-flex align-items-center title">
                                    <i class="bi bi-person-plus animate__animated animate__fadeIn me-2"></i> Registrar Dados
                                    de Acesso
                                </span>
                            </h1>
                            <div class="mb-3 text-end">
                                <button type="button" id="toggleAllPasswords" class="btn btn-sm btn-link text-decoration-none">
                                    <i class="bi bi-eye"></i> Mostrar senhas
                                </button>
                            </div>
                            <form id="form-register" action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label required">E-mail:</label>
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
                                    <label for="registerPassword" class="form-label required">Senha:</label>
                                    <input type="password" name="password"
                                        class="form-control password-field password-strength-field @error('password') is-invalid @enderror"
                                        id="registerPassword">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="progress mt-2" style="height:6px;">
                                        <div class="progress-bar passwordStrength"></div>
                                    </div>

                                    <small class="text-muted passwordStrengthText"></small>
                                </div>
                                <div class="mb-3">
                                    <label for="registerRepeatPassword" class="form-label required">Repetir senha:</label>
                                    <input type="password" name="password_confirmation" id="registerRepeatPassword"
                                        class="form-control password-field @error('password_confirmation') is-invalid @enderror">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-group col-md-12">
                                        <small class="text-muted">
                                            <b>ATENÇÃO:</b> Sua senha deve conter no <b>mínimo</b> 6 caracteres e no
                                            <b>máximo</b> 8 caracteres, incluindo, <b>pelo menos</b>, uma letra maiúscula,
                                            uma letra minúscula <b>e</b> um número.
                                        </small>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success submit-password" disabled>
                                        <i class="bi bi-person-plus animate__animated animate__fadeIn me-1"></i> Cadastrar
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-center mt-3 gap-2">
                                    <a href="{{ route('login') }}" class="text-decoration-none">Já tenho registro</a>
                                    <a href="{{ route('resend.email') }}" class="text-decoration-none">Não recebeu o e-mail
                                        de confirmação?</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="mini-footer">
        @include('guest.home.mini-footer')
    </footer>

@endsection

@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/ui/auth/toggleAllPasswords.js') }}"></script>
@endpush
