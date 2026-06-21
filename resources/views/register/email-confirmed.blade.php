@extends('layouts.auth')

@section('title', 'Confirmação de e-mail — Vestibulinho ' . $selection_process->year)

@section('meta_description', 'Área de confirmação de e-mail para candidatos do Vestibulinho.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/email/confirmed.css') }}">
@endpush

@section('left-panel')

    <div class="deco-circle deco-c1"></div>
    <div class="deco-circle deco-c2"></div>
    <div class="deco-circle deco-c3"></div>
    <div class="deco-blob"></div>

    <div class="panel-brand">
        <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <h2>EM Dr. Leandro Franceschini</h2>
        <p>Vestibulinho {{ $selection_process->year ?? config('app.year') }} · Cursos Técnicos Gratuitos</p>
    </div>

    <div class="panel-center">
        <p class="headline">
            Tudo certo!<br>Sua conta está<br><em>verificada</em>.
        </p>
        <p class="lead-text">
            Agora você pode acessar a Área do Candidato e preencher o formulário de inscrição para o
            Vestibulinho {{ $selection_process->year ?? config('app.year') }}.
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
        © {{ $year ?? date('Y') }} EM Dr. Leandro Franceschini · Todos os direitos reservados
    </div>

@endsection

@section('right-panel')

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
                Vestibulinho {{ $selection_process->year }}.
            </p>
        </div>

        <!-- CTA -->
        <a href="{{ route('login') }}" class="btn-login">
            <i class="bi bi-person-fill-lock"></i>
            Acessar Área do Candidato
        </a>

        <hr class="confirm-divider">

        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Voltar para a página inicial
        </a>

    </div>

@endsection