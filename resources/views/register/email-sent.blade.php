<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Verificação de e-mail para candidatos do Vestibulinho 2025." />
  <title>Verificação de E-mail — Vestibulinho 2025</title>
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
      --green:      #27ae60;
      --green2:     #1e8449;
      --light:      #EEF3FA;
      --muted:      #6B7FA3;
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
    @keyframes fadeDown   { from{opacity:0;transform:translateY(-22px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeUp     { from{opacity:0;transform:translateY(22px)}  to{opacity:1;transform:translateY(0)} }
    @keyframes fadeIn     { from{opacity:0} to{opacity:1} }
    @keyframes float      { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }
    @keyframes gradShift  { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    @keyframes scaleIn    { from{opacity:0;transform:scale(.93)} to{opacity:1;transform:scale(1)} }
    @keyframes envelope   { 0%,100%{transform:translateY(0) rotate(-2deg)} 50%{transform:translateY(-10px) rotate(2deg)} }
    @keyframes ripple     { 0%{transform:scale(1);opacity:.6} 100%{transform:scale(2.2);opacity:0} }
    @keyframes blink      { 0%,100%{opacity:1} 50%{opacity:.3} }

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
               border:1px solid rgba(0,168,150,.2); animation:float 7s ease-in-out infinite; }
    .deco-blob {
      position:absolute; border-radius:50%; pointer-events:none;
      background:radial-gradient(circle, rgba(0,168,150,.14) 0%, transparent 65%);
      width:500px; height:500px; bottom:-60px; right:-80px;
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

    /* Timeline de cadastro no painel */
    .reg-timeline { margin-top:2.2rem; display:flex; flex-direction:column; gap:0; }
    .rt-item {
      display:flex; gap:1rem; position:relative;
      animation:fadeUp .6s ease both;
    }
    .rt-item:nth-child(1){ animation-delay:.5s; }
    .rt-item:nth-child(2){ animation-delay:.65s; }
    .rt-item:nth-child(3){ animation-delay:.8s; }
    .rt-item:nth-child(4){ animation-delay:.95s; }
    .rt-item:not(:last-child)::after {
      content:'';
      position:absolute; left:19px; top:40px;
      width:2px; height:calc(100% - 8px);
      background:linear-gradient(to bottom, rgba(255,255,255,.15), rgba(255,255,255,.04));
    }
    .rt-node {
      width:40px; height:40px; border-radius:50%; flex-shrink:0;
      display:flex; align-items:center; justify-content:center;
      font-size:.85rem; position:relative; z-index:1;
    }
    .rt-node.done {
      background:rgba(0,168,150,.3); border:1.5px solid var(--teal); color:var(--teal);
    }
    .rt-node.active {
      background:var(--grad-amber); color:var(--navy);
      font-weight:800; font-family:var(--font-head);
      box-shadow:0 0 0 4px rgba(244,162,97,.2);
    }
    /* Pulso no nó ativo */
    .rt-node.active::before {
      content:''; position:absolute; inset:0; border-radius:50%;
      border:2px solid var(--amber); animation:ripple 1.8s ease-out infinite;
    }
    .rt-node.pending {
      background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.15);
      color:rgba(255,255,255,.35); font-weight:700; font-family:var(--font-head);
    }
    .rt-body { padding:.55rem 0 1.5rem; }
    .rt-body strong { display:block; font-size:.85rem; font-weight:700; color:#fff; margin-bottom:.15rem; }
    .rt-body span   { font-size:.78rem; color:rgba(255,255,255,.55); line-height:1.5; }

    /* Rodapé */
    .panel-footer {
      position:relative; z-index:2;
      font-size:.73rem; color:rgba(255,255,255,.3);
      animation:fadeIn 1s ease 1s both;
    }

    /* ═══════════ PAINEL DIREITO ═════════════════════════════════ */
    .panel-right {
      background:#fff;
      display:flex; align-items:center; justify-content:center;
      padding:3rem 2.5rem; position:relative; overflow-y:auto;
    }
    .content-card {
      width:100%; max-width:420px;
      animation:scaleIn .55s cubic-bezier(.22,.68,0,1.2) .1s both;
    }

    /* Ícone de envelope animado */
    .envelope-wrap {
      position:relative; width:90px; height:90px;
      margin:0 auto 1.8rem; display:flex; align-items:center; justify-content:center;
    }
    .envelope-bg {
      width:90px; height:90px; border-radius:50%;
      background:linear-gradient(135deg,rgba(0,168,150,.12),rgba(0,168,150,.06));
      border:1.5px solid rgba(0,168,150,.2);
      display:flex; align-items:center; justify-content:center;
      animation:envelope 4s ease-in-out infinite;
    }
    .envelope-bg i { font-size:2.3rem; color:var(--teal); }
    /* Ponto pulsante de "mensagem nova" */
    .envelope-dot {
      position:absolute; top:6px; right:6px;
      width:14px; height:14px; border-radius:50%;
      background:var(--amber); border:2px solid #fff;
      animation:blink 1.4s ease-in-out infinite;
    }

    /* Badge */
    .form-badge {
      display:inline-flex; align-items:center; gap:.5rem;
      background:rgba(0,168,150,.08); border:1px solid rgba(0,168,150,.2);
      color:var(--teal2); border-radius:50px;
      padding:.3rem 1rem; font-size:.73rem; font-weight:700;
      letter-spacing:.07em; text-transform:uppercase;
      margin-bottom:1rem;
    }

    /* Título */
    .page-title {
      font-size:1.6rem; font-weight:800; color:var(--navy);
      line-height:1.2; margin-bottom:.5rem;
    }
    .page-sub {
      font-size:.88rem; color:var(--muted); line-height:1.65;
    }

    /* Alert de mensagem */
    .msg-box {
      background:rgba(0,168,150,.05);
      border:1px solid rgba(0,168,150,.18);
      border-left:4px solid var(--teal);
      border-radius:0 var(--radius) var(--radius) 0;
      padding:1.1rem 1.3rem;
      margin:1.5rem 0;
    }
    .msg-box .msg-title {
      display:flex; align-items:center; gap:.5rem;
      font-family:var(--font-head); font-size:.85rem; font-weight:700;
      color:var(--teal2); margin-bottom:.65rem;
    }
    .msg-box p {
      font-size:.85rem; color:var(--muted); line-height:1.75; margin:0;
    }
    .msg-box strong { color:var(--navy); }
    .msg-box em     { color:var(--teal2); font-style:normal; font-weight:600; }

    /* Chips informativos */
    .info-chips { display:flex; flex-wrap:wrap; gap:.5rem; margin:1.2rem 0; }
    .info-chip {
      display:inline-flex; align-items:center; gap:.4rem;
      background:var(--light); border:1px solid rgba(11,30,61,.08);
      border-radius:50px; padding:.35rem .9rem;
      font-size:.76rem; font-weight:600; color:var(--muted);
    }
    .info-chip i { color:var(--teal); font-size:.82rem; }

    /* Botão principal */
    .btn-access {
      width:100%; padding:.92rem; border:none; border-radius:50px;
      background:var(--grad-teal);
      color:#fff; font-family:var(--font-head); font-weight:700; font-size:.95rem;
      cursor:pointer; display:flex; align-items:center; justify-content:center; gap:.6rem;
      box-shadow:0 6px 22px rgba(0,168,150,.35);
      transition:transform .25s, box-shadow .25s;
      margin-bottom:1rem;
    }
    .btn-access:hover {
      transform:translateY(-3px); box-shadow:0 12px 34px rgba(0,168,150,.45); color:#fff;
    }



    /* ─── RESPONSIVO ──────────────────────────────────────────── */
    @media(max-width:900px) {
      .page-wrapper { grid-template-columns:1fr; }
      .panel-left   { display:none; }
      .panel-right  { padding:2.5rem 1.5rem; min-height:100vh; align-items:flex-start; }
      .content-card { padding-top:1.5rem; }
    }
    @media(max-width:480px) {
      .panel-right { padding:2rem 1.2rem; }
      .info-chips  { flex-direction:column; }
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
      <h2>EM Dr. Francisco de Souza</h2>
      <p>Vestibulinho 2025 · Cursos Técnicos Gratuitos</p>
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
      © 2025 EM Dr. Francisco de Souza · Todos os direitos reservados
    </div>
  </aside>

  <!-- ═══════════════════ PAINEL DIREITO ════════════════════════ -->
  <main class="panel-right">
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
        <p class="page-sub">
          Um link de verificação foi enviado para o<br>endereço cadastrado no Vestibulinho 2025.
        </p>
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
      <a href="login.html" class="btn-access">
        <i class="bi bi-box-arrow-in-right"></i> Acessar Área do Candidato
      </a>


    </div>
  </main>

</div><!-- /page-wrapper -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
