<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @if (app()->environment('local'))
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif
    <meta name="description" content="Reenvio de e-mail de verificação.">
    <title>{{ config('app.name') }} {{ $calendar->year }} | Reenviar E-mail de Verificação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/email/resend.css') }}" />
</head>

<body>

    <div class="page-wrapper">

        @include('alerts.toasts')

        <!-- ══════════ PAINEL ESQUERDO ══════════ -->
        <aside class="panel-left">
            <div class="deco-circle deco-c1"></div>
            <div class="deco-circle deco-c2"></div>
            <div class="deco-circle deco-c3"></div>
            <div class="deco-blob"></div>

            <!-- Brand -->
            <div class="panel-brand">
                <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <h2>EM Dr. Leandro Franceschini</h2>
                <p>Vestibulinho {{ $calendar->year }} · Cursos Técnicos Gratuitos</p>
            </div>

            <!-- Conteúdo central -->
            <div class="panel-center">
                <p class="headline">
                    Não recebeu<br>seu e-mail de<br><em>verificação</em>?
                </p>
                <p class="lead-text">
                    Sem problemas. Informe seu endereço de e-mail abaixo e reenviaremos o link de confirmação para você.
                </p>

                <div class="panel-steps">
                    <div class="panel-step">
                        <div class="step-dot done"><i class="bi bi-check-lg"></i></div>
                        <span><strong>Cadastro</strong> realizado com sucesso</span>
                    </div>
                    <div class="panel-step">
                        <div class="step-dot" style="border-color:var(--amber);color:var(--amber);">2</div>
                        <span><strong>Reenviar</strong> o e-mail de verificação</span>
                    </div>
                    <div class="panel-step">
                        <div class="step-dot">3</div>
                        <span>Confirmar e-mail e <strong>acessar</strong> a Área do Candidato</span>
                    </div>
                </div>
            </div>

            <!-- Rodapé -->
            <div class="panel-footer">
                © {{ $currentYear ?? date('Y') }} EM Dr. Francisco de Souza · Todos os direitos reservados
            </div>
        </aside>

        <!-- ══════════ PAINEL DIREITO (formulário) ══════════ -->
        <main class="panel-right">
            <div class="form-card">

                <!-- ─── VIEW 1: Formulário ──────────────────────────── -->
                <div class="view" id="viewForm">

                    <div class="form-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('register') }}" class="back-link">
                                <i class="bi bi-arrow-left"></i> Voltar ao registro
                            </a>                        
                            <div class="form-badge">
                                <i class="bi bi-envelope-check-fill"></i> Verificação de E-mail
                            </div>
                        </div>
                        <h1>Reenviar E-mail<br>de Verificação</h1>
                        <p>Informe o endereço cadastrado e enviaremos um novo link de confirmação.</p>
                    </div>

                    <!-- Caixa informativa -->
                    <div class="info-box">
                        <div class="info-icon"><i class="bi bi-info-lg"></i></div>
                        <span>
                            Use o mesmo e-mail informado durante o seu registro no Vestibulinho
                            {{ $calendar->year ?? config('app.year') }}.
                            Verifique também a pasta de <strong>spam</strong> caso não encontre o e-mail.
                        </span>
                    </div>

                    <form id="resend-email" method="POST" action="{{ route('resend.email') }}"
                        style="display:flex;flex-direction:column;gap:1.1rem;">

                        @csrf

                        <!-- E-mail -->
                        <div>
                            <label class="field-label" for="myEmail">
                                Endereço de e-mail <span style="color:#e74c3c;font-size:.7rem;">*</span>
                            </label>
                            <div class="field-wrap">
                                <input
                                    type="email"
                                    name="email"
                                    id="myEmail"
                                    class="form-input @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="seu@email.com"
                                    autocomplete="email"
                                    oninput="onEmailInput()"
                                    aria-describedby="@error('email') emailError @enderror"
                                />
                                <i class="bi bi-envelope-fill field-icon"></i>
                            </div>
                            <div class="field-msg" id="msgEmail"></div>
                            @error('email')
                                <div id="emailError" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Botão submit -->
                        <button type="submit" class="btn-send" id="btnSubmit">
                            <i class="bi bi-envelope-check"></i> Reenviar E-mail de Verificação
                        </button>

                        <!-- Links inferiores -->
                        <div class="form-links">
                            <a href="{{ route('login') }}" class="link-btn">
                                <i class="bi bi-box-arrow-in-right"></i> Já validei meu e-mail
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
                            Enviamos o link de verificação para o endereço:
                        </p>
                        <div class="email-preview" id="emailPreview">
                            <i class="bi bi-envelope-fill" style="color:var(--teal);flex-shrink:0;"></i>
                            <span id="emailPreviewText">—</span>
                        </div>
                        <p style="margin-top:.9rem;">
                            Acesse o link no e-mail para confirmar sua conta.<br>
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
    <script src="{{ asset('assets/js/email/resend.js') }}"></script>
</body>

</html>