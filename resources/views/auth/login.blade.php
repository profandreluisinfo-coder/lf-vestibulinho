<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Área de acesso exclusivo para candidatos." />
    <title>Área do Candidato — Vestibulinho 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}" />
</head>

<body>

    <div class="page-wrapper">

        <!-- ══════════════ PAINEL ESQUERDO ══════════════ -->
        <aside class="panel-left">
            <div class="deco-circle deco-c1"></div>
            <div class="deco-circle deco-c2"></div>
            <div class="deco-circle deco-c3"></div>
            <div class="deco-blob"></div>

            <!-- Brand -->
            <div class="panel-brand">
                <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <h2>EM Dr. Leandro Franceschini</h2>
                <p>Vestibulinho {{ config('app.year') }} · Cursos Técnicos Gratuitos</p>
            </div>

            <!-- Centro -->
            <div class="panel-center">
                <p class="headline">
                    Bem-vindo de<br>volta à sua<br><em>Área do Candidato</em>.
                </p>
                <p class="lead-text">
                    Acompanhe sua inscrição, consulte resultados, locais de prova e convocações — tudo em um só lugar.
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
                © {{ $currentYear }} EM Dr. Leandro Franceschini · Todos os direitos reservados
            </div>
        </aside>

        <!-- ══════════════ PAINEL DIREITO ══════════════ -->
        <main class="panel-right">
            <!-- ═══════════════════════ ALERTAS ══════════════════════════ -->
            @include('alerts.toasts')

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
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <a href="{{ route('home') }}" class="back-link">
                            <i class="bi bi-arrow-left"></i> Voltar ao site
                        </a>
                        <div class="form-badge">
                            <i class="bi bi-person-lock"></i> Área do Candidato
                        </div>
                    </div>

                    <!-- Título -->
                    <h1>Vestibulinho<br>{{ config('app.year') }}</h1>
                    <p>Informe seus dados de acesso para continuar.</p>
                </div>

                <!-- Alerta de erro (hidden por padrão) -->
                <div class="alert-error hidden" id="alertError">
                    <i class="bi bi-exclamation-circle-fill" style="font-size:1rem;flex-shrink:0;"></i>
                    <span id="alertMsg">E-mail ou senha incorretos. Verifique e tente novamente.</span>
                </div>

                <form method="POST" action="{{ route('login') }}"
                    style="display:flex;flex-direction:column;gap:1.1rem;">

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
                    <div class="form-links">
                        <div class="divider-or">ou</div>
                        <a href="{{ route('register') }}" class="link-register">
                            <i class="bi bi-person-plus-fill"></i> Ainda não tem registro? Cadastre-se
                        </a>
                    </div>

                    <!--</div> /fields -->
                </form>
            </div><!-- /form-card -->
        </main>

    </div><!-- /page-wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/auth/login.js') }}"></script>
</body>

</html>
