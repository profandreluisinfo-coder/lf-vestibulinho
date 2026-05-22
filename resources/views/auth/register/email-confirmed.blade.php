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
    <meta name="description"
        content="Confirmação de e-mail para candidatos do {{ config('app.name') }} {{ $calendar->year }}">
    <title>{{ config('app.name') }} {{ $calendar->year ?? '' }} | Registro Confirmado</title>

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
                transform: translateY(-20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px)
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

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(.92)
            }

            to {
                opacity: 1;
                transform: scale(1)
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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-14px)
            }
        }

        @keyframes checkPop {
            0% {
                transform: scale(0) rotate(-15deg);
                opacity: 0
            }

            70% {
                transform: scale(1.15) rotate(3deg)
            }

            100% {
                transform: scale(1) rotate(0);
                opacity: 1
            }
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: .6
            }

            100% {
                transform: scale(1.8);
                opacity: 0
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center
            }

            100% {
                background-position: 200% center
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(16px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ─── LAYOUT GRID ─────────────────────────────────────────── */
        .page-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ─── PAINEL ESQUERDO ─────────────────────────────────────── */
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

        /* Steps */
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

        .panel-step:nth-child(1) {
            animation-delay: .5s
        }

        .panel-step:nth-child(2) {
            animation-delay: .65s
        }

        .panel-step:nth-child(3) {
            animation-delay: .8s
        }

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

        .confirm-card {
            width: 100%;
            max-width: 420px;
            animation: scaleIn .55s cubic-bezier(.22, .68, 0, 1.2) .1s both;
            text-align: center;
        }

        /* ─── ÍCONE DE SUCESSO ────────────────────────────────────── */
        .success-ring-wrap {
            position: relative;
            width: 96px;
            height: 96px;
            margin: 0 auto 1.8rem;
        }

        .ripple-ring {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 2px solid rgba(0, 168, 150, .4);
            animation: ripple 2s ease-out infinite;
        }

        .ripple-ring:nth-child(2) {
            animation-delay: .7s;
        }

        .success-ring {
            position: relative;
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: var(--grad-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            color: #fff;
            box-shadow: 0 10px 36px rgba(0, 168, 150, .38);
            animation: checkPop .6s cubic-bezier(.22, .68, 0, 1.2) .2s both;
            z-index: 1;
        }

        /* ─── BADGE STATUS ────────────────────────────────────────── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: rgba(0, 168, 150, .1);
            border: 1px solid rgba(0, 168, 150, .3);
            color: var(--teal2);
            border-radius: 50px;
            padding: .3rem 1rem;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            margin-bottom: 1.1rem;
            animation: fadeDown .7s ease .5s both;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: var(--teal);
            border-radius: 50%;
        }

        /* ─── TÍTULO ──────────────────────────────────────────────── */
        .confirm-card h1 {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
            margin-bottom: .5rem;
            animation: fadeUp .7s ease .55s both;
        }

        .confirm-card>p.subtitle {
            font-size: .9rem;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 1.6rem;
            animation: fadeUp .7s ease .6s both;
        }

        /* ─── CAIXA DE E-MAIL ─────────────────────────────────────── */
        .email-box {
            display: flex;
            align-items: center;
            gap: .75rem;
            background: var(--light);
            border: 1px solid rgba(11, 30, 61, .1);
            border-radius: 12px;
            padding: .9rem 1.1rem;
            margin-bottom: 1.5rem;
            text-align: left;
            animation: fadeUp .7s ease .65s both;
        }

        .email-box .email-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            flex-shrink: 0;
            background: var(--grad-teal);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .email-box .email-label {
            font-size: .73rem;
            color: var(--muted);
            margin-bottom: .1rem;
        }

        .email-box .email-value {
            font-size: .9rem;
            font-weight: 700;
            color: var(--navy);
            word-break: break-all;
        }

        /* ─── PRÓXIMO PASSO ───────────────────────────────────────── */
        .next-step-box {
            background: linear-gradient(135deg, rgba(0, 168, 150, .06), rgba(0, 168, 150, .02));
            border: 1px solid rgba(0, 168, 150, .18);
            border-radius: var(--radius);
            padding: 1.1rem 1.2rem;
            text-align: left;
            margin-bottom: 1.6rem;
            animation: fadeUp .7s ease .7s both;
        }

        .next-step-box .ns-header {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--teal2);
            margin-bottom: .6rem;
        }

        .next-step-box p {
            font-size: .86rem;
            color: var(--muted);
            line-height: 1.65;
            margin: 0;
        }

        .next-step-box strong {
            color: var(--navy);
        }

        /* ─── BOTÃO CTA ───────────────────────────────────────────── */
        .btn-cta {
            width: 100%;
            padding: .95rem;
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
            transition: transform .25s, box-shadow .25s;
            animation: fadeUp .7s ease .75s both;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 168, 150, .45);
            color: #fff;
        }

        .btn-cta:active {
            transform: translateY(0);
        }

        /* ─── DIVIDER + LINK VOLTAR ───────────────────────────────── */
        .confirm-divider {
            border: none;
            border-top: 1px solid rgba(11, 30, 61, .07);
            margin: 1.3rem 0;
            animation: fadeIn .7s ease .8s both;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            color: var(--muted);
            font-weight: 500;
            transition: color .2s, gap .2s;
            animation: fadeIn .7s ease .85s both;
        }

        .back-link:hover {
            color: var(--teal);
            gap: .65rem;
        }

        /* ─── RESPONSIVE ──────────────────────────────────────────── */
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

            .confirm-card {
                padding-top: 1.5rem;
            }
        }

        @media(max-width:480px) {
            .panel-right {
                padding: 2rem 1.2rem;
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

            <div class="panel-brand">
                <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <h2>EM Dr. Leandro Franceschini</h2>
                <p>Vestibulinho {{ $calendar->year }} · Cursos Técnicos Gratuitos</p>
            </div>

            <div class="panel-center">
                <p class="headline">
                    Tudo certo!<br>Sua conta está<br><em>verificada</em>.
                </p>
                <p class="lead-text">
                    Agora você pode acessar a Área do Candidato e preencher o formulário de inscrição para o
                    Vestibulinho {{ $calendar->year }}.
                </p>

                <div class="panel-steps">
                    <div class="panel-step">
                        <div class="step-dot done"><i class="bi bi-check-lg"></i></div>
                        <span><strong>Cadastro</strong> realizado com sucesso</span>
                    </div>
                    <div class="panel-step">
                        <div class="step-dot done"><i class="bi bi-check-lg"></i></div>
                        <span><strong>E-mail</strong> verificado com sucesso</span>
                    </div>
                    <div class="panel-step">
                        <div class="step-dot" style="border-color:var(--amber);color:var(--amber);">3</div>
                        <span>Preencher o <strong>formulário de inscrição</strong></span>
                    </div>
                </div>
            </div>

            <div class="panel-footer">
                © {{ $currentYear ?? date('Y') }} EM Dr. Leandro Franceschini · Todos os direitos reservados
            </div>
        </aside>

        <!-- ══════════ PAINEL DIREITO ══════════ -->
        <main class="panel-right">
            <div class="confirm-card">

                <!-- Ícone animado -->
                <div class="success-ring-wrap">
                    <div class="ripple-ring"></div>
                    <div class="ripple-ring"></div>
                    <div class="success-ring">
                        <i class="bi bi-envelope-check-fill"></i>
                    </div>
                </div>

                <!-- Badge -->
                <div class="status-badge">
                    <span class="status-dot"></span>
                    E-mail Verificado
                </div>

                <!-- Título -->
                <h1>Registro<br>Confirmado!</h1>
                <p class="subtitle">
                    Seu endereço de e-mail foi validado com sucesso.<br>
                    Você já pode acessar a Área do Candidato.
                </p>

                <!-- E-mail verificado -->
                <div class="email-box">
                    <div class="email-icon"><i class="bi bi-envelope-check-fill"></i></div>
                    <div>
                        <div class="email-label">E-mail verificado</div>
                        <div class="email-value">{{ session('email') ?? '—' }}</div>
                    </div>
                </div>

                <!-- Próximo passo -->
                <div class="next-step-box">
                    <div class="ns-header">
                        <i class="bi bi-arrow-right-circle-fill"></i> Próximo passo
                    </div>
                    <p>
                        Acesse a <strong>Área do Candidato</strong> com o e-mail e senha cadastrados
                        para preencher o <strong>formulário de inscrição</strong> do
                        Vestibulinho {{ $calendar->year }}.
                    </p>
                </div>

                <!-- CTA -->
                <a href="{{ route('login') }}" class="btn-cta">
                    <i class="bi bi-person-fill-lock"></i>
                    Acessar Área do Candidato
                </a>

                <hr class="confirm-divider">

                <a href="{{ route('home') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i> Voltar para a página inicial
                </a>

            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
