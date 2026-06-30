@extends('layouts.auth')

@section('title', 'Verificação de E-mail — Vestibulinho ' . $process?->year)

@section('meta_description', 'Verificação de e-mail para candidatos do Vestibulinho ' . $process?->year)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/email/sent.css') }}">
@endpush

@section('left-panel')

  <div class="deco-circle deco-c1"></div>
  <div class="deco-circle deco-c2"></div>
  <div class="deco-circle deco-c3"></div>
  <div class="deco-blob"></div>

  <!-- Brand -->
  <div class="panel-brand">
    <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
    <h2>EM Dr. Francisco de Souza</h2>
    <p>Vestibulinho {{ $process?->year }} · Cursos Técnicos Gratuitos</p>
  </div>

  <!-- Centro -->
  <div class="panel-center">
    <p class="headline">
      Só falta<br>confirmar<br>seu <em>e-mail</em>!
    </p>
    <p class="lead-text">
      Você está quase lá. Confirme seu endereço para liberar o acesso completo à sua Área do Candidato.
    </p>

    <!-- Timeline do processo de cadastro -->
    <div class="reg-timeline">
      <div class="rt-item">
        <div class="rt-node done"><i class="bi bi-check-lg"></i></div>
        <div class="rt-body">
          <strong>Dados de acesso criados</strong>
          <span>E-mail e senha registrados com sucesso.</span>
        </div>
      </div>
      <div class="rt-item">
        <div class="rt-node active"><i class="bi bi-envelope-fill"></i></div>
        <div class="rt-body">
          <strong>Verificação de e-mail</strong>
          <span>Confirme o link enviado para sua caixa de entrada.</span>
        </div>
      </div>
      <div class="rt-item">
        <div class="rt-node pending">3</div>
        <div class="rt-body">
          <strong>Acesso liberado</strong>
          <span>Área do Candidato disponível após confirmação.</span>
        </div>
      </div>
      <div class="rt-item">
        <div class="rt-node pending">4</div>
        <div class="rt-body">
          <strong>Realize sua inscrição</strong>
          <span>Preencha o formulário de inscrição e confirme sua participação.</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Rodapé -->
  <div class="panel-footer">
    © {{ $year ?? date('Y') }} EM Dr. Leandro Franceschini · Todos os direitos reservados
  </div>

@endsection

@section('right-panel')

  <div class="content-card">

    <!-- Envelope animado -->
    <div class="envelope-wrap">
      <div class="envelope-bg">
        <i class="bi bi-envelope-check-fill" aria-hidden="true"></i>
      </div>
      <div class="envelope-dot"></div>
    </div>

    <!-- Badge + Título -->
    <div class="text-center mb-4">
      <div class="form-badge">
        <i class="bi bi-envelope-check"></i> Verificação de E-mail
      </div>
      <h1 class="page-title">Verifique<br>sua caixa de entrada</h1>
    </div>

    <!-- Mensagem principal (conteúdo do Blade) -->
    <div class="msg-box" role="alert">
      <div class="msg-title">
        <i class="bi bi-info-circle-fill"></i>
        Prezado(a) Candidato(a):
      </div>
      <p>
        Um <em>link</em> de verificação foi enviado para o endereço de e-mail informado no
        momento do cadastro. Verifique sua caixa de entrada, incluindo a pasta de <em>spam</em>,
        e siga as instruções contidas na mensagem para confirmar seu endereço de e-mail.
        O acesso à <strong>Área do Candidato</strong> será liberado somente após a conclusão
        dessa confirmação.
      </p>
    </div>

    <!-- Chips informativos -->
    <div class="info-chips">
      <span class="info-chip">
        <i class="bi bi-clock-fill"></i> Link válido por 60 min
      </span>
      <span class="info-chip">
        <i class="bi bi-folder2-open"></i> Verifique a pasta spam
      </span>
      <span class="info-chip">
        <i class="bi bi-shield-check"></i> Envio seguro
      </span>
    </div>

    <!-- Botão principal -->
    <a href="{{ route('login') }}" class="btn-access">
      <i class="bi bi-box-arrow-in-right"></i> Acessar Área do Candidato
    </a>

  </div><!-- content-card -->

@endsection