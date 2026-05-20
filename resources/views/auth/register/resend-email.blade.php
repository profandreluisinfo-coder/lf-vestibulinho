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

    <style>
        /* ─── TOKENS ──────────────────────────────────────────────── */
        :root {
            --navy: #0B1E3D;
            --navy2: #132948;
            --teal: #00A896;
            --teal2: #007F72;
            --amber: #F4A261;
            --amber2: #E07A3A;
            --light: #EEF3FA;
            --muted: #6B7FA3;
            --card-bg: #FFFFFF;
            --shadow: 0 8px 32px rgba(11, 30, 61, .12);
            --shadow-lg: 0 24px 64px rgba(11, 30, 61, .18);
            --radius: 16px;
            --grad-main: linear-gradient(135deg, #0B1E3D 0%, #1B3E72 60%, #0E4D6B 100%);
            --grad-teal: linear-gradient(135deg, #00A896 0%, #007F72 100%);
            --grad-amber: linear-gradient(135deg, #F4A261 0%, #E07A3A 100%);
            --font-head: 'Sora', sans-serif;
            --font-body: 'DM Sans', sans-serif;
        }

        /* ─── BASE ────────────────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: var(--font-body);
            background: var(--light);
            color: var(--navy);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-head);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ─── KEYFRAMES ───────────────────────────────────────────── */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-20px) }
            to   { opacity: 1; transform: translateY(0) }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px) }
            to   { opacity: 1; transform: translateY(0) }
        }

        @keyframes fadeIn {
            from { opacity: 0 }
            to   { opacity: 1 }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) }
            50%       { transform: translateY(-14px) }
        }

        @keyframes gradShift {
            0%   { background-position: 0% 50% }
            50%  { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(.92) }
            to   { opacity: 1; transform: scale(1) }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px) }
            to   { opacity: 1; transform: translateY(0) }
        }

        @keyframes slideRule {
            from { opacity: 0; transform: translateX(-8px) }
            to   { opacity: 1; transform: translateX(0) }
        }

        @keyframes progressFill {
            from { width: 0 }
            to   { width: 100% }
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0, 168, 150, .35) }
            50%       { box-shadow: 0 0 0 10px rgba(0, 168, 150, 0) }
        }

        /* ─── LAYOUT WRAPPER ──────────────────────────────────────── */
        .page-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ─── PAINEL ESQUERDO (decorativo) ───────────────────────── */
        .panel-left {
            background: var(--grad-main);
            background-size: 300% 300%;
            animation: gradShift 14s ease infinite;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
        }

        /* Círculos decorativos */
        .deco-circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .deco-c1 {
            width: 420px;
            height: 420px;
            top: -100px;
            right: -140px;
            border: 1px solid rgba(255, 255, 255, .07);
        }

        .deco-c2 {
            width: 260px;
            height: 260px;
            bottom: -60px;
            left: -60px;
            border: 1px solid rgba(0, 168, 150, .18);
        }

        .deco-c3 {
            width: 150px;
            height: 150px;
            top: 42%;
            right: 10%;
            border: 1px solid rgba(244, 162, 97, .22);
            animation: float 7s ease-in-out infinite;
        }

        .deco-blob {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 168, 150, .18) 0%, transparent 65%);
            width: 500px;
            height: 500px;
            top: 10%;
            right: -120px;
            pointer-events: none;
        }

        /* Logo / Brand no painel */
        .panel-brand {
            position: relative;
            z-index: 2;
            animation: fadeDown .8s ease both;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: var(--grad-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            box-shadow: 0 6px 20px rgba(0, 168, 150, .4);
            margin-bottom: 1.2rem;
        }

        .panel-brand h2 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.3;
        }

        .panel-brand p {
            font-size: .8rem;
            color: rgba(255, 255, 255, .55);
            margin-top: .2rem;
        }

        /* Ilustração central */
        .panel-center {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeIn 1s ease .3s both;
        }

        .panel-center .headline {
            font-size: clamp(1.6rem, 2.5vw, 2.4rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .panel-center .headline em {
            color: var(--amber);
            font-style: normal;
        }

        .panel-center .lead-text {
            font-size: .9rem;
            color: rgba(255, 255, 255, .65);
            line-height: 1.7;
            max-width: 380px;
        }

        /* Steps list no painel */
        .panel-steps {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        .panel-step {
            display: flex;
            align-items: center;
            gap: .85rem;
            animation: fadeUp .6s ease both;
        }

        .panel-step:nth-child(1) { animation-delay: .5s; }
        .panel-step:nth-child(2) { animation-delay: .65s; }
        .panel-step:nth-child(3) { animation-delay: .8s; }

        .step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 700;
            font-family: var(--font-head);
            border: 1.5px solid rgba(255, 255, 255, .2);
            color: rgba(255, 255, 255, .45);
        }

        .step-dot.done {
            background: var(--grad-teal);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 168, 150, .4);
        }

        .panel-step span {
            font-size: .85rem;
            color: rgba(255, 255, 255, .65);
        }

        .panel-step span strong {
            color: #fff;
        }

        /* Rodapé */
        .panel-footer {
            position: relative;
            z-index: 2;
            font-size: .73rem;
            color: rgba(255, 255, 255, .3);
            animation: fadeIn 1s ease .9s both;
        }

        /* ─── PAINEL DIREITO ──────────────────────────────────────── */
        .panel-right {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            position: relative;
            overflow-y: auto;
        }

        .form-card {
            width: 100%;
            max-width: 400px;
            animation: scaleIn .55s cubic-bezier(.22, .68, 0, 1.2) .1s both;
            position: relative;
        }

        /* Topo */
        .form-top {
            margin-bottom: 1.8rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .8rem;
            color: var(--muted);
            font-weight: 500;
            transition: color .2s, gap .2s;
            margin-bottom: 1.6rem;
        }

        .back-link:hover {
            color: var(--teal);
            gap: .65rem;
        }

        .form-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(0, 168, 150, .1);
            border: 1px solid rgba(0, 168, 150, .3);
            color: var(--teal2);
            border-radius: 50px;
            padding: .3rem 1rem;
            font-size: .73rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .form-top h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
        }

        .form-top p {
            font-size: .88rem;
            color: var(--muted);
            margin-top: .45rem;
            line-height: 1.65;
        }

        /* Info box */
        .info-box {
            background: rgba(11, 30, 61, .03);
            border-radius: 12px;
            border: 1px solid rgba(11, 30, 61, .07);
            padding: .9rem 1.1rem;
            margin-bottom: 1.3rem;
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            font-size: .82rem;
            color: var(--muted);
            line-height: 1.65;
        }

        .info-box .info-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            flex-shrink: 0;
            background: rgba(0, 168, 150, .1);
            color: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
        }

        /* Labels */
        .field-label {
            font-size: .82rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: .4rem;
            display: block;
        }

        /* Input */
        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: .95rem;
            pointer-events: none;
            transition: color .25s;
        }

        .form-input {
            width: 100%;
            border: 1.5px solid rgba(11, 30, 61, .14);
            border-radius: 12px;
            padding: .82rem .9rem .82rem 2.5rem;
            font-family: var(--font-body);
            font-size: .92rem;
            color: var(--navy);
            background: #fff;
            outline: none;
            transition: border-color .25s, box-shadow .25s;
        }

        .form-input::placeholder {
            color: rgba(107, 127, 163, .5);
        }

        .form-input:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(0, 168, 150, .12);
        }

        .form-input:focus ~ .field-icon {
            color: var(--teal);
        }

        .form-input.input-ok {
            border-color: var(--teal);
        }

        .form-input.input-error {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, .1);
        }

        .form-input.input-error ~ .field-icon {
            color: #e74c3c;
        }

        .form-input.is-invalid {
            border-color: #e74c3c;
        }

        .form-input.is-invalid ~ .field-icon {
            color: #e74c3c;
        }

        /* Feedback inline */
        .field-msg {
            font-size: .74rem;
            margin-top: .35rem;
            min-height: 1rem;
            display: flex;
            align-items: center;
            gap: .3rem;
            animation: slideRule .25s ease;
        }

        .field-msg.error {
            color: #e74c3c;
        }

        .field-msg.ok {
            color: var(--teal2);
        }

        /* Botão submit */
        .btn-send {
            width: 100%;
            padding: .92rem;
            border: none;
            border-radius: 50px;
            background: var(--grad-teal);
            color: #fff;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: .95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            box-shadow: 0 6px 20px rgba(0, 168, 150, .35);
            transition: transform .25s, box-shadow .25s, opacity .3s;
        }

        .btn-send:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 168, 150, .45);
        }

        .btn-send:active {
            transform: translateY(0);
        }

        /* Links bottom */
        .form-links {
            border-top: 1px solid rgba(11, 30, 61, .07);
            margin-top: 1.2rem;
            padding-top: 1.1rem;
            text-align: center;
        }

        .link-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-family: var(--font-head);
            font-size: .84rem;
            font-weight: 700;
            color: var(--teal);
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: gap .2s, color .2s;
            padding: 0;
            text-decoration: none;
        }

        .link-btn:hover {
            gap: .6rem;
            color: var(--teal2);
        }

        /* Invalid feedback Bootstrap compat */
        .invalid-feedback {
            display: block;
            font-size: .78rem;
            color: #e74c3c;
            margin-top: .3rem;
        }

        /* ─── ESTADO DE SUCESSO ───────────────────────────────────── */
        .view {
            transition: opacity .4s ease, transform .4s ease;
        }

        .view.hidden {
            opacity: 0;
            pointer-events: none;
            transform: translateY(12px);
            position: absolute;
            width: calc(100% - 5rem);
        }

        .success-card {
            text-align: center;
            animation: scaleIn .5s cubic-bezier(.22, .68, 0, 1.2) both;
        }

        .success-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--grad-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            margin: 0 auto 1.4rem;
            box-shadow: 0 8px 28px rgba(0, 168, 150, .35);
            animation: pulse 2.5s ease-in-out 3;
        }

        .success-card h2 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: .5rem;
        }

        .success-card p {
            font-size: .87rem;
            color: var(--muted);
            line-height: 1.7;
            max-width: 300px;
            margin: 0 auto;
        }

        .email-preview {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--light);
            border: 1px solid rgba(11, 30, 61, .1);
            border-radius: 50px;
            padding: .4rem 1.1rem;
            font-size: .82rem;
            font-weight: 600;
            color: var(--navy);
            margin: 1rem auto .1rem;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Barra de progresso do timer */
        .resend-area {
            margin-top: 1.5rem;
        }

        .resend-label {
            font-size: .8rem;
            color: var(--muted);
            margin-bottom: .5rem;
        }

        .timer-bar-track {
            height: 4px;
            background: rgba(11, 30, 61, .08);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: .7rem;
        }

        .timer-bar-fill {
            height: 100%;
            background: var(--grad-teal);
            border-radius: 2px;
            width: 100%;
            animation: progressFill 60s linear reverse forwards;
        }

        .btn-resend {
            background: none;
            border: 1.5px solid rgba(11, 30, 61, .15);
            border-radius: 50px;
            padding: .5rem 1.3rem;
            font-family: var(--font-head);
            font-size: .82rem;
            font-weight: 700;
            color: var(--muted);
            cursor: not-allowed;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: all .25s;
        }

        .btn-resend.ready {
            border-color: var(--teal);
            color: var(--teal);
            cursor: pointer;
        }

        .btn-resend.ready:hover {
            background: var(--teal);
            color: #fff;
            box-shadow: 0 4px 16px rgba(0, 168, 150, .3);
        }

        /* Link voltar ao login (tela sucesso) */
        .btn-back-login {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--grad-teal);
            color: #fff;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: .88rem;
            padding: .72rem 1.8rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0, 168, 150, .35);
            transition: transform .25s, box-shadow .25s;
            margin-top: 1.4rem;
        }

        .btn-back-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(0, 168, 150, .45);
            color: #fff;
        }

        /* ─── RESPONSIVO ─────────────────────────────────────────── */
        @media (max-width: 900px) {
            .page-wrapper {
                grid-template-columns: 1fr;
            }

            .panel-left {
                display: none;
            }

            .panel-right {
                padding: 2.5rem 1.5rem;
                min-height: 100vh;
                align-items: flex-start;
            }

            .form-card {
                padding-top: 1rem;
            }

            .view.hidden {
                width: calc(100% - 3rem);
            }
        }

        @media (max-width: 480px) {
            .panel-right {
                padding: 2rem 1.2rem;
            }

            .view.hidden {
                width: calc(100% - 2.4rem);
            }
        }
    </style>
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
                        <a href="{{ route('login') }}" class="back-link">
                            <i class="bi bi-arrow-left"></i> Voltar ao login
                        </a>
                        <div class="text-center">
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
                            {{ config('app.year') }}.
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
    <script>
        // ── Validação do e-mail em tempo real ──────────────────────
        function onEmailInput() {
            const el  = document.getElementById('myEmail');
            const msg = document.getElementById('msgEmail');
            const v   = el.value.trim();

            if (!v) {
                el.classList.remove('input-ok', 'input-error');
                msg.innerHTML = '';
                msg.className = 'field-msg';
                return;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
                el.classList.remove('input-ok');
                el.classList.add('input-error');
                msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> E-mail inválido';
                msg.className = 'field-msg error';
            } else {
                el.classList.remove('input-error');
                el.classList.add('input-ok');
                msg.innerHTML = '<i class="bi bi-check-circle-fill"></i> E-mail válido';
                msg.className = 'field-msg ok';
            }
        }

        // ── Contador para reenvio ──────────────────────────────────
        let countdownInterval = null;

        function startCountdown() {
            // let seconds   = 60;
            let seconds   = 300;
            const countEl = document.getElementById('countdown');
            const labelEl = document.getElementById('resendLabel');
            const btnEl   = document.getElementById('btnResend');

            countdownInterval = setInterval(() => {
                seconds--;
                countEl.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(countdownInterval);
                    btnEl.disabled = false;
                    btnEl.classList.add('ready');
                    labelEl.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Reenviar e-mail';
                }
            }, 1000);
        }

        async function doResend(btn) {
            if (btn.disabled) return;

            btn.disabled = true;
            btn.classList.remove('ready');
            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm"
                    style="width:.8rem;height:.8rem;border-width:2px;">
                </span>
                Enviando...
            `;

            try {
                const response = await fetch(
                    document.getElementById('resend-email').action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: new FormData(document.getElementById('resend-email'))
                    }
                );

                const data = await response.json();

                if (!response.ok) {
                    throw data;
                }

                btn.innerHTML = `
                    <i class="bi bi-check-circle-fill"></i>
                    Enviado!
                `;

                setTimeout(() => {
                    // Reinicia barra
                    const timerBar = document.getElementById('timerBar');
                    timerBar.style.animation = 'none';
                    requestAnimationFrame(() => {
                        timerBar.style.animation =
                            'progressFill 300s linear reverse forwards';
                    });

                    // Reinicia botão
                    btn.innerHTML = `
                        <i class="bi bi-arrow-clockwise"></i>
                        <span id="resendLabel">
                            Reenviar em <span id="countdown">60</span>s
                        </span>
                    `;

                    startCountdown();
                }, 1500);

            } catch (error) {
                btn.disabled = false;
                btn.classList.add('ready');
                btn.innerHTML = `
                    <i class="bi bi-x-circle-fill"></i>
                    Tentar novamente
                `;
            }
        }

        // ── Submit do formulário ───────────────────────────────────
        document
            .getElementById('resend-email')
            .addEventListener('submit', async function (event) {
                event.preventDefault();

                const form  = this;
                const input = document.getElementById('myEmail');
                const msg   = document.getElementById('msgEmail');
                const btn   = document.getElementById('btnSubmit');
                const email = input.value.trim();

                // Reset visual
                input.classList.remove('input-error');

                // Validação front-end
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    input.classList.add('input-error');
                    msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> Informe um e-mail válido.';
                    msg.className = 'field-msg error';
                    input.focus();
                    return;
                }

                // Loading
                btn.disabled = true;
                btn.innerHTML = `
                    <span class="spinner-border spinner-border-sm"
                        style="width:.9rem;height:.9rem;border-width:2px;">
                    </span>
                    Enviando...
                `;

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw data;
                    }

                    // Mostra e-mail na tela de sucesso
                    document.getElementById('emailPreviewText').textContent = email;

                    // Troca de view
                    const viewForm    = document.getElementById('viewForm');
                    const viewSuccess = document.getElementById('viewSuccess');

                    viewForm.style.opacity   = '0';
                    viewForm.style.transform = 'translateY(-12px)';

                    setTimeout(() => {
                        viewForm.classList.add('hidden');
                        viewSuccess.classList.remove('hidden');
                        viewSuccess.style.opacity   = '1';
                        viewSuccess.style.transform = 'translateY(0)';

                        startCountdown();
                    }, 350);

                } catch (error) {
                    btn.disabled = false;
                    btn.innerHTML = `
                        <i class="bi bi-envelope-check"></i>
                        Reenviar E-mail de Verificação
                    `;
                    msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> Não foi possível enviar o e-mail.';
                    msg.className = 'field-msg error';
                }
            });
    </script>
    {{-- <script type="module" src="{{ asset('assets/js/app.js') }}"></script> --}}
</body>

</html>