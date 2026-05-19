<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Recuperação de senha." />
  <title>Recuperar Senha — Vestibulinho 2025</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <style>
    /* ─── TOKENS ─────────────────────────────────────────────── */
    :root {
      --navy:       #0B1E3D;
      --teal:       #00A896;
      --teal2:      #007F72;
      --amber:      #F4A261;
      --amber2:     #E07A3A;
      --light:      #EEF3FA;
      --muted:      #6B7FA3;
      --shadow:     0 8px 32px rgba(11,30,61,.12);
      --shadow-lg:  0 24px 64px rgba(11,30,61,.18);
      --radius:     16px;
      --grad-main:  linear-gradient(135deg, #0B1E3D 0%, #1B3E72 60%, #0E4D6B 100%);
      --grad-teal:  linear-gradient(135deg, #00A896 0%, #007F72 100%);
      --grad-amber: linear-gradient(135deg, #F4A261 0%, #E07A3A 100%);
      --font-head:  'Sora', sans-serif;
      --font-body:  'DM Sans', sans-serif;
    }

    /* ─── BASE ────────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body {
      font-family: var(--font-body);
      background: var(--light);
      color: var(--navy);
      min-height: 100vh;
    }
    h1,h2,h3,h4,h5,h6 { font-family: var(--font-head); }
    a { text-decoration: none; color: inherit; }

    /* ─── KEYFRAMES ───────────────────────────────────────────── */
    @keyframes fadeDown  { from{opacity:0;transform:translateY(-22px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeUp    { from{opacity:0;transform:translateY(22px)}  to{opacity:1;transform:translateY(0)} }
    @keyframes fadeIn    { from{opacity:0} to{opacity:1} }
    @keyframes float     { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }
    @keyframes gradShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    @keyframes scaleIn   { from{opacity:0;transform:scale(.93)} to{opacity:1;transform:scale(1)} }
    @keyframes slideUp   { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
    @keyframes progressFill { from{width:0} to{width:100%} }
    @keyframes pulse     { 0%,100%{box-shadow:0 0 0 0 rgba(0,168,150,.35)} 50%{box-shadow:0 0 0 10px rgba(0,168,150,0)} }

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

    .deco-circle { position:absolute; border-radius:50%; pointer-events:none; }
    .deco-c1 { width:440px;height:440px; top:-110px; right:-150px; border:1px solid rgba(255,255,255,.06); }
    .deco-c2 { width:280px;height:280px; bottom:-70px; left:-70px;  border:1px solid rgba(0,168,150,.18); }
    .deco-c3 { width:160px;height:160px; top:40%; right:8%;
               border:1px solid rgba(244,162,97,.22); animation:float 7s ease-in-out infinite; }
    .deco-blob {
      position:absolute; border-radius:50%; pointer-events:none;
      background:radial-gradient(circle, rgba(244,162,97,.12) 0%, transparent 65%);
      width:500px; height:500px; bottom:-80px; right:-100px;
    }

    /* Brand */
    .panel-brand { position:relative; z-index:2; animation:fadeDown .8s ease both; }
    .brand-icon {
      width:48px; height:48px; border-radius:14px;
      background:var(--grad-teal); display:flex; align-items:center; justify-content:center;
      font-size:1.4rem; color:#fff; box-shadow:0 6px 20px rgba(0,168,150,.4); margin-bottom:1.2rem;
    }
    .panel-brand h2 { font-size:1.1rem; font-weight:800; color:#fff; line-height:1.3; }
    .panel-brand p  { font-size:.8rem; color:rgba(255,255,255,.55); margin-top:.2rem; }

    /* Centro */
    .panel-center {
      position:relative; z-index:2; flex:1;
      display:flex; flex-direction:column; justify-content:center;
      animation:fadeIn 1s ease .3s both;
    }
    .panel-center .headline {
      font-size: clamp(1.55rem, 2.4vw, 2.3rem);
      font-weight:800; color:#fff; line-height:1.22; margin-bottom:1rem;
    }
    .panel-center .headline em { color:var(--amber); font-style:normal; }
    .panel-center .lead-text {
      font-size:.9rem; color:rgba(255,255,255,.65); line-height:1.75; max-width:380px;
    }

    /* Steps de recuperação */
    .recovery-steps { margin-top:2.2rem; display:flex; flex-direction:column; gap:0; }
    .rs-item {
      display:flex; gap:1rem; position:relative;
      animation:fadeUp .6s ease both;
    }
    .rs-item:nth-child(1){ animation-delay:.5s; }
    .rs-item:nth-child(2){ animation-delay:.65s; }
    .rs-item:nth-child(3){ animation-delay:.8s; }

    /* Linha vertical conectora */
    .rs-item:not(:last-child)::after {
      content:'';
      position:absolute; left:18px; top:38px;
      width:2px; height:calc(100% - 10px);
      background:linear-gradient(to bottom, rgba(255,255,255,.15), rgba(255,255,255,.04));
    }
    .rs-node {
      width:38px; height:38px; border-radius:50%; flex-shrink:0;
      display:flex; align-items:center; justify-content:center;
      font-size:.85rem; font-weight:700; font-family:var(--font-head);
      position:relative; z-index:1;
    }
    .rs-node.active {
      background:var(--grad-amber);
      color:var(--navy);
      box-shadow:0 4px 16px rgba(244,162,97,.4);
      animation: pulse 2s ease-in-out infinite;
    }
    .rs-node.pending {
      background:rgba(255,255,255,.08);
      border:1px solid rgba(255,255,255,.16);
      color:rgba(255,255,255,.4);
    }
    .rs-body { padding:.55rem 0 1.5rem; }
    .rs-body strong { display:block; font-size:.85rem; font-weight:700; color:#fff; margin-bottom:.18rem; }
    .rs-body span   { font-size:.78rem; color:rgba(255,255,255,.55); line-height:1.5; }

    /* Rodapé */
    .panel-footer {
      position:relative; z-index:2;
      font-size:.73rem; color:rgba(255,255,255,.3);
      animation:fadeIn 1s ease .9s both;
    }

    /* ═══════════ PAINEL DIREITO ═════════════════════════════════ */
    .panel-right {
      background:#fff;
      display:flex; align-items:center; justify-content:center;
      padding:3rem 2.5rem;
      position:relative;
      overflow-y:auto;
    }
    .form-card {
      width:100%; max-width:400px;
      animation:scaleIn .55s cubic-bezier(.22,.68,0,1.2) .1s both;
    }

    /* Topo */
    .form-top { margin-bottom:1.8rem; }
    .back-link {
      display:inline-flex; align-items:center; gap:.4rem;
      font-size:.8rem; color:var(--muted); font-weight:500;
      transition:color .2s, gap .2s; margin-bottom:1.6rem;
    }
    .back-link:hover { color:var(--teal); gap:.65rem; }

    .form-badge {
      display:inline-flex; align-items:center; gap:.5rem;
      background:rgba(244,162,97,.1); border:1px solid rgba(244,162,97,.3);
      color:var(--amber2); border-radius:50px;
      padding:.3rem 1rem; font-size:.73rem; font-weight:700;
      letter-spacing:.07em; text-transform:uppercase; margin-bottom:1rem;
    }
    .form-top h1 { font-size:1.6rem; font-weight:800; color:var(--navy); line-height:1.2; }
    .form-top p  { font-size:.88rem; color:var(--muted); margin-top:.45rem; line-height:1.65; }

    /* Info box */
    .info-box {
      background:rgba(11,30,61,.03); border-radius:12px;
      border:1px solid rgba(11,30,61,.07);
      padding:.9rem 1.1rem; margin-bottom:1.3rem;
      display:flex; align-items:flex-start; gap:.75rem;
      font-size:.82rem; color:var(--muted); line-height:1.65;
    }
    .info-box .info-icon {
      width:30px; height:30px; border-radius:50%; flex-shrink:0;
      background:rgba(0,168,150,.1); color:var(--teal);
      display:flex; align-items:center; justify-content:center; font-size:.85rem;
    }

    /* Labels */
    .field-label {
      font-size:.82rem; font-weight:700; color:var(--navy);
      margin-bottom:.4rem; display:block;
    }

    /* Input */
    .field-wrap { position:relative; }
    .field-icon {
      position:absolute; left:.9rem; top:50%; transform:translateY(-50%);
      color:var(--muted); font-size:.95rem; pointer-events:none; transition:color .25s;
    }
    .form-input {
      width:100%; border:1.5px solid rgba(11,30,61,.14);
      border-radius:12px; padding:.82rem .9rem .82rem 2.5rem;
      font-family:var(--font-body); font-size:.92rem; color:var(--navy);
      background:#fff; outline:none;
      transition:border-color .25s, box-shadow .25s;
    }
    .form-input::placeholder { color:rgba(107,127,163,.5); }
    .form-input:focus {
      border-color:var(--teal);
      box-shadow:0 0 0 3px rgba(0,168,150,.12);
    }
    .form-input:focus ~ .field-icon { color:var(--teal); }
    .form-input.input-ok    { border-color:var(--teal); }
    .form-input.input-error {
      border-color:#e74c3c;
      box-shadow:0 0 0 3px rgba(231,76,60,.1);
    }
    .form-input.input-error ~ .field-icon { color:#e74c3c; }

    /* Feedback inline */
    .field-msg {
      font-size:.74rem; margin-top:.35rem; min-height:1rem;
      display:flex; align-items:center; gap:.3rem;
      animation:slideUp .2s ease;
    }
    .field-msg.error { color:#e74c3c; }
    .field-msg.ok    { color:var(--teal2); }

    /* Botão */
    .btn-recover {
      width:100%; padding:.92rem; border:none; border-radius:50px;
      background:var(--grad-amber);
      color:var(--navy); font-family:var(--font-head); font-weight:700; font-size:.95rem;
      cursor:pointer; display:flex; align-items:center; justify-content:center; gap:.6rem;
      box-shadow:0 6px 22px rgba(244,162,97,.38);
      transition:transform .25s, box-shadow .25s;
    }
    .btn-recover:hover {
      transform:translateY(-3px); box-shadow:0 12px 34px rgba(244,162,97,.5);
    }
    .btn-recover:active { transform:translateY(0); }

    /* Link inferior */
    .form-links {
      border-top:1px solid rgba(11,30,61,.07);
      margin-top:1.3rem; padding-top:1.1rem;
      text-align:center;
    }
    .link-back-login {
      display:inline-flex; align-items:center; gap:.45rem;
      font-family:var(--font-head); font-size:.84rem; font-weight:700;
      color:var(--muted);
      transition:color .2s, gap .2s;
    }
    .link-back-login:hover { color:var(--navy); gap:.65rem; }

    /* ─── ESTADO DE SUCESSO ───────────────────────────────────── */
    .view { transition:opacity .4s ease, transform .4s ease; }
    .view.hidden {
      opacity:0; pointer-events:none;
      transform:translateY(12px);
      position:absolute; width:calc(100% - 5rem);
    }

    .success-card {
      text-align:center;
      animation:scaleIn .5s cubic-bezier(.22,.68,0,1.2) both;
    }
    .success-ring {
      width:80px; height:80px; border-radius:50%;
      background:var(--grad-teal);
      display:flex; align-items:center; justify-content:center;
      font-size:2rem; color:#fff; margin:0 auto 1.4rem;
      box-shadow:0 8px 28px rgba(0,168,150,.35);
      animation:pulse 2.5s ease-in-out 3;
    }
    .success-card h2 { font-size:1.35rem; font-weight:800; color:var(--navy); margin-bottom:.5rem; }
    .success-card p  { font-size:.87rem; color:var(--muted); line-height:1.7; max-width:300px; margin:0 auto; }

    .email-preview {
      display:inline-flex; align-items:center; gap:.5rem;
      background:var(--light); border:1px solid rgba(11,30,61,.1);
      border-radius:50px; padding:.4rem 1.1rem;
      font-size:.82rem; font-weight:600; color:var(--navy);
      margin:1rem auto .1rem; max-width:100%; overflow:hidden;
      text-overflow:ellipsis; white-space:nowrap;
    }

    /* Barra de progresso do timer */
    .resend-area { margin-top:1.5rem; }
    .resend-label { font-size:.8rem; color:var(--muted); margin-bottom:.5rem; }
    .timer-bar-track {
      height:4px; background:rgba(11,30,61,.08);
      border-radius:2px; overflow:hidden; margin-bottom:.7rem;
    }
    .timer-bar-fill {
      height:100%; background:var(--grad-teal); border-radius:2px;
      width:100%;
      animation:progressFill 60s linear reverse forwards;
    }
    .btn-resend {
      background:none; border:1.5px solid rgba(11,30,61,.15);
      border-radius:50px; padding:.5rem 1.3rem;
      font-family:var(--font-head); font-size:.82rem; font-weight:700;
      color:var(--muted); cursor:not-allowed;
      display:inline-flex; align-items:center; gap:.4rem;
      transition:all .25s;
    }
    .btn-resend.ready {
      border-color:var(--teal); color:var(--teal); cursor:pointer;
    }
    .btn-resend.ready:hover {
      background:var(--teal); color:#fff;
      box-shadow:0 4px 16px rgba(0,168,150,.3);
    }
    .resend-timer { font-size:.75rem; color:var(--muted); margin-top:.4rem; display:block; }

    /* Link voltar ao login (tela sucesso) */
    .btn-back-login {
      display:inline-flex; align-items:center; gap:.5rem;
      background:var(--grad-teal); color:#fff;
      font-family:var(--font-head); font-weight:700; font-size:.88rem;
      padding:.72rem 1.8rem; border-radius:50px; border:none; cursor:pointer;
      box-shadow:0 6px 20px rgba(0,168,150,.35);
      transition:transform .25s, box-shadow .25s; margin-top:1.4rem;
    }
    .btn-back-login:hover {
      transform:translateY(-2px); box-shadow:0 10px 28px rgba(0,168,150,.45); color:#fff;
    }

    /* ─── RESPONSIVO ──────────────────────────────────────────── */
    @media(max-width:900px) {
      .page-wrapper { grid-template-columns:1fr; }
      .panel-left   { display:none; }
      .panel-right  { padding:2.5rem 1.5rem; min-height:100vh; align-items:flex-start; }
      .form-card    { padding-top:1rem; }
      .view.hidden  { width:calc(100% - 3rem); }
    }
    @media(max-width:480px) {
      .panel-right { padding:2rem 1.2rem; }
      .view.hidden { width:calc(100% - 2.4rem); }
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
      <p>Vestibulinho {{ config('app.year') }} · Cursos Técnicos Gratuitos</p>
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
          {{-- <a href="{{ route('login') }}" class="back-link me-2">
            <i class="bi bi-arrow-left"></i> Voltar ao login
          </a> --}}
          <div class="text-center">
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
            Use o mesmo e-mail informado durante o seu registro no Vestibulinho {{ config('app.year') }}.
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
            <input type="email" name="email" id="forgotEmail" class="form-input @error('email') is-invalid @enderror"
              placeholder="seu@email.com"
              autocomplete="email"
              oninput="onEmailInput()" />
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
<script>

  // ── Validação do e-mail em tempo real ──────────────────────
  function onEmailInput() {
    const el  = document.getElementById('forgotEmail');
    const msg = document.getElementById('msgEmail');
    const v   = el.value.trim();

    if (!v) {
      el.classList.remove('input-ok', 'input-error');
      msg.innerHTML = ''; msg.className = 'field-msg';
      return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
      el.classList.remove('input-ok'); el.classList.add('input-error');
      msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> E-mail inválido';
      msg.className = 'field-msg error';
    } else {
      el.classList.remove('input-error'); el.classList.add('input-ok');
      msg.innerHTML = '<i class="bi bi-check-circle-fill"></i> E-mail válido';
      msg.className = 'field-msg ok';
    }
  }

  // ── Submit: validar e mostrar sucesso ──────────────────────
//   function doRecover(btn) {
//     const el  = document.getElementById('forgotEmail');
//     const msg = document.getElementById('msgEmail');
//     const v   = el.value.trim();

//     if (!v || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
//       el.classList.add('input-error');
//       msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> Informe um e-mail válido.';
//       msg.className = 'field-msg error';
//       el.focus();
//       return;
//     }

//     // Loading
//     const orig = btn.innerHTML;
//     btn.innerHTML = `<span class="spinner-border spinner-border-sm"
//       style="width:.9rem;height:.9rem;border-width:2px;"></span> Enviando...`;
//     btn.style.pointerEvents = 'none';

//     setTimeout(() => {
//       // Exibe e-mail na tela de sucesso
//       document.getElementById('emailPreviewText').textContent = v;

//       // Troca de view com fade
//       const form    = document.getElementById('viewForm');
//       const success = document.getElementById('viewSuccess');
//       form.style.opacity    = '0';
//       form.style.transform  = 'translateY(-12px)';
//       setTimeout(() => {
//         form.classList.add('hidden');
//         success.classList.remove('hidden');
//         success.style.opacity   = '1';
//         success.style.transform = 'translateY(0)';
//         startCountdown();
//       }, 350);
//     }, 1700);
//   }

  // ── Contador para reenvio ──────────────────────────────────
  let countdownInterval = null;

  function startCountdown() {
    let seconds = 60;
    const countEl  = document.getElementById('countdown');
    const labelEl  = document.getElementById('resendLabel');
    const btnEl    = document.getElementById('btnResend');

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

  // ── Reenvio ────────────────────────────────────────────────
//   function doResend(btn) {
//     if (btn.disabled) return;
//     btn.disabled = true;
//     btn.classList.remove('ready');

//     const orig = btn.innerHTML;
//     btn.innerHTML = `<span class="spinner-border spinner-border-sm"
//       style="width:.8rem;height:.8rem;border-width:2px;"></span> Enviando...`;

//     setTimeout(() => {
//       btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Enviado!';
//       setTimeout(() => {
//         // Reinicia o timer
//         document.getElementById('timerBar').style.animation = 'none';
//         requestAnimationFrame(() => {
//           document.getElementById('timerBar').style.animation =
//             'progressFill 60s linear reverse forwards';
//         });
//         btn.innerHTML = `Reenviar em <span id="countdown">60</span>s`;
//         startCountdown();
//       }, 1500);
//     }, 1400);
//   }
async function doResend(btn) {

  if (btn.disabled) return;

  const email = document.getElementById('forgotEmail').value;

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
      document.getElementById('forgot-password').action,
      {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
          'Accept': 'application/json',
        },
        body: new FormData(
          document.getElementById('forgot-password')
        )
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
          'progressFill 60s linear reverse forwards';
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

  // ── Enter dispara envio ────────────────────────────────────
//   document.getElementById('forgotEmail').addEventListener('keydown', e => {
//     if (e.key === 'Enter') {
//       const btn = document.querySelector('.btn-recover');
//       if (btn) btn.click();
//     }
//   });
document
  .getElementById('forgot-password')
  .addEventListener('submit', async function (event) {

    event.preventDefault();

    const form  = this;
    const input = document.getElementById('forgotEmail');
    const msg   = document.getElementById('msgEmail');
    const btn   = document.querySelector('.btn-recover');

    const email = input.value.trim();

    // Reset visual
    input.classList.remove('input-error');

    // Validação front-end
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {

      input.classList.add('input-error');

      msg.innerHTML =
        '<i class="bi bi-x-circle-fill"></i> Informe um e-mail válido.';

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

      // Mostra e-mail na tela
      document.getElementById('emailPreviewText')
        .textContent = email;

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
        <i class="bi bi-key-fill"></i>
        Recuperar Senha
      `;

      msg.innerHTML =
        '<i class="bi bi-x-circle-fill"></i> Não foi possível enviar o e-mail.';

      msg.className = 'field-msg error';
    }
});
</script>
</body>
</html>