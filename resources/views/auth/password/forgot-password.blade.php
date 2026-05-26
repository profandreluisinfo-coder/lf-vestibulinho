<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Recuperação de senha." />
    <title>Recuperar Senha — Vestibulinho {{ $calendar?->year }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/pass/forgot.css') }}" />
    
</head>

<body>

    <div class="page-wrapper">

        <!-- ═══════════════════ PAINEL ESQUERDO ═══════════════════════ -->
        <aside class="panel-left">
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
                    Não se preocupe.<br>Recuperar sua<br><em>senha é simples</em>.
                </p>
                <p class="lead-text">
                    Siga os três passos ao lado e você estará de volta à sua Área do Candidato em instantes.
                </p>

                <!-- Steps de recuperação -->
                <div class="recovery-steps">
                    <div class="rs-item">
                        <div class="rs-node active">1</div>
                        <div class="rs-body">
                            <strong>Informe seu e-mail</strong>
                            <span>Insira o endereço cadastrado no Vestibulinho.</span>
                        </div>
                    </div>
                    <div class="rs-item">
                        <div class="rs-node pending">2</div>
                        <div class="rs-body">
                            <strong>Verifique sua caixa de entrada</strong>
                            <span>Enviaremos um link seguro para redefinição.</span>
                        </div>
                    </div>
                    <div class="rs-item">
                        <div class="rs-node pending">3</div>
                        <div class="rs-body">
                            <strong>Crie uma nova senha</strong>
                            <span>Acesse o link recebido e defina sua nova senha.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rodapé -->
            <div class="panel-footer">
                © {{ $currentYear }} EM Dr. Leandro Franceschini · Todos os direitos reservados
            </div>
        </aside>

        <!-- ═══════════════════ PAINEL DIREITO ════════════════════════ -->
        <main class="panel-right">
            <!-- ═══════════════════════ ALERTAS ══════════════════════════ -->
            @include('alerts.toasts')

            <div class="form-card" style="position:relative;">

                <!-- ─── VIEW 1: Formulário ──────────────────────────── -->
                <div class="view" id="viewForm">

                    <div class="form-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('login') }}" class="back-link me-2">
                                <i class="bi bi-arrow-left"></i> Voltar ao login
                            </a>                        
                            <div class="form-badge">
                                <i class="bi bi-key-fill"></i> Recuperação de Senha
                            </div>
                        </div>
                        <h1>Esqueceu<br>sua senha?</h1>
                        <p>Sem problema. Informe seu e-mail de cadastro e enviaremos as instruções de recuperação.</p>
                    </div>

                    <!-- Caixa informativa -->
                    <div class="info-box">
                        <div class="info-icon"><i class="bi bi-info-lg"></i></div>
                        <span>
                            Use o mesmo e-mail informado durante o seu registro no Vestibulinho
                            {{ $calendar?->year }}.
                            Verifique também a pasta de <strong>spam</strong> caso não encontre o e-mail.
                        </span>
                    </div>

                    <form id="forgot-password" action="{{ route('forgot.password') }}" method="POST">
                        @csrf

                        <!-- Campo e-mail -->
                        <div style="margin-bottom:1.2rem;">
                            <label class="field-label" for="forgotEmail">
                                Endereço de e-mail <span style="color:#e74c3c;font-size:.7rem;">*</span>
                            </label>
                            <div class="field-wrap">
                                <input type="email" name="email" id="forgotEmail"
                                    class="form-input @error('email') is-invalid @enderror" placeholder="seu@email.com"
                                    autocomplete="email" oninput="onEmailInput()" />
                                <i class="bi bi-envelope-fill field-icon"></i>
                            </div>
                            <div class="field-msg" id="msgEmail"></div>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Botão -->
                        <button class="btn-recover" type="submit">
                            <i class="bi bi-key-fill"></i> Recuperar Senha
                        </button>

                        <!-- Link voltar -->
                        <div class="form-links">
                            <a href="{{ route('login') }}" class="link-back-login">
                                <i class="bi bi-arrow-left"></i> Lembrei minha senha
                            </a>
                        </div>

                    </form>

                </div><!-- /viewForm -->

                <!-- ─── VIEW 2: Sucesso / E-mail enviado ────────────── -->
                <div class="view hidden" id="viewSuccess">
                    <div class="success-card">

                        <div class="success-ring">
                            <i class="bi bi-envelope-check-fill"></i>
                        </div>

                        <h2>E-mail enviado!</h2>
                        <p>
                            Enviamos as instruções de recuperação para o endereço:
                        </p>
                        <div class="email-preview" id="emailPreview">
                            <i class="bi bi-envelope-fill" style="color:var(--teal);flex-shrink:0;"></i>
                            <span id="emailPreviewText">—</span>
                        </div>
                        <p style="margin-top:.9rem;">
                            Acesse o link no e-mail para criar uma nova senha.<br>
                            O link expira em <strong>60 minutos</strong>.
                        </p>

                        <!-- Reenvio com timer -->
                        <div class="resend-area">
                            <p class="resend-label">Não recebeu? Aguarde antes de reenviar:</p>
                            <div class="timer-bar-track">
                                <div class="timer-bar-fill" id="timerBar"></div>
                            </div>
                            <button class="btn-resend" id="btnResend" onclick="doResend(this)" disabled>
                                <i class="bi bi-arrow-clockwise"></i>
                                <span id="resendLabel">Reenviar em <span id="countdown">60</span>s</span>
                            </button>
                        </div>

                        <a href="{{ route('login') }}" class="btn-back-login">
                            <i class="bi bi-box-arrow-in-right"></i> Ir para o Login
                        </a>

                    </div>
                </div><!-- /viewSuccess -->

            </div><!-- /form-card -->
        </main>

    </div><!-- /page-wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/pass/forgot.js') }}"></script>
</body>

</html>