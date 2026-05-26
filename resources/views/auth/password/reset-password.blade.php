<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Redefinição de senha." />
    <title>Redefinir Senha — Vestibulinho {{ $calendar?->year }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <style>
        /* ─── TOKENS ─────────────────────────────────────────────── */
        :root {
            --navy: #0B1E3D;
            --teal: #00A896;
            --teal2: #007F72;
            --amber: #F4A261;
            --amber2: #E07A3A;
            --green: #27ae60;
            --green2: #1e8449;
            --light: #EEF3FA;
            --muted: #6B7FA3;
            --radius: 16px;
            --grad-main: linear-gradient(135deg, #0B1E3D 0%, #1B3E72 60%, #0E4D6B 100%);
            --grad-teal: linear-gradient(135deg, #00A896 0%, #007F72 100%);
            --grad-green: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
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
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-head);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ─── KEYFRAMES ───────────────────────────────────────────── */
        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-22px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(22px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-14px)
            }
        }

        @keyframes gradShift {
            0% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }

            100% {
                background-position: 0% 50%
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(.93)
            }

            to {
                opacity: 1;
                transform: scale(1)
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(39, 174, 96, .35)
            }

            50% {
                box-shadow: 0 0 0 10px rgba(39, 174, 96, 0)
            }
        }

        @keyframes checkPop {
            0% {
                transform: scale(0)
            }

            70% {
                transform: scale(1.2)
            }

            100% {
                transform: scale(1)
            }
        }

        /* ─── LAYOUT ──────────────────────────────────────────────── */
        .page-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ═══════════ PAINEL ESQUERDO ════════════════════════════════ */
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

        .deco-circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .deco-c1 {
            width: 440px;
            height: 440px;
            top: -110px;
            right: -150px;
            border: 1px solid rgba(255, 255, 255, .06);
        }

        .deco-c2 {
            width: 280px;
            height: 280px;
            bottom: -70px;
            left: -70px;
            border: 1px solid rgba(0, 168, 150, .18);
        }

        .deco-c3 {
            width: 160px;
            height: 160px;
            top: 40%;
            right: 8%;
            border: 1px solid rgba(39, 174, 96, .25);
            animation: float 7s ease-in-out infinite;
        }

        .deco-blob {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(39, 174, 96, .12) 0%, transparent 65%);
            width: 500px;
            height: 500px;
            bottom: -80px;
            right: -100px;
        }

        /* Brand */
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

        /* Centro */
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
            font-size: clamp(1.55rem, 2.4vw, 2.3rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.22;
            margin-bottom: 1rem;
        }

        .panel-center .headline em {
            color: #6eefb0;
            font-style: normal;
        }

        .panel-center .lead-text {
            font-size: .9rem;
            color: rgba(255, 255, 255, .65);
            line-height: 1.75;
            max-width: 380px;
        }

        /* Card de dicas de senha */
        .tips-card {
            margin-top: 2.2rem;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: var(--radius);
            padding: 1.2rem 1.4rem;
            backdrop-filter: blur(6px);
            animation: fadeUp .8s ease .5s both;
        }

        .tips-card h6 {
            font-size: .75rem;
            font-weight: 700;
            color: #6eefb0;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .85rem;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            margin-bottom: .65rem;
        }

        .tip-item:last-child {
            margin-bottom: 0;
        }

        .tip-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            flex-shrink: 0;
            background: rgba(39, 174, 96, .2);
            color: #6eefb0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
        }

        .tip-item p {
            font-size: .78rem;
            color: rgba(255, 255, 255, .6);
            line-height: 1.55;
            margin: 0;
        }

        /* Progresso de etapas */
        .step-progress {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-top: 2rem;
            animation: fadeUp .7s ease .9s both;
        }

        .sp-step {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .75rem;
            color: rgba(255, 255, 255, .5);
            font-weight: 500;
        }

        .sp-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
        }

        .sp-dot.done {
            background: rgba(0, 168, 150, .4);
            color: var(--teal);
            border: 1.5px solid var(--teal);
        }

        .sp-dot.current {
            background: rgba(39, 174, 96, .25);
            color: #6eefb0;
            border: 1.5px solid #6eefb0;
        }

        .sp-line {
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, .1);
            max-width: 24px;
        }

        /* Rodapé */
        .panel-footer {
            position: relative;
            z-index: 2;
            font-size: .73rem;
            color: rgba(255, 255, 255, .3);
            animation: fadeIn 1s ease 1s both;
        }

        /* ═══════════ PAINEL DIREITO ═════════════════════════════════ */
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
            background: rgba(39, 174, 96, .1);
            border: 1px solid rgba(39, 174, 96, .25);
            color: var(--green2);
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

        /* Toggle senhas */
        .toggle-pwd-btn {
            background: rgba(0, 168, 150, .08);
            border: 1px solid rgba(0, 168, 150, .2);
            color: var(--teal);
            font-size: .78rem;
            font-weight: 600;
            font-family: var(--font-head);
            cursor: pointer;
            border-radius: 50px;
            padding: .3rem .9rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background .2s, box-shadow .2s;
        }

        .toggle-pwd-btn:hover {
            background: rgba(0, 168, 150, .15);
            box-shadow: 0 2px 10px rgba(0, 168, 150, .2);
        }

        /* Labels */
        .field-label {
            font-size: .82rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: .4rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .field-label .req {
            color: #e74c3c;
            font-size: .7rem;
        }

        /* Inputs */
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

        .form-input:focus~.field-icon {
            color: var(--teal);
        }

        .form-input.input-ok {
            border-color: var(--green);
        }

        .form-input.input-ok~.field-icon {
            color: var(--green);
        }

        .form-input.input-error {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, .1);
        }

        .form-input.input-error~.field-icon {
            color: #e74c3c;
        }

        /* Eye btn */
        .eye-btn {
            position: absolute;
            right: .8rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            font-size: .95rem;
            cursor: pointer;
            padding: .2rem;
            line-height: 1;
            transition: color .2s;
        }

        .eye-btn:hover {
            color: var(--teal);
        }

        /* Feedback inline */
        .field-msg {
            font-size: .74rem;
            margin-top: .35rem;
            min-height: 1rem;
            display: flex;
            align-items: center;
            gap: .3rem;
            animation: slideUp .2s ease;
        }

        .field-msg.ok {
            color: var(--green);
        }

        .field-msg.error {
            color: #e74c3c;
        }

        /* Barra de força */
        .strength-wrap {
            margin-top: .55rem;
        }

        .strength-track {
            height: 5px;
            background: rgba(11, 30, 61, .08);
            border-radius: 3px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0;
            border-radius: 3px;
            transition: width .4s ease, background .4s ease;
        }

        .strength-label {
            font-size: .73rem;
            color: var(--muted);
            display: block;
            margin-top: .3rem;
            min-height: 1rem;
            transition: color .3s;
        }

        /* Regras checklist */
        .rules-box {
            background: rgba(11, 30, 61, .03);
            border-radius: 12px;
            border: 1px solid rgba(11, 30, 61, .07);
            padding: .85rem 1rem;
        }

        .rules-note {
            font-size: .78rem;
            color: var(--muted);
            line-height: 1.65;
            border-left: 3px solid var(--amber);
            padding-left: .75rem;
            margin-bottom: .85rem;
        }

        .rules-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .35rem .6rem;
        }

        .rule-chip {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .77rem;
            color: var(--muted);
            font-weight: 500;
            transition: color .3s;
        }

        .rule-chip .ri {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1.5px solid rgba(11, 30, 61, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .6rem;
            flex-shrink: 0;
            transition: background .3s, border-color .3s, transform .3s;
        }

        .rule-chip.valid {
            color: var(--green);
        }

        .rule-chip.valid .ri {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
            transform: scale(1.15);
            animation: checkPop .3s cubic-bezier(.22, .68, 0, 1.3);
        }

        /* Botão submit */
        .btn-reset {
            width: 100%;
            padding: .92rem;
            border: none;
            border-radius: 50px;
            background: var(--grad-green);
            color: #fff;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: .95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            box-shadow: 0 6px 22px rgba(39, 174, 96, .35);
            transition: transform .25s, box-shadow .25s, opacity .3s;
        }

        .btn-reset:disabled {
            opacity: .42;
            cursor: not-allowed;
            box-shadow: none;
            transform: none !important;
        }

        .btn-reset:not(:disabled):hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 34px rgba(39, 174, 96, .45);
        }

        /* Link inferior */
        .form-links {
            border-top: 1px solid rgba(11, 30, 61, .07);
            margin-top: 1.3rem;
            padding-top: 1.1rem;
            text-align: center;
        }

        .link-back {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-family: var(--font-head);
            font-size: .84rem;
            font-weight: 700;
            color: var(--muted);
            transition: color .2s, gap .2s;
        }

        .link-back:hover {
            color: var(--navy);
            gap: .65rem;
        }

        /* ─── VIEW SUCESSO ────────────────────────────────────────── */
        .view {
            transition: opacity .4s ease, transform .4s ease;
        }

        .view.hidden {
            opacity: 0;
            pointer-events: none;
            transform: translateY(14px);
            position: absolute;
            width: calc(100% - 5rem);
        }

        .success-card {
            text-align: center;
            animation: scaleIn .55s cubic-bezier(.22, .68, 0, 1.2) both;
        }

        .success-ring {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: var(--grad-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.1rem;
            color: #fff;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 28px rgba(39, 174, 96, .38);
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
            max-width: 280px;
            margin: 0 auto;
        }

        .btn-go-login {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            background: var(--grad-teal);
            color: #fff;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: .9rem;
            padding: .8rem 2rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0, 168, 150, .35);
            transition: transform .25s, box-shadow .25s;
            margin-top: 1.5rem;
        }

        .btn-go-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 168, 150, .45);
            color: #fff;
        }

        /* ─── RESPONSIVO ──────────────────────────────────────────── */
        @media(max-width:900px) {
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

        @media(max-width:480px) {
            .panel-right {
                padding: 2rem 1.2rem;
            }

            .rules-grid {
                grid-template-columns: 1fr;
            }

            .view.hidden {
                width: calc(100% - 2.4rem);
            }
        }
    </style>
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
        </aside>

        <!-- ═══════════════════ PAINEL DIREITO ════════════════════════ -->
        <main class="panel-right">
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
                                    <strong>e</strong> um número.
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
        </main>

    </div><!-- /page-wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Estado ─────────────────────────────────────────────────
        let pwdVisible = false;
        const rules = {
            len: v => v.length >= 6 && v.length <= 8,
            upper: v => /[A-Z]/.test(v),
            lower: v => /[a-z]/.test(v),
            num: v => /[0-9]/.test(v),
        };

        // ── Toggle TODAS as senhas ─────────────────────────────────
        function toggleAllPwd() {
            pwdVisible = !pwdVisible;
            document.querySelectorAll('.pwd-input').forEach(el => {
                el.type = pwdVisible ? 'text' : 'password';
            });
            document.querySelectorAll('.eye-btn i').forEach(i => {
                i.className = pwdVisible ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
            });
            document.getElementById('toggleAllIcon').className = pwdVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
            document.getElementById('toggleAllLabel').textContent = pwdVisible ? 'Ocultar senhas' : 'Mostrar senhas';
        }

        // ── Toggle senha individual ────────────────────────────────
        function toggleEye(inputId, iconId) {
            const el = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            el.type = el.type === 'password' ? 'text' : 'password';
            icon.className = el.type === 'text' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
        }

        // ── Regras: atualiza chip ──────────────────────────────────
        function setRule(key, valid) {
            const chip = document.getElementById('rule-' + key);
            const ri = document.getElementById('ri-' + key);
            chip.classList.toggle('valid', valid);
            ri.innerHTML = valid ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-dash"></i>';
        }

        // ── Força da senha + regras ────────────────────────────────
        function onPwdInput() {
            const v = document.getElementById('newPwd').value;

            setRule('len', rules.len(v));
            setRule('upper', rules.upper(v));
            setRule('lower', rules.lower(v));
            setRule('num', rules.num(v));

            let score = [rules.len(v), rules.upper(v), rules.lower(v), rules.num(v)]
                .filter(Boolean).length;

            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');
            const map = [{
                    w: '0%',
                    bg: 'transparent',
                    txt: ''
                },
                {
                    w: '30%',
                    bg: '#e74c3c',
                    txt: 'Muito fraca'
                },
                {
                    w: '55%',
                    bg: var_amber(),
                    txt: 'Fraca'
                },
                {
                    w: '78%',
                    bg: '#f0c030',
                    txt: 'Razoável'
                },
                {
                    w: '100%',
                    bg: var_green(),
                    txt: 'Forte ✓'
                },
            ];
            const s = v.length === 0 ? 0 : Math.max(1, score);
            fill.style.width = map[s].w;
            fill.style.background = map[s].bg;
            label.textContent = map[s].txt;
            label.style.color = map[s].bg === 'transparent' ? 'var(--muted)' : map[s].bg;

            validateConfirm();
            checkSubmit();
        }

        // Helpers para ler CSS vars em JS
        function var_amber() {
            return getComputedStyle(document.documentElement)
                .getPropertyValue('--amber').trim() || '#F4A261';
        }

        function var_green() {
            return getComputedStyle(document.documentElement)
                .getPropertyValue('--green').trim() || '#27ae60';
        }

        // ── Confirmar senha ────────────────────────────────────────
        function validateConfirm() {
            const pwd = document.getElementById('newPwd').value;
            const conf = document.getElementById('confirmPwd');
            const msg = document.getElementById('msgConfirm');
            const v = conf.value;

            conf.classList.remove('input-ok', 'input-error');
            if (!v) {
                msg.innerHTML = '';
                msg.className = 'field-msg';
                return;
            }
            if (v !== pwd) {
                conf.classList.add('input-error');
                msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> As senhas não coincidem';
                msg.className = 'field-msg error';
            } else {
                conf.classList.add('input-ok');
                msg.innerHTML = '<i class="bi bi-check-circle-fill"></i> Senhas conferem';
                msg.className = 'field-msg ok';
            }
            checkSubmit();
        }

        // ── Habilita botão ─────────────────────────────────────────
        function checkSubmit() {
            const pwd = document.getElementById('newPwd').value;
            const conf = document.getElementById('confirmPwd').value;
            const btn = document.getElementById('btnSubmit');

            const pwdOk = rules.len(pwd) && rules.upper(pwd) && rules.lower(pwd) && rules.num(pwd);
            const confirmOk = pwd === conf && conf.length > 0;

            btn.disabled = !(pwdOk && confirmOk);
            btn.style.cursor = btn.disabled ? 'not-allowed' : 'pointer';
        }

        document.getElementById('resetForm').addEventListener('submit', async function(e) {

            e.preventDefault();

            const btn = document.getElementById('btnSubmit');

            const errorsBox = document.getElementById('serverErrors');

            errorsBox.innerHTML = '';

            btn.innerHTML = `
    <span class="spinner-border spinner-border-sm"
      style="width:.9rem;height:.9rem;border-width:2px;">
    </span>
    Salvando...
  `;

            btn.disabled = true;

            const form = e.target;

            const formData = new FormData(form);

            try {

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                const data = await response.json();

                // erro backend
                if (!response.ok) {

                    let html = `
        <div class="alert alert-danger py-2 px-3 mb-3 rounded-3"
          style="font-size:.82rem;">
          <ul class="mb-0 ps-3">
      `;

                    // erros validate()
                    if (data.errors) {

                        Object.values(data.errors).forEach(arr => {

                            arr.forEach(msg => {
                                html += `<li>${msg}</li>`;
                            });

                        });

                    } else {

                        html += `<li>${data.message || 'Erro ao processar.'}</li>`;
                    }

                    html += `</ul></div>`;

                    errorsBox.innerHTML = html;

                    btn.disabled = false;

                    btn.innerHTML = `
        <i class="bi bi-save-fill"></i>
        Redefinir Senha
      `;

                    return;
                }

                // sucesso
                document.getElementById('viewForm')
                    .classList.add('hidden');

                setTimeout(() => {

                    document.getElementById('viewSuccess')
                        .classList.remove('hidden');

                }, 250);

            } catch (err) {

                errorsBox.innerHTML = `
      <div class="alert alert-danger py-2 px-3 mb-3 rounded-3"
        style="font-size:.82rem;">
        Ocorreu um erro inesperado.
      </div>
    `;

                btn.disabled = false;

                btn.innerHTML = `
      <i class="bi bi-save-fill"></i>
      Redefinir Senha
    `;
            }
        });
    </script>
</body>

</html>
