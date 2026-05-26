<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Área de acesso exclusivo para candidatos." />
    <title>Área do Administrador — Vestibulinho {{ $calendar?->year }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <style>
        /* ─── TOKENS ─────────────────────────────────────────────── */
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

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0)
            }

            20%,
            60% {
                transform: translateX(-6px)
            }

            40%,
            80% {
                transform: translateX(6px)
            }
        }

        /* ─── LAYOUT ──────────────────────────────────────────────── */
        .page-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ═══════════ PAINEL ESQUERDO ═══════════════════════════════ */
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

        /* Decorações */
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
            border: 1px solid rgba(244, 162, 97, .22);
            animation: float 7s ease-in-out infinite;
        }

        .deco-blob {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(0, 168, 150, .16) 0%, transparent 65%);
            width: 520px;
            height: 520px;
            top: 5%;
            right: -130px;
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
            line-height: 1.75;
            max-width: 380px;
        }

        /* Card de segurança */
        .security-card {
            margin-top: 2.2rem;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: var(--radius);
            padding: 1.2rem 1.4rem;
            backdrop-filter: blur(6px);
            animation: fadeUp .8s ease .5s both;
        }

        .security-card h6 {
            font-size: .75rem;
            font-weight: 700;
            color: var(--amber);
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .75rem;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .sec-item {
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            margin-bottom: .6rem;
        }

        .sec-item:last-child {
            margin-bottom: 0;
        }

        .sec-item .sec-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(0, 168, 150, .2);
            color: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
            flex-shrink: 0;
            margin-top: .05rem;
        }

        .sec-item p {
            font-size: .78rem;
            color: rgba(255, 255, 255, .6);
            line-height: 1.55;
            margin: 0;
        }

        /* Rodapé */
        .panel-footer {
            position: relative;
            z-index: 2;
            font-size: .73rem;
            color: rgba(255, 255, 255, .3);
            animation: fadeIn 1s ease .9s both;
        }

        /* ═══════════ PAINEL DIREITO (formulário) ════════════════════ */
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
            background: rgba(0, 168, 150, .08);
            border: 1px solid rgba(0, 168, 150, .2);
            color: var(--teal);
            border-radius: 50px;
            padding: .3rem 1rem;
            font-size: .73rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .form-top h1 {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.18;
        }

        .form-top p {
            font-size: .88rem;
            color: var(--muted);
            margin-top: .4rem;
            line-height: 1.6;
        }

        /* Aviso de erro geral */
        .alert-error {
            background: rgba(231, 76, 60, .07);
            border: 1px solid rgba(231, 76, 60, .25);
            border-radius: 12px;
            padding: .8rem 1rem;
            font-size: .82rem;
            color: #c0392b;
            display: flex;
            align-items: center;
            gap: .6rem;
            animation: shake .4s ease;
            margin-bottom: 1.1rem;
        }

        .alert-error.hidden {
            display: none;
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

        /* Checkbox personalizado */
        .custom-check {
            display: flex;
            align-items: center;
            gap: .65rem;
            cursor: pointer;
        }

        .custom-check input[type="checkbox"] {
            display: none;
        }

        .check-box {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            flex-shrink: 0;
            border: 1.5px solid rgba(11, 30, 61, .2);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .2s, border-color .2s;
            font-size: .7rem;
            color: transparent;
        }

        .custom-check input:checked~.check-box {
            background: var(--teal);
            border-color: var(--teal);
            color: #fff;
        }

        .custom-check .check-label {
            font-size: .84rem;
            color: var(--muted);
            font-weight: 400;
            user-select: none;
        }

        /* Linha "lembrar / esqueceu" */
        .row-remember {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -.1rem;
        }

        .forgot-link {
            font-size: .82rem;
            font-weight: 600;
            color: var(--teal);
            transition: color .2s;
        }

        .forgot-link:hover {
            color: var(--teal2);
        }

        /* Botão principal */
        .btn-login {
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
            box-shadow: 0 6px 22px rgba(0, 168, 150, .35);
            transition: transform .25s, box-shadow .25s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 34px rgba(0, 168, 150, .45);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Links inferiores */
        .form-links {
            border-top: 1px solid rgba(11, 30, 61, .07);
            margin-top: 1.3rem;
            padding-top: 1.1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .55rem;
        }

        .link-register {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-family: var(--font-head);
            font-size: .84rem;
            font-weight: 700;
            color: var(--navy);
            background: var(--light);
            border: 1.5px solid rgba(11, 30, 61, .1);
            border-radius: 50px;
            padding: .55rem 1.3rem;
            transition: background .2s, border-color .2s, color .2s;
        }

        .link-register:hover {
            background: rgba(0, 168, 150, .08);
            border-color: var(--teal);
            color: var(--teal);
        }

        /* Divider */
        .divider-or {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .75rem;
            color: rgba(11, 30, 61, .3);
            font-weight: 500;
            margin: .1rem 0;
        }

        .divider-or::before,
        .divider-or::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(11, 30, 61, .08);
        }

        /* Overlay sucesso */
        .success-overlay {
            position: absolute;
            inset: 0;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 3rem;
            text-align: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .4s ease;
            z-index: 10;
        }

        .success-overlay.show {
            opacity: 1;
            pointer-events: all;
        }

        .success-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--grad-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            box-shadow: 0 8px 28px rgba(0, 168, 150, .35);
            animation: scaleIn .5s cubic-bezier(.22, .68, 0, 1.2);
        }

        .success-overlay h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--navy);
        }

        .success-overlay p {
            font-size: .88rem;
            color: var(--muted);
            line-height: 1.7;
            max-width: 280px;
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
                <p>Vestibulinho {{ $calendar?->year }} · Cursos Técnicos Gratuitos</p>
            </div>

            <!-- Centro -->
            <div class="panel-center">
                <p class="headline">
                    Bem-vindo de<br>volta à sua<br><em>Área Administrativa</em>.
                </p>
                <p class="lead-text">
                    Gerencie inscrições, acompanhe indicadores, publique editais e organize convocações — o controle total do processo em um só lugar.
                </p>

                <!-- Card de segurança -->
                <div class="security-card">
                    <h6><i class="bi bi-shield-fill-check"></i> Acesso seguro</h6>
                    <div class="sec-item">
                        <div class="sec-icon"><i class="bi bi-lock-fill"></i></div>
                        <p>Sua conexão é protegida. Nunca compartilhe sua senha com terceiros.</p>
                    </div>
                    <div class="sec-item">
                        <div class="sec-icon"><i class="bi bi-headset"></i></div>
                        <p>Dificuldades de acesso? Contate a desenvolvedor.</p>
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
                            <i class="bi bi-shield-fill-check"></i> Área Administrativa
                        </div>
                    </div>

                    <!-- Título -->
                    <h1>Vestibulinho<br>{{ $calendar?->year }}</h1>
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
                    </div>
                    
                    <!-- Botão entrar -->
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </button>

                    <!--</div> /fields -->
                </form>
            </div><!-- /form-card -->
        </main>

    </div><!-- /page-wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Toggle olhinho ─────────────────────────────────────────
        function toggleEye(inputId, iconId) {
            const el = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            el.type = el.type === 'password' ? 'text' : 'password';
            icon.className = el.type === 'text' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
        }

        // ── Limpa borda de erro ao digitar ─────────────────────────
        function clearError(el) {
            el.classList.remove('input-error');
            document.getElementById('alertError').classList.add('hidden');
        }

        // ── Valida campos ──────────────────────────────────────────
        function validate() {
            const email = document.getElementById('loginEmail');
            const pwd = document.getElementById('loginPassword');
            let ok = true;

            if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                email.classList.add('input-error');
                ok = false;
            }
            if (!pwd.value.trim()) {
                pwd.classList.add('input-error');
                ok = false;
            }
            return ok;
        }

        // ── Mostrar erro ───────────────────────────────────────────
        function showError(msg) {
            const alert = document.getElementById('alertError');
            document.getElementById('alertMsg').textContent = msg;
            alert.classList.remove('hidden');
            alert.style.animation = 'none';
            requestAnimationFrame(() => {
                alert.style.animation = 'shake .4s ease';
            });
        }

        document.querySelector('form').addEventListener('submit', function(event) {

            if (!validate()) {
                showError('Preencha todos os campos obrigatórios.');
                event.preventDefault();
                return;
            }

            const btn = document.querySelector('.btn-login');

            btn.disabled = true;

            btn.innerHTML = `<span class="spinner-border spinner-border-sm" style="width:.95rem;height:.95rem;border-width:2px;"></span>Entrando...`;
        });
    </script>
</body>

</html>
