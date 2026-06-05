@extends('layouts.auth')

@section('title', 'Redefinir Senha — Vestibulinho {{ $calendar?->year }}')

@section('meta_description', 'Área de redefinição de senha exclusiva para candidatos.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pass/reset.css') }}">
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

    <!-- Centro -->
    <div class="panel-center">
        <p class="headline">
            Quase lá!<br>Crie uma senha<br><em>nova e segura</em>.
        </p>
        <p class="lead-text">
            Você está a um passo de recuperar o acesso à sua Área do Candidato. Escolha uma senha forte.
        </p>

        <!-- Dicas de senha segura -->
        <div class="tips-card">
            <h6><i class="bi bi-lightbulb-fill"></i> Dicas para uma senha segura</h6>
            <div class="tip-item">
                <div class="tip-icon"><i class="bi bi-check-lg"></i></div>
                <p>Misture letras <strong style="color:#fff;">maiúsculas</strong> e <strong
                        style="color:#fff;">minúsculas</strong> para aumentar a complexidade.</p>
            </div>
            <div class="tip-item">
                <div class="tip-icon"><i class="bi bi-check-lg"></i></div>
                <p>Inclua pelo menos um <strong style="color:#fff;">número</strong> na senha.</p>
            </div>
            <div class="tip-item">
                <div class="tip-icon"><i class="bi bi-x-lg" style="color:#f87171;"></i></div>
                <p>Evite sequências óbvias como <strong style="color:#f87171;">123456</strong> ou seu próprio
                    nome.</p>
            </div>
        </div>

        <!-- Progresso de etapas -->
        <div class="step-progress">
            <div class="sp-step">
                <div class="sp-dot done"><i class="bi bi-check-lg"></i></div>
                <span>Solicitou</span>
            </div>
            <div class="sp-line"></div>
            <div class="sp-step">
                <div class="sp-dot done"><i class="bi bi-check-lg"></i></div>
                <span>E-mail recebido</span>
            </div>
            <div class="sp-line"></div>
            <div class="sp-step">
                <div class="sp-dot current"><i class="bi bi-pencil-fill"></i></div>
                <span style="color:#6eefb0;font-weight:600;">Nova senha</span>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <div class="panel-footer">
        © {{ $currentYear }} EM Dr. Leandro Franceschini · Todos os direitos reservados
    </div>

@endsection

@section('right-panel')

    @include('alerts.toasts')

    <div class="form-card">

        <!-- ─── VIEW 1: Formulário ─────────────────────────── -->
        <div class="view" id="viewForm">

            <div class="form-top">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i> Voltar ao login
                </a>
                <div class="form-badge">
                    <i class="bi bi-shield-lock-fill"></i> Redefinir Senha
                </div>
                <h1>Crie sua<br>nova senha</h1>
                <p>Para redefinir sua senha, preencha o formulário abaixo com atenção às regras.</p>
            </div>

            <form method="POST" action="{{ route('reset.password.action') }}" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}" />
                <div id="serverErrors"></div>

                <!-- Toggle mostrar/ocultar senhas -->
                <div style="display:flex;justify-content:flex-end;margin-bottom:1.2rem;">
                    <button class="toggle-pwd-btn" id="toggleAllBtn" onclick="toggleAllPwd()" type="button">
                        <i class="bi bi-eye" id="toggleAllIcon"></i>
                        <span id="toggleAllLabel">Mostrar senhas</span>
                    </button>
                </div>

                <div style="display:flex;flex-direction:column;gap:1.1rem;">

                    <!-- Nova Senha -->
                    <div>
                        <div class="field-label">
                            Nova Senha <span class="req">*</span>
                        </div>
                        <div class="field-wrap">
                            <input type="password" id="newPwd" name="password"
                                class="form-input pwd-input @error('password') input-error @enderror"
                                placeholder="Crie sua nova senha" style="padding-right:2.8rem;"
                                oninput="onPwdInput()" />
                            <i class="bi bi-lock-fill field-icon"></i>
                            <button class="eye-btn" type="button" onclick="toggleEye('newPwd','eye1')"
                                aria-label="Mostrar senha">
                                <i class="bi bi-eye-fill" id="eye1"></i>
                            </button>
                        </div>
                        <!-- Barra de força -->
                        <div class="strength-wrap">
                            <div class="strength-track">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-label" id="strengthLabel"></span>
                        </div>
                    </div>

                    <!-- Repetir Senha -->
                    <div>
                        <div class="field-label">
                            Repetir Senha <span class="req">*</span>
                        </div>
                        <div class="field-wrap">
                            <input type="password" id="confirmPwd" name="password_confirmation"
                                class="form-input pwd-input @error('password_confirmation') input-error @enderror"
                                placeholder="Repita a nova senha" style="padding-right:2.8rem;"
                                oninput="validateConfirm()" />
                            <i class="bi bi-shield-lock-fill field-icon"></i>
                            <button class="eye-btn" type="button" onclick="toggleEye('confirmPwd','eye2')"
                                aria-label="Mostrar senha">
                                <i class="bi bi-eye-fill" id="eye2"></i>
                            </button>
                        </div>
                        <div class="field-msg" id="msgConfirm"></div>
                    </div>

                    <!-- Regras + Checklist -->
                    <div class="rules-box">
                        <p class="rules-note">
                            <strong style="color:var(--navy);">ATENÇÃO:</strong> Sua senha deve ter no
                            <strong>mínimo 6</strong> e no <strong>máximo 8</strong> caracteres, incluindo
                            <strong>pelo menos</strong> uma letra maiúscula, uma minúscula
                            <strong>e</strong> um número. <strong>Não utilize caracteres especiais (@, #, $, *)</strong>
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
                            <div class="rule-chip" id="rule-noSpecial">
                                <div class="ri" id="ri-noSpecial"><i class="bi bi-dash"></i></div>
                                Sem caracteres especiais
                            </div>
                        </div>
                    </div>

                    <!-- Botão -->
                    <button class="btn-reset" id="btnSubmit" type="submit" disabled>
                        <i class="bi bi-save-fill"></i> Redefinir Senha
                    </button>

                    <!-- Link -->
                    <div class="form-links">
                        <a href="{{ route('login') }}" class="link-back">
                            <i class="bi bi-arrow-left"></i> Lembrei minha senha
                        </a>
                    </div>

                </div>
            </form>
        </div><!-- /viewForm -->

        <!-- ─── VIEW 2: Sucesso ────────────────────────────── -->
        {{-- <div class="view @unless (session('success')) hidden @endunless" id="viewSuccess"> --}}<div class="view hidden" id="viewSuccess">
            <div class="success-card">
                <div class="success-ring">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
                <h2>Senha redefinida!</h2>
                <p>
                    Sua nova senha foi salva com sucesso. Agora você já pode acessar sua Área do Candidato.
                </p>
                <a href="{{ route('login') }}" class="btn-go-login">
                    <i class="bi bi-box-arrow-in-right"></i> Ir para o Login
                </a>
            </div>
        </div><!-- /viewSuccess -->

    </div><!-- /form-card -->

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/pass/reset.js') }}"></script>
@endpush