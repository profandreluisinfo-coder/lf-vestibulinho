<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vestibulinho {{ config('app.year') }} · EM Dr. Leandro Franceschini</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    :root {
      --navy:   #0b1f3a;
      --navy-light: #1a3a5c;
      --gold:   #e8b45a;
      --gold-dark:  #c9933a;
      --cream:  #faf6ef;
      --muted:  #6b7a8d;
      --light-rule: rgba(11,31,58,.08);
      --radius: 12px;
    }

    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--cream);
      color: var(--navy);
      overflow-x: hidden;
    }

    /* ════ TYPOGRAPHY ════ */
    h1, h2, h3, .serif { font-family: 'DM Serif Display', serif; letter-spacing: -.02em; }
    p { line-height: 1.7; }

    /* ════ NAVBAR ════ */
    .navbar-custom {
      background: var(--navy);
      box-shadow: 0 4px 30px rgba(0,0,0,.15);
      position: sticky;
      top: 0;
      z-index: 1050;
      padding: 1rem 0;
    }
    .navbar-brand {
      font-family: 'DM Serif Display', serif;
      color: var(--gold) !important;
      font-size: 1.3rem;
      font-weight: 600;
      letter-spacing: -.5px;
    }
    .navbar-brand small {
      display: block;
      font-family: 'DM Sans', sans-serif;
      font-size: .65rem;
      font-weight: 600;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: rgba(232,180,90,.6);
      margin-top: 2px;
    }
    .navbar-custom .nav-link {
      color: rgba(255,255,255,.7) !important;
      font-size: .9rem;
      font-weight: 500;
      padding: .5rem 1rem !important;
      transition: all .3s ease;
      position: relative;
    }
    .navbar-custom .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0; left: 0;
      width: 0;
      height: 2px;
      background: var(--gold);
      transition: width .3s ease;
    }
    .navbar-custom .nav-link:hover::after,
    .navbar-custom .nav-link.active::after { width: 100%; }
    .navbar-custom .nav-link:hover { color: #fff !important; }
    .btn-cta-nav {
      background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
      color: var(--navy) !important;
      font-weight: 700;
      font-size: .8rem;
      letter-spacing: .06em;
      text-transform: uppercase;
      border-radius: var(--radius);
      padding: .6rem 1.5rem;
      box-shadow: 0 4px 15px rgba(232,180,90,.3);
      transition: all .3s ease;
      border: none;
    }
    .btn-cta-nav:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(232,180,90,.4); }

    /* ════ HERO ════ */
    .hero-section {
      background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
      position: relative;
      min-height: 90vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .hero-section::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at 80% 20%, rgba(232,180,90,.15) 0%, transparent 40%),
        radial-gradient(circle at 20% 80%, rgba(232,180,90,.1) 0%, transparent 40%);
      pointer-events: none;
    }
    .hero-grid {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
      background-size: 80px 80px;
      pointer-events: none;
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      background: rgba(232,180,90,.15);
      color: var(--gold);
      font-size: .8rem;
      font-weight: 600;
      letter-spacing: .1em;
      text-transform: uppercase;
      padding: .6rem 1.2rem;
      border-radius: 50px;
      border: 1px solid rgba(232,180,90,.3);
      margin-bottom: 1.5rem;
      animation: slideInDown .6s ease;
    }
    .hero-content h1 {
      font-size: clamp(2.2rem, 5.5vw, 4.2rem);
      color: #fff;
      line-height: 1.15;
      margin-bottom: 1rem;
      animation: slideInUp .7s ease .15s both;
    }
    .hero-content h1 .highlight {
      background: linear-gradient(135deg, var(--gold) 0%, #f5c85b 100%);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .hero-lead {
      font-size: 1.1rem;
      color: rgba(255,255,255,.75);
      max-width: 550px;
      margin-bottom: 2rem;
      animation: slideInUp .7s ease .3s both;
    }
    .hero-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      animation: slideInUp .7s ease .45s both;
    }
    .btn-primary-hero {
      background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
      color: var(--navy) !important;
      font-weight: 700;
      font-size: .9rem;
      letter-spacing: .05em;
      text-transform: uppercase;
      border-radius: var(--radius);
      padding: 1rem 2.5rem;
      border: none;
      box-shadow: 0 8px 24px rgba(232,180,90,.35);
      transition: all .3s ease;
    }
    .btn-primary-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 36px rgba(232,180,90,.45);
    }
    .btn-secondary-hero {
      background: rgba(255,255,255,.12);
      border: 1.5px solid rgba(255,255,255,.3);
      color: #fff !important;
      font-weight: 600;
      font-size: .9rem;
      border-radius: var(--radius);
      padding: 1rem 2.5rem;
      transition: all .3s ease;
    }
    .btn-secondary-hero:hover {
      background: rgba(255,255,255,.18);
      border-color: var(--gold);
    }

    /* Hero Stats */
    .hero-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 2rem;
      margin-top: 3.5rem;
      padding-top: 2.5rem;
      border-top: 1px solid rgba(255,255,255,.1);
      animation: slideInUp .7s ease .6s both;
    }
    .stat-card {
      text-align: center;
    }
    .stat-value {
      font-family: 'DM Serif Display', serif;
      font-size: 2.2rem;
      color: var(--gold);
      font-weight: 700;
    }
    .stat-label {
      font-size: .75rem;
      font-weight: 600;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: rgba(255,255,255,.5);
      margin-top: .5rem;
    }

    /* ════ ALERTS SECTION ════ */
    .alerts-section {
      background: #fff;
      padding: 1.5rem 0;
    }
    .alert-banner {
      background: linear-gradient(135deg, rgba(232,180,90,.1) 0%, rgba(232,180,90,.05) 100%);
      border-left: 4px solid var(--gold);
      border-radius: var(--radius);
      padding: 1.2rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      animation: slideInLeft .6s ease;
    }
    .alert-banner i {
      font-size: 1.5rem;
      color: var(--gold-dark);
      flex-shrink: 0;
    }
    .alert-banner p { margin: 0; font-size: .95rem; }

    /* ════ COUNTDOWN SECTION ════ */
    .countdown-section {
      background: linear-gradient(135deg, rgba(232,180,90,.08) 0%, rgba(232,180,90,.03) 100%);
      padding: 3rem 0;
      border-top: 1px solid var(--light-rule);
      border-bottom: 1px solid var(--light-rule);
    }
    .countdown-title {
      font-size: clamp(1.4rem, 3vw, 2.2rem);
      color: var(--navy);
      margin-bottom: 2rem;
      text-align: center;
    }
    .countdown-box {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
      gap: 1.5rem;
      justify-content: center;
    }
    .countdown-item {
      background: #fff;
      border-radius: var(--radius);
      padding: 1.5rem 1rem;
      text-align: center;
      box-shadow: 0 4px 15px rgba(11,31,58,.08);
      transition: all .3s ease;
      border: 1px solid var(--light-rule);
    }
    .countdown-item:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(11,31,58,.12);
      border-color: rgba(232,180,90,.3);
    }
    .countdown-num {
      font-family: 'DM Serif Display', serif;
      font-size: 2.4rem;
      color: var(--gold);
      font-weight: 700;
      line-height: 1;
    }
    .countdown-label {
      font-size: .75rem;
      font-weight: 600;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--muted);
      margin-top: .75rem;
    }

    /* ════ TIMELINE (CRONOGRAMA) ════ */
    .timeline-section {
      padding: 5rem 0;
      background: #fff;
    }
    .section-header {
      text-align: center;
      margin-bottom: 4rem;
    }
    .section-label {
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--gold-dark);
      margin-bottom: 1rem;
    }
    .section-title {
      font-size: clamp(1.8rem, 3.5vw, 2.8rem);
      color: var(--navy);
      margin-bottom: 1rem;
    }
    .gold-rule {
      width: 50px;
      height: 3px;
      background: var(--gold);
      margin: 1rem auto;
    }
    .timeline {
      position: relative;
      padding-left: 0;
    }
    .timeline::before {
      content: '';
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      top: 0;
      bottom: 0;
      width: 2px;
      background: linear-gradient(to bottom, var(--gold), transparent);
    }
    .timeline-item {
      margin-bottom: 3rem;
      position: relative;
    }
    .timeline-item:nth-child(odd) { text-align: right; padding-right: 52%; }
    .timeline-item:nth-child(even) { text-align: left; padding-left: 52%; }
    .timeline-dot {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      top: 10px;
      width: 20px;
      height: 20px;
      background: var(--cream);
      border: 3px solid var(--gold);
      border-radius: 50%;
      z-index: 10;
      transition: all .3s ease;
    }
    .timeline-item:hover .timeline-dot {
      background: var(--gold);
      transform: translateX(-50%) scale(1.3);
    }
    .timeline-content {
      background: #fff;
      padding: 1.8rem;
      border-radius: var(--radius);
      box-shadow: 0 4px 12px rgba(11,31,58,.08);
      border-left: 3px solid var(--light-rule);
      transition: all .3s ease;
    }
    .timeline-item:hover .timeline-content {
      border-left-color: var(--gold);
      box-shadow: 0 8px 24px rgba(11,31,58,.12);
    }
    .timeline-date {
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--gold-dark);
      margin-bottom: .5rem;
    }
    .timeline-title {
      font-size: 1rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: .5rem;
    }
    .timeline-desc {
      font-size: .9rem;
      color: var(--muted);
    }

    /* ════ COURSES ════ */
    .courses-section {
      padding: 5rem 0;
      background: var(--cream);
    }
    .courses-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 2rem;
    }
    .course-card {
      background: #fff;
      border-radius: var(--radius);
      padding: 2rem;
      box-shadow: 0 4px 12px rgba(11,31,58,.08);
      border: 1px solid var(--light-rule);
      transition: all .3s ease;
      position: relative;
      overflow: hidden;
    }
    .course-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--gold);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .3s ease;
    }
    .course-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 32px rgba(11,31,58,.15);
    }
    .course-card:hover::before { transform: scaleX(1); }
    .course-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, rgba(232,180,90,.15) 0%, rgba(232,180,90,.08) 100%);
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.6rem;
      color: var(--gold-dark);
      margin-bottom: 1.2rem;
    }
    .course-name {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: .5rem;
    }
    .course-desc {
      font-size: .9rem;
      color: var(--muted);
      margin: 1rem 0;
      line-height: 1.6;
    }
    .course-vacancies {
      font-size: .85rem;
      font-weight: 600;
      color: var(--muted);
      margin-top: 1rem;
    }
    .course-vacancies strong { color: var(--gold-dark); }
    .course-badge {
      display: inline-block;
      background: rgba(232,180,90,.15);
      color: var(--gold-dark);
      font-size: .7rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      padding: .35rem .8rem;
      border-radius: 50px;
      margin-top: 1rem;
    }

    /* ════ GUIDELINES (Como Inscrever) ════ */
    .guidelines-section {
      padding: 5rem 0;
      background: #fff;
    }
    .guidelines-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }
    .guide-card {
      background: linear-gradient(135deg, rgba(232,180,90,.05) 0%, rgba(232,180,90,.02) 100%);
      border-radius: var(--radius);
      padding: 2rem;
      text-align: center;
      border: 1px solid rgba(232,180,90,.15);
      transition: all .3s ease;
    }
    .guide-card:hover {
      transform: translateY(-6px);
      border-color: var(--gold);
      box-shadow: 0 8px 20px rgba(232,180,90,.15);
    }
    .guide-number {
      font-family: 'DM Serif Display', serif;
      font-size: 2.8rem;
      color: var(--gold);
      font-weight: 700;
      line-height: 1;
      margin-bottom: 1rem;
    }
    .guide-title {
      font-size: 1rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: .5rem;
    }
    .guide-desc {
      font-size: .9rem;
      color: var(--muted);
      line-height: 1.6;
    }
    .guide-icon {
      font-size: 2.5rem;
      color: var(--gold);
      margin-bottom: .5rem;
      opacity: .3;
    }

    /* ════ FAQ ════ */
    .faq-section {
      padding: 5rem 0;
      background: linear-gradient(135deg, rgba(232,180,90,.05) 0%, rgba(232,180,90,.02) 100%);
    }
    .faq-container {
      max-width: 800px;
      margin: 0 auto;
    }
    .accordion-button {
      background: #fff !important;
      color: var(--navy) !important;
      font-weight: 600;
      font-size: .95rem;
      border-bottom: 1px solid var(--light-rule) !important;
      box-shadow: none !important;
      padding: 1.2rem 1.5rem;
      transition: all .3s ease;
    }
    .accordion-button:not(.collapsed) {
      color: var(--gold-dark) !important;
      background: rgba(232,180,90,.05) !important;
    }
    .accordion-button::after {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230b1f3a'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
      transition: transform .3s ease;
    }
    .accordion-item {
      background: #fff;
      border: 1px solid var(--light-rule) !important;
      margin-bottom: 1rem;
      border-radius: var(--radius) !important;
      overflow: hidden;
    }
    .accordion-body {
      padding: 1.5rem;
      font-size: .95rem;
      color: var(--muted);
      background: rgba(232,180,90,.02);
    }

    /* ════ CTA SECTION ════ */
    .cta-final {
      background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
      padding: 4rem 0;
      position: relative;
      overflow: hidden;
    }
    .cta-final::before {
      content: 'INSCREVA-SE AGORA';
      position: absolute;
      font-family: 'DM Serif Display', serif;
      font-size: clamp(2rem, 10vw, 8rem);
      color: rgba(11,31,58,.08);
      white-space: nowrap;
      right: -5%;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      font-weight: 700;
    }
    .cta-content {
      position: relative;
      z-index: 1;
    }
    .cta-content h2 { color: var(--navy); font-size: clamp(1.8rem, 4vw, 2.8rem); }
    .cta-content p { color: rgba(11,31,58,.7); font-size: 1rem; }
    .btn-cta-final {
      background: var(--navy);
      color: #fff !important;
      font-weight: 700;
      font-size: .9rem;
      letter-spacing: .06em;
      text-transform: uppercase;
      border-radius: var(--radius);
      padding: 1rem 2.5rem;
      border: none;
      transition: all .3s ease;
      box-shadow: 0 6px 20px rgba(11,31,58,.25);
    }
    .btn-cta-final:hover {
      background: var(--navy-light);
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(11,31,58,.35);
    }

    /* ════ QUICK ACCESS ════ */
    .quick-access-section {
      padding: 3rem 0;
      background: #fff;
    }
    .quick-access-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 1.5rem;
    }
    .quick-access-link {
      background: linear-gradient(135deg, rgba(232,180,90,.1) 0%, rgba(232,180,90,.05) 100%);
      border-radius: var(--radius);
      padding: 1.8rem;
      text-align: center;
      text-decoration: none !important;
      color: var(--navy) !important;
      border: 1px solid rgba(232,180,90,.2);
      transition: all .3s ease;
    }
    .quick-access-link:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(232,180,90,.15);
      border-color: var(--gold);
    }
    .quick-access-icon {
      font-size: 2.2rem;
      color: var(--gold);
      margin-bottom: .8rem;
    }
    .quick-access-text {
      font-size: .85rem;
      font-weight: 600;
      letter-spacing: .05em;
    }

    /* ════ FOOTER ════ */
    footer {
      background: #060f1e;
      color: rgba(255,255,255,.5);
      font-size: .85rem;
      padding: 4rem 0 1rem;
    }
    footer .brand {
      font-family: 'DM Serif Display', serif;
      color: var(--gold);
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    footer a {
      color: rgba(255,255,255,.5);
      text-decoration: none;
      transition: color .3s ease;
    }
    footer a:hover { color: var(--gold); }
    footer .footer-section-title {
      font-size: .7rem;
      font-weight: 700;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: rgba(255,255,255,.3);
      margin-bottom: 1.2rem;
    }
    footer .footer-divider { border-color: rgba(255,255,255,.08); }
    footer .social-links { display: flex; gap: 1rem; margin-top: 1.2rem; }
    footer .social-links a { font-size: 1.2rem; }

    /* ════ ANIMATIONS ════ */
    @keyframes slideInDown {
      from { opacity: 0; transform: translateY(-20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
      from { opacity: 0; transform: translateX(-20px); }
      to   { opacity: 1; transform: translateX(0); }
    }
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity .7s ease, transform .7s ease;
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* ════ RESPONSIVE ════ */
    @media (max-width: 768px) {
      .timeline::before { display: none; }
      .timeline-item:nth-child(odd),
      .timeline-item:nth-child(even) {
        text-align: left;
        padding-left: 0;
        padding-right: 0;
      }
      .timeline-dot { display: none; }
      .timeline-content { border-left-color: var(--gold) !important; }
    }
  </style>
</head>
<body>

<!-- ════════════════════════════════════════════════════
     NAVBAR
════════════════════════════════════════════════════ -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">
      EM Dr. Leandro Franceschini
      <small>Ensino Técnico</small>
    </a>
    <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <i class="bi bi-list fs-4"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item"><a class="nav-link" href="#cronograma">Cronograma</a></li>
        <li class="nav-item"><a class="nav-link" href="#cursos">Cursos</a></li>
        <li class="nav-item"><a class="nav-link" href="#como">Como se Inscrever</a></li>
        <li class="nav-item"><a class="nav-link" href="#faq">Dúvidas</a></li>
        <li class="nav-item ms-lg-2 mt-3 mt-lg-0">
          <a class="btn btn-cta-nav" href="{{ route('login') }}">Inscrever-se</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ════════════════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════════════════ -->
<section class="hero-section">
  <div class="hero-grid"></div>
  <div class="container position-relative">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="hero-badge">
          <i class="bi bi-mortarboard-fill"></i>
          Vestibulinho {{ config('app.year') }}
        </div>
        <h1 class="hero-content">
          Seu futuro em<br>
          <span class="highlight">ensino técnico</span><br>
          de qualidade começa aqui.
        </h1>
        <p class="hero-lead">
          Processo seletivo 100% gratuito com vagas em cursos técnicos reconhecidos.
          Inscrições abertas até 30 de setembro.
        </p>
        <div class="hero-buttons">
          <a href="{{ route('login') }}" class="btn btn-primary-hero">
            <i class="bi bi-pencil-square me-2"></i>Fazer Inscrição
          </a>
          <a href="#cursos" class="btn btn-secondary-hero">
            <i class="bi bi-book me-2"></i>Conhecer Cursos
          </a>
        </div>

        <!-- Stats -->
        <div class="hero-stats">
          <div class="stat-card">
            <div class="stat-value">1.200+</div>
            <div class="stat-label">Vagas</div>
          </div>
          <div class="stat-card">
            <div class="stat-value">{{ $courses->count() ?? 8 }}</div>
            <div class="stat-label">Cursos</div>
          </div>
          <div class="stat-card">
            <div class="stat-value">100%</div>
            <div class="stat-label">Gratuito</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     ALERTS
════════════════════════════════════════════════════ -->
<section class="alerts-section">
  <div class="container">
    <div class="alert-banner reveal">
      <i class="bi bi-info-circle-fill"></i>
      <div>
        <p><strong>Atenção:</strong> As inscrições são realizadas exclusivamente através do portal online. Não há taxa de inscrição.</p>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     COUNTDOWN
════════════════════════════════════════════════════ -->
@if ($calendar->isInscriptionOpen())
<section class="countdown-section">
  <div class="container">
    <h2 class="countdown-title reveal">
      <i class="bi bi-hourglass-split me-2"></i>Contagem Regressiva para o Fim das Inscrições
    </h2>
    <div class="countdown-box reveal" style="transition-delay: 0.1s">
      <div class="countdown-item">
        <div class="countdown-num" id="dias">00</div>
        <div class="countdown-label">Dias</div>
      </div>
      <div class="countdown-item">
        <div class="countdown-num" id="horas">00</div>
        <div class="countdown-label">Horas</div>
      </div>
      <div class="countdown-item">
        <div class="countdown-num" id="minutos">00</div>
        <div class="countdown-label">Minutos</div>
      </div>
      <div class="countdown-item">
        <div class="countdown-num" id="segundos">00</div>
        <div class="countdown-label">Segundos</div>
      </div>
    </div>
  </div>
</section>
@endif

<!-- ════════════════════════════════════════════════════
     TIMELINE / CRONOGRAMA
════════════════════════════════════════════════════ -->
<section class="timeline-section" id="cronograma">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-label">Passo a Passo</div>
      <h2 class="section-title">Cronograma do Processo Seletivo</h2>
      <div class="gold-rule"></div>
    </div>

    <div class="timeline reveal" style="transition-delay: 0.1s">
      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-date">01 – 30 de Setembro</div>
          <div class="timeline-title">Período de Inscrições</div>
          <div class="timeline-desc">Inscrições gratuitas pelo portal oficial com documentos: RG, CPF e histórico escolar.</div>
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-date">10 de Outubro</div>
          <div class="timeline-title">Divulgação dos Locais de Prova</div>
          <div class="timeline-desc">Consulte seu local de prova e horário com seu número de inscrição no portal.</div>
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-date">19 de Outubro</div>
          <div class="timeline-title">Aplicação das Provas</div>
          <div class="timeline-desc">Prova objetiva com 40 questões de Português, Matemática e Ciências. Duração: 3h.</div>
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-date">05 de Novembro</div>
          <div class="timeline-title">Divulgação do Gabarito</div>
          <div class="timeline-desc">Gabarito preliminar disponível. Prazo para recursos: 3 dias úteis.</div>
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-date">20 de Novembro</div>
          <div class="timeline-title">Resultado Final</div>
          <div class="timeline-desc">Classificados convocados para entrega de documentos e matrícula presencial.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     CURSOS
════════════════════════════════════════════════════ -->
<section class="courses-section" id="cursos">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-label">Oferta {{ config('app.year') }}</div>
      <h2 class="section-title">Cursos Técnicos Disponíveis</h2>
      <div class="gold-rule"></div>
      <p style="color: var(--muted); margin-top: 1rem; font-size: .95rem;">
        Todos os cursos são gratuitos e integrados ao Ensino Médio (4 anos).
      </p>
    </div>

    <div class="courses-grid reveal" style="transition-delay: 0.1s">
      @foreach($courses as $index => $course)
      <div class="course-card" style="transition-delay: {{ $index * 0.08 }}s">
        <div class="course-icon">
          <i class="bi bi-book"></i>
        </div>
        <div class="course-name">{{ $course->name }}</div>
        <p class="course-desc">{{ $course->info }}</p>
        <div class="course-vacancies">
          <i class="bi bi-door-open me-1"></i>
          <strong>{{ $course->vacancies }}</strong> vagas
        </div>
        <span class="course-badge">{{ $course->duration ?? 4 }} anos</span>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     COMO SE INSCREVER (GUIDELINES)
════════════════════════════════════════════════════ -->
<section class="guidelines-section" id="como">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-label">Passo a Passo</div>
      <h2 class="section-title">Como se Inscrever</h2>
      <div class="gold-rule"></div>
      <p style="color: var(--muted); margin-top: 1rem; font-size: .95rem;">
        Processo 100% online, rápido e gratuito.
      </p>
    </div>

    <div class="guidelines-grid reveal" style="transition-delay: 0.1s">
      <div class="guide-card">
        <div class="guide-number">01</div>
        <div class="guide-icon"><i class="bi bi-person-plus"></i></div>
        <div class="guide-title">Registre seus dados</div>
        <div class="guide-desc">Acesse o formulário, informe um e-mail válido e crie uma senha segura.</div>
      </div>

      <div class="guide-card" style="transition-delay: 0.08s">
        <div class="guide-number">02</div>
        <div class="guide-icon"><i class="bi bi-envelope-check"></i></div>
        <div class="guide-title">Confirme seu e-mail</div>
        <div class="guide-desc">Verifique sua caixa de entrada e clique no link de confirmação enviado.</div>
      </div>

      <div class="guide-card" style="transition-delay: 0.16s">
        <div class="guide-number">03</div>
        <div class="guide-icon"><i class="bi bi-patch-check"></i></div>
        <div class="guide-title">Valide seu cadastro</div>
        <div class="guide-desc">Sem essa validação não será possível realizar sua inscrição.</div>
      </div>

      <div class="guide-card" style="transition-delay: 0.24s">
        <div class="guide-number">04</div>
        <div class="guide-icon"><i class="bi bi-file-earmark-text"></i></div>
        <div class="guide-title">Preencha a inscrição</div>
        <div class="guide-desc">Acesse a Área do Candidato e envie seus dados pessoais e acadêmicos.</div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     ACESSO RÁPIDO
════════════════════════════════════════════════════ -->
<section class="quick-access-section">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-label">Recursos Úteis</div>
      <h2 class="section-title">Acesso Rápido</h2>
      <div class="gold-rule"></div>
    </div>

    <div class="quick-access-grid reveal" style="transition-delay: 0.1s">
      <a href="#faq" class="quick-access-link">
        <div class="quick-access-icon"><i class="bi bi-question-circle"></i></div>
        <div class="quick-access-text">Perguntas<br>Frequentes</div>
      </a>
      <a href="#" class="quick-access-link">
        <div class="quick-access-icon"><i class="bi bi-file-text"></i></div>
        <div class="quick-access-text">Provas &<br>Gabaritos</div>
      </a>
      <a href="#" class="quick-access-link">
        <div class="quick-access-icon"><i class="bi bi-file-pdf"></i></div>
        <div class="quick-access-text">Edital<br>Completo</div>
      </a>
      <a href="{{ route('login') }}" class="quick-access-link">
        <div class="quick-access-icon"><i class="bi bi-person-lock"></i></div>
        <div class="quick-access-text">Área do<br>Candidato</div>
      </a>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     FAQ
════════════════════════════════════════════════════ -->
<section class="faq-section" id="faq">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-label">Tire suas Dúvidas</div>
      <h2 class="section-title">Perguntas Frequentes</h2>
      <div class="gold-rule"></div>
    </div>

    <div class="faq-container reveal" style="transition-delay: 0.1s">
      <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
              <i class="bi bi-question-circle me-2"></i>Qual é a faixa etária para se inscrever?
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Podem se inscrever alunos que tenham concluído ou estejam cursando o 9º ano do Ensino Fundamental. Não há limite máximo de idade para as vagas regulares.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
              <i class="bi bi-question-circle me-2"></i>A prova tem taxa de inscrição?
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Não. As inscrições e toda a participação no processo seletivo são completamente gratuitas.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
              <i class="bi bi-question-circle me-2"></i>O que cai na prova?
            </button>
          </h2>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              A prova contém 40 questões objetivas: 15 de Língua Portuguesa, 15 de Matemática e 10 de Ciências. O conteúdo é baseado no currículo do Ensino Fundamental II.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
              <i class="bi bi-question-circle me-2"></i>Posso me inscrever em mais de um curso?
            </button>
          </h2>
          <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Sim. É possível indicar uma segunda opção de curso no momento da inscrição. Em caso de não classificação, você será considerado para a segunda opção.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
              <i class="bi bi-question-circle me-2"></i>Há cotas ou reservas de vagas?
            </button>
          </h2>
          <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Sim. 50% das vagas são reservadas para candidatos de escolas públicas, com subdivisões para alunos pretos/pardos/indígenas e pessoas com deficiência (PCD), conforme legislação.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     CALL TO ACTION FINAL
════════════════════════════════════════════════════ -->
<section class="cta-final">
  <div class="container">
    <div class="cta-content reveal">
      <h2>Não fique de fora desta oportunidade</h2>
      <p style="margin-bottom: 2rem;">
        Inscrições abertas até 30 de setembro. Garanta sua vaga em um ensino técnico de qualidade.
      </p>
      <a href="{{ route('login') }}" class="btn btn-cta-final">
        <i class="bi bi-pencil-square me-2"></i>Iniciar Inscrição Agora
      </a>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════ -->
<footer>
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-lg-4">
        <div class="brand">EM Dr. Leandro Franceschini</div>
        <p style="font-size: .9rem; line-height: 1.8; margin-top: .75rem;">
          Ensino Técnico de qualidade, formando profissionais desde 1962.
        </p>
        <div class="social-links">
          <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" title="YouTube"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

      <div class="col-6 col-lg-2 offset-lg-1">
        <p class="footer-section-title">Vestibulinho</p>
        <ul class="list-unstyled" style="line-height: 2;">
          <li><a href="#">Edital Completo</a></li>
          <li><a href="#">Cronograma</a></li>
          <li><a href="#">Provas Anteriores</a></li>
          <li><a href="#">Resultado</a></li>
        </ul>
      </div>

      <div class="col-6 col-lg-2">
        <p class="footer-section-title">Instituição</p>
        <ul class="list-unstyled" style="line-height: 2;">
          <li><a href="#">Portal do Aluno</a></li>
          <li><a href="#">Sobre Nós</a></li>
          <li><a href="#">Contato</a></li>
          <li><a href="#">Localização</a></li>
        </ul>
      </div>

      <div class="col-lg-3">
        <p class="footer-section-title">Contato</p>
        <div style="line-height: 2.2;">
          <p style="margin: 0;">
            <i class="bi bi-telephone me-2" style="color: var(--gold);"></i>(19) 3873-2605
          </p>
          <p style="margin: 0;">
            <i class="bi bi-envelope me-2" style="color: var(--gold);"></i>
            <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br">
              emdrleandrofranceschini@educacaosumare.com.br
            </a>
          </p>
          <p style="margin: 0;">
            <i class="bi bi-clock me-2" style="color: var(--gold);"></i>Seg-Sex: 14h às 19h
          </p>
        </div>
      </div>
    </div>

    <hr class="footer-divider" />

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 pt-3" style="font-size: .78rem;">
      <span>© 2025 EM Dr. Leandro Franceschini. Todos os direitos reservados.</span>
      <span>
        <a href="#">Política de Privacidade</a> · <a href="#">Termos de Uso</a>
      </span>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Scroll Reveal Animation
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  // Active Nav Link
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.navbar-custom .nav-link');

  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => {
      if (window.scrollY >= s.offsetTop - 150) {
        current = s.getAttribute('id');
      }
    });
    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === '#' + current) {
        link.classList.add('active');
      }
    });
  });

  // Countdown Timer
  const deadline = document.querySelector('[id="dias"]')?.parentElement?.parentElement?.getAttribute('data-deadline');
  if (deadline) {
    const countdownInterval = setInterval(() => {
      const now = new Date().getTime();
      const distance = new Date(deadline).getTime() - now;

      const dias = Math.floor(distance / (1000 * 60 * 60 * 24));
      const horas = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutos = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const segundos = Math.floor((distance % (1000 * 60)) / 1000);

      document.getElementById('dias').textContent = String(dias).padStart(2, '0');
      document.getElementById('horas').textContent = String(horas).padStart(2, '0');
      document.getElementById('minutos').textContent = String(minutos).padStart(2, '0');
      document.getElementById('segundos').textContent = String(segundos).padStart(2, '0');

      if (distance < 0) clearInterval(countdownInterval);
    }, 1000);
  }
</script>
</body>
</html>
