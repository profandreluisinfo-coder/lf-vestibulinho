@extends('layouts.auth')

@section('title', 'Área do Candidato — Vestibulinho ' . $calendar?->year)

@section('meta_description', 'Área de acesso exclusivo para candidatos.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}">
@endpush

@section('left-panel')

    <div class="deco-circle deco-c1"></div>
    <div class="deco-circle deco-c2"></div>
    <div class="deco-circle deco-c3"></div>
    <div class="deco-blob"></div>

    <!-- Brand -->
    <div class="panel-brand">
        <div class="brand-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>

        <h2>EM Dr. Leandro Franceschini</h2>

        <p>
            Vestibulinho {{ $calendar?->year }}
            · Cursos Técnicos Gratuitos
        </p>
    </div>

    <!-- Centro -->
    <div class="panel-center">

        <p class="headline">
            Bem-vindo de<br>
            volta à sua<br>
            <em>Área do Candidato</em>.
        </p>

        <p class="lead-text">
            Acompanhe sua inscrição, consulte resultados,
            locais de prova e convocações.
        </p>

        <!-- Card de segurança -->
        <div class="security-card">
            <h6><i class="bi bi-shield-fill-check"></i> Acesso seguro</h6>
            <div class="sec-item">
                <div class="sec-icon"><i class="bi bi-lock-fill"></i></div>
                <p>Sua conexão é protegida. Nunca compartilhe sua senha com terceiros.</p>
            </div>
            <div class="sec-item">
                <div class="sec-icon"><i class="bi bi-envelope-check-fill"></i></div>
                <p>Use o mesmo e-mail informado no momento do cadastro.</p>
            </div>
            <div class="sec-item">
                <div class="sec-icon"><i class="bi bi-headset"></i></div>
                <p>Dificuldades de acesso? Contate a secretaria da escola.</p>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="panel-footer">
        © {{ $currentYear }}
        EM Dr. Leandro Franceschini
    </div>

@endsection

@section('right-panel')

    @php
        $open = $calendar?->isInscriptionOpen() ? true : false;
    @endphp

    <!-- Overlay de sucesso -->
    <div class="success-overlay" id="successOverlay">
        <div class="success-icon"><i class="bi bi-check-lg"></i></div>
        <h3>Acesso confirmado!</h3>
        <p>Redirecionando para sua Área do Candidato…</p>
        <div class="spinner-border text-success mt-1" style="width:1.4rem;height:1.4rem;border-width:2px;"
            role="status"></div>
    </div>

    <div class="form-card">
        <!-- Topo -->
        <div class="form-top">
            <div class="d-flex flex-column flex-md-row justify-content-between">
                <a href="{{ route('home') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i> Voltar ao site
                </a>
                <div class="form-badge">
                    <i class="bi bi-person-lock"></i> Área do Candidato
                </div>
            </div>

            <!-- Título -->
            <h1>Vestibulinho<br>{{ $calendar?->year }}</h1>
            <p>Se você já registrou seus dados de acesso, informe seu e-mail e senha para continuar.</p>
        </div>

        <!-- Alerta de erro (hidden por padrão) -->
        <div class="alert-error hidden" id="alertError">
            <i class="bi bi-exclamation-circle-fill" style="font-size:1rem;flex-shrink:0;"></i>
            <span id="alertMsg">E-mail ou senha incorretos. Verifique e tente novamente.</span>
        </div>

        <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1.1rem;">

            @csrf
            <!-- Campos -->
            <!-- E-mail -->
            <div>
                <div class="field-label">
                    E-mail <span class="req">*</span>
                </div>
                <div class="field-wrap">
                    <input type="email" id="loginEmail" name="email"
                        class="form-input @error('email') is-invalid @enderror" placeholder="seu@email.com"
                        autocomplete="email" oninput="clearError(this)" />
                    <i class="bi bi-envelope-fill field-icon"></i>
                    @error('email')
                        <div id="emailError" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Senha -->
            <div>
                <div class="field-label">
                    Senha <span class="req">*</span>
                </div>
                <div class="field-wrap">
                    <input type="password" id="loginPassword" name="password"
                        class="form-input @error('password') is-invalid @enderror" placeholder="Sua senha"
                        style="padding-right:2.8rem;" autocomplete="current-password"
                        oninput="clearError(this)" />
                    <i class="bi bi-lock-fill field-icon"></i>
                    <button class="eye-btn" type="button" onclick="toggleEye('loginPassword','eyeLogin')"
                        aria-label="Mostrar senha">
                        <i class="bi bi-eye-fill" id="eyeLogin"></i>
                    </button>
                    @error('password')
                        <div id="passwordError" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Lembrar / Esqueceu -->
            <div class="row-remember">
                <label class="custom-check" for="rememberMe">
                    <input type="checkbox" id="rememberMe" name="remember" />
                    <div class="check-box"><i class="bi bi-check-lg"></i></div>
                    <span class="check-label">Lembrar de mim</span>
                </label>
                <a href="{{ route('forgot.password') }}" class="forgot-link">Esqueceu a senha?</a>
            </div>
            
            <!-- Botão entrar -->
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Entrar
            </button>

            <!-- Links -->
            @if ($open)
            <div class="form-links">
                <div class="divider-or">ou</div>
                <a href="{{ route('register') }}" class="link-register">
                    <i class="bi bi-person-plus-fill"></i> Ainda não tem registro?
                </a>
            </div>
            @endif
        </form>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/auth/login.js') }}"></script>
@endpush