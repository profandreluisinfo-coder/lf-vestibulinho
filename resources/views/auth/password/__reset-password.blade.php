@extends('layouts.auth.master')

@push('metas')
    @if (app()->environment('local'))
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif
    <meta name="description" content="Redefinição de senha.">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Redefinir Senha')

@section('content')

    @include('partials.videos.back-login')

    <main class="auth">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <article class="card shadow-sm">
                        <header
                            class="card-header d-flex flex-column justify-content-center align-items-center border-0 pt-4">
                            <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;"></i>
                            <h2 class="h3 text-center">{{ config('app.name') }} {{ $calendar->year }}</h2>
                        </header>
                        <div class="card-body">
                            <h1 class="h4 mb-4 text-center">
                                <span class="d-inline-flex align-items-center title">
                                    <i class="bi bi-shield-lock me-2" aria-hidden="true"></i>
                                    Redefinir Senha
                                </span>
                            </h1>
                            <div>
                                <p class="text-muted text-center">
                                    Para redefinir sua senha, preencha o formulário abaixo:
                                </p>
                            </div>
                            <div class="mb-3 text-end">
                                <button type="button" id="toggleAllPasswords" class="btn btn-sm btn-link text-decoration-none">
                                    <i class="bi bi-eye"></i> Mostrar senhas
                                </button>
                            </div>
                            <form id="reset-password" action="{{ route('reset.password.action') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="mb-3">
                                    <label for="password" class="form-label required">Nova Senha</label>
                                    <input type="password" name="password" class="form-control password-field" id="password">
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label required">Repetir Senha</label>
                                    <input type="password" name="password_confirmation" class="form-control password-field"
                                        id="password_confirmation">
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <b>ATENÇÃO:</b> Sua senha deve conter no <b>mínimo</b> 6 caracteres e no
                                        <b>máximo</b> 8 caracteres, incluindo, <b>pelo menos</b>, uma letra maiúscula,
                                        uma letra minúscula <b>e</b> um número.
                                    </small>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save me-2"></i> Redefinir Senha
                                    </button>
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="{{ route('login') }}" class="text-decoration-none">Lembrei minha senha</a>
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
    <script src="{{ asset('assets/js/ui/auth/toggleAllPasswords.js') }}"></script>
@endpush