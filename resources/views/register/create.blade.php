@extends('layouts.auth')

@section('title', 'Registrar Dados de Acesso — Vestibulinho ' . $calendar?->year)

@section('meta_description', 'Área de acesso exclusivo para candidatos.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth/register.css') }}">
@endpush

@section('left-panel')

    <div class="deco-circle deco-c1"></div>
    <div class="deco-circle deco-c2"></div>
    <div class="deco-circle deco-c3"></div>
    <div class="deco-blob"></div>

    <!-- Brand -->
    <div class="panel-brand">
        <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <h2>EM Dr. Leandro Franceschini</h2>
        <p>Vestibulinho {{ $calendar?->year }} · Cursos Técnicos Gratuitos</p>
    </div>

    <!-- Conteúdo central -->
    <div class="panel-center">
        <p class="headline">
            Sua jornada<br>técnica começa<br>com um <em>cadastro</em>.
        </p>
        <p class="lead-text">
            Registre seus dados de acesso para acompanhar todas as etapas do processo seletivo em um só lugar.
        </p>

        <div class="panel-steps">
            <div class="panel-step">
                <div class="step-dot done"><i class="bi bi-check-lg"></i></div>
                <span><strong>Criando</strong> seus dados de acesso</span>
            </div>
            <div class="panel-step">
                <div class="step-dot" style="border-color:var(--amber);color:var(--amber);">2</div>
                <span>Acessar a <strong>Área do Candidato</strong> e preencher o formulário de inscrição</span>
            </div>
            <div class="panel-step">
                <div class="step-dot">3</div>
                <span>Confirmar inscrição e <strong>aguardar prova</strong></span>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="panel-footer">
        © {{ $currentYear }} EM Dr. Francisco de Souza · Todos os direitos reservados
    </div>

@endsection

@section('right-panel')

    <div class="form-card">

        <!-- Topo -->
        <div class="form-top">
            <a href="{{ route('home') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Voltar ao site
            </a>
            <h1>Registrar<br>Dados de Acesso</h1>
            <p>Crie seu acesso para acompanhar o Vestibulinho 2025 pela Área do Candidato.</p>
        </div>

        <!-- Toggle senhas -->
        <div style="display:flex;justify-content:flex-end;margin-bottom:1.2rem;">
            <button class="toggle-pwd-btn" id="toggleAllBtn" onclick="toggleAllPwd()">
                <i class="bi bi-eye" id="toggleAllIcon"></i>
                <span id="toggleAllLabel">Mostrar senhas</span>
            </button>
        </div>

        <!-- Formulário -->
        <form method="POST" action="{{ route('register') }}"
            style="display:flex;flex-direction:column;gap:1.1rem;">

            @csrf
            <!-- E-mail -->
            <div>
                <div class="field-label">
                    E-mail <span class="req">*</span>
                </div>
                <div class="field-wrap">
                    <input type="email" name="email" id="regEmail"
                        class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}"
                        placeholder="seu@email.com" oninput="validateEmail()" />
                    <i class="bi bi-envelope-fill field-icon"></i>
                </div>
                <div class="field-msg" id="msgEmail"></div>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Senha -->
            <div>
                <div class="field-label">
                    Senha <span class="req">*</span>
                </div>
                <div class="field-wrap">
                    <input type="password" name="password" id="regPwd"
                        class="form-input pwd-input @error('password') is-invalid @enderror"
                        value="{{ old('password') }}" placeholder="Crie sua senha" style="padding-right:2.8rem;"
                        oninput="onPwdInput()" />
                    <i class="bi bi-lock-fill field-icon"></i>
                    <button class="eye-btn" type="button" onclick="toggleEye('regPwd','eyePwd1')">
                        <i class="bi bi-eye-fill" id="eyePwd1"></i>
                    </button>
                </div>
                <!-- Barra de força -->
                <div class="strength-wrap">
                    <div class="strength-track">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="strength-label" id="strengthLabel"></span>
                </div>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Repetir Senha -->
            <div>
                <div class="field-label">
                    Repetir Senha <span class="req">*</span>
                </div>
                <div class="field-wrap">
                    <input type="password" name="password_confirmation" id="regConfirm"
                        class="form-input pwd-input @error('password_confirmation') is-invalid @enderror" value="{{ old('password_confirmation') }}"
                        placeholder="Repita sua senha" style="padding-right:2.8rem;"
                        oninput="validateConfirm()" />
                    <i class="bi bi-shield-lock-fill field-icon"></i>
                    <button class="eye-btn" type="button" onclick="toggleEye('regConfirm','eyePwd2')">
                        <i class="bi bi-eye-fill" id="eyePwd2"></i>
                    </button>
                </div>
                <div class="field-msg" id="msgConfirm"></div>
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Regras + Checklist -->
            <div class="rules-box">
                <p class="rules-note">
                    <strong style="color:var(--navy);">ATENÇÃO:</strong> Sua senha deve ter no
                    <strong>mínimo 6</strong> e no <strong>máximo 8</strong> caracteres, contendo
                    <strong>pelo menos</strong> uma letra maiúscula, uma minúscula <strong>e</strong> um número. <strong>Não utilize caracteres especiais (@, #, $, *, etc)<strong>.
                </p>
                <div class="rules-grid">
                    <div class="rule-chip" id="rule-len">
                        <div class="ri" id="ri-len"><i class="bi bi-dash"></i></div>
                        6 a 8 caracteres
                    </div>
                    <div class="rule-chip" id="rule-upper">
                        <div class="ri" id="ri-upper"><i class="bi bi-dash"></i></div>
                        Letra maiúscula
                    </div>
                    <div class="rule-chip" id="rule-lower">
                        <div class="ri" id="ri-lower"><i class="bi bi-dash"></i></div>
                        Letra minúscula
                    </div>
                    <div class="rule-chip" id="rule-num">
                        <div class="ri" id="ri-num"><i class="bi bi-dash"></i></div>
                        Número
                    </div>
                </div>
            </div>

            <!-- Botão submit -->
            <button class="btn-register" id="btnSubmit" type="submit" disabled>
                <i class="bi bi-person-plus-fill"></i> Cadastrar
            </button>

            <!-- Links inferiores -->
            @if ($open && $show)

                <div class="form-links">
                    <a href="{{ route('login') }}" class="link-btn">
                        <i class="bi bi-box-arrow-in-right"></i> Já tenho registro
                    </a>
                    <a href="{{ route('resend.email') }}" class="link-muted">
                        <i class="bi bi-envelope"></i> Não recebeu o e-mail de verificação?
                    </a>
                </div>

            @endif
            

        </form><!-- /form -->
    </div><!-- /form-card -->

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/auth/register.js') }}"></script>
@endpush