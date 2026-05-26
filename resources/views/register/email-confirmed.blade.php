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
        content="Confirmação de e-mail para candidatos do {{ config('app.name') }} {{ $calendar->year ?? config('app.year') }}">
    <title>{{ config('app.name') }} {{ $calendar->year ?? config('app.year') }} | Registro Confirmado</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/email/confirmed.css') }}" />

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
                <p>Vestibulinho {{ $calendar->year ?? config('app.year') }} · Cursos Técnicos Gratuitos</p>
            </div>

            <div class="panel-center">
                <p class="headline">
                    Tudo certo!<br>Sua conta está<br><em>verificada</em>.
                </p>
                <p class="lead-text">
                    Agora você pode acessar a Área do Candidato e preencher o formulário de inscrição para o
                    Vestibulinho {{ $calendar->year ?? config('app.year') }}.
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
