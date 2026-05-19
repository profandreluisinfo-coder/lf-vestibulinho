<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Vestibulinho LF {{ config('app.year') }} — EM Dr. Leandro Franceschini</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <style>
    /* ─── TOKENS ─────────────────────────────────────────────── */
    :root {
      --navy:    #0B1E3D;
      --navy2:   #132948;
      --teal:    #00A896;
      --teal2:   #007F72;
      --amber:   #F4A261;
      --amber2:  #E07A3A;
      --light:   #EEF3FA;
      --white:   #FFFFFF;
      --muted:   #6B7FA3;
      --card-bg: #FFFFFF;
      --shadow:  0 8px 32px rgba(11,30,61,.12);
      --shadow-lg: 0 20px 60px rgba(11,30,61,.18);
      --radius:  16px;
      --radius-lg: 24px;
      --grad-main: linear-gradient(135deg, var(--navy) 0%, #1B3E72 60%, #0E4D6B 100%);
      --grad-teal: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
      --grad-amber: linear-gradient(135deg, var(--amber) 0%, var(--amber2) 100%);
      --font-head: 'Sora', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }

    /* ─── RESET & BASE ───────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: var(--font-body);
      background: var(--light);
      color: var(--navy);
      overflow-x: hidden;
    }
    h1,h2,h3,h4,h5 { font-family: var(--font-head); }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; }

    /* ─── SCROLL REVEAL ──────────────────────────────────────── */
    .reveal {
      opacity: 0;
      transform: translateY(40px);
      transition: opacity .7s ease, transform .7s ease;
    }
    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }
    .reveal-left  { opacity:0; transform:translateX(-50px); transition: opacity .7s ease, transform .7s ease; }
    .reveal-right { opacity:0; transform:translateX(50px);  transition: opacity .7s ease, transform .7s ease; }
    .reveal-left.visible, .reveal-right.visible { opacity:1; transform:translateX(0); }
    .delay-1 { transition-delay: .1s !important; }
    .delay-2 { transition-delay: .2s !important; }
    .delay-3 { transition-delay: .3s !important; }
    .delay-4 { transition-delay: .4s !important; }

    /* ─── KEYFRAMES ──────────────────────────────────────────── */
    @keyframes fadeDown   { from{opacity:0;transform:translateY(-30px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeUp     { from{opacity:0;transform:translateY(30px)}  to{opacity:1;transform:translateY(0)} }
    @keyframes fadeIn     { from{opacity:0} to{opacity:1} }
    @keyframes pulse-ring { 0%{transform:scale(1);opacity:.6} 100%{transform:scale(1.6);opacity:0} }
    @keyframes float      { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
    @keyframes shimmer    { 0%{background-position:-200% center} 100%{background-position:200% center} }
    @keyframes blink-dot  { 0%,100%{opacity:1} 50%{opacity:.2} }
    @keyframes gradShift  { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }

    /* ─── NAVBAR ─────────────────────────────────────────────── */
    .navbar-brand span.school { font-family:var(--font-head); font-weight:800; font-size:.95rem; line-height:1.2; }
    .navbar-brand span.sub    { font-size:.7rem; font-weight:300; letter-spacing:.06em; opacity:.8; display:block; }
    .navbar-custom {
      background: rgba(11,30,61,.96);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(255,255,255,.08);
      padding: .7rem 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: box-shadow .3s;
    }
    .navbar-custom.scrolled { box-shadow: 0 4px 30px rgba(0,0,0,.3); }
    .navbar-custom .nav-link {
      color: rgba(255,255,255,.82) !important;
      font-size: .85rem;
      font-weight: 500;
      padding: .45rem .9rem !important;
      border-radius: 8px;
      transition: background .2s, color .2s;
    }
    .navbar-custom .nav-link:hover { background: rgba(0,168,150,.18); color:#fff !important; }
    .navbar-custom .nav-link.active { background: var(--teal); color:#fff !important; }
    .btn-nav-cta {
      background: var(--grad-teal);
      color: #fff !important;
      font-weight: 600 !important;
      padding: .45rem 1.2rem !important;
      border-radius: 50px !important;
      transition: box-shadow .2s, transform .2s !important;
    }
    .btn-nav-cta:hover { box-shadow: 0 4px 18px rgba(0,168,150,.45) !important; transform: translateY(-1px) !important; }

    /* ─── HERO ───────────────────────────────────────────────── */
    .hero {
      min-height: 100vh;
      background: var(--grad-main);
      background-size: 300% 300%;
      animation: gradShift 12s ease infinite;
      position: relative;
      display: flex;
      align-items: center;
      overflow: hidden;
    }
    .hero::before {
      content:'';
      position:absolute; inset:0;
      background:
        radial-gradient(ellipse 60% 55% at 80% 50%, rgba(0,168,150,.22) 0%, transparent 70%),
        radial-gradient(ellipse 40% 40% at 20% 80%, rgba(244,162,97,.12) 0%, transparent 60%);
      pointer-events:none;
    }
    /* Decorative circles */
    .hero-circle {
      position:absolute; border-radius:50%; border:1px solid rgba(255,255,255,.08);
      pointer-events:none;
    }
    .hero-circle-1 { width:460px;height:460px; top:-80px; right:-120px; }
    .hero-circle-2 { width:300px;height:300px; bottom:-60px; left:-80px; border-color:rgba(0,168,150,.15); }
    .hero-circle-3 { width:180px;height:180px; top:50%; right:12%; transform:translateY(-50%); border-color:rgba(244,162,97,.2); animation:float 6s ease-in-out infinite; }

    .hero-badge {
      display:inline-flex; align-items:center; gap:.5rem;
      background: rgba(0,168,150,.18); border:1px solid rgba(0,168,150,.4);
      color: #5ef0e0; border-radius: 50px; padding: .35rem 1rem;
      font-size: .78rem; font-weight: 600; letter-spacing: .08em; text-transform:uppercase;
      animation: fadeDown .8s ease both;
      backdrop-filter:blur(8px);
    }
    .live-dot {
      width:8px;height:8px; background:#5ef0e0; border-radius:50%;
      animation: blink-dot 1.2s ease-in-out infinite;
    }
    .hero h1 {
      font-size: clamp(2.2rem, 5vw, 3.8rem);
      font-weight: 800;
      color: #fff;
      line-height: 1.12;
      animation: fadeDown 1s ease .2s both;
    }
    .hero h1 em { color: var(--amber); font-style: normal; }
    .hero-sub {
      font-size: 1.05rem; color: rgba(255,255,255,.75);
      animation: fadeDown 1s ease .4s both;
      font-weight: 300;
    }
    .hero-actions { animation: fadeUp 1s ease .6s both; }

    .btn-hero-primary {
      background: var(--grad-amber);
      color: var(--navy);
      font-family: var(--font-head);
      font-weight: 700;
      padding: .85rem 2rem;
      border-radius: 50px;
      font-size: .95rem;
      border: none;
      box-shadow: 0 6px 24px rgba(244,162,97,.4);
      transition: transform .25s, box-shadow .25s;
      display:inline-flex; align-items:center; gap:.5rem;
    }
    .btn-hero-primary:hover { transform:translateY(-3px); box-shadow: 0 12px 36px rgba(244,162,97,.5); color:var(--navy); }

    .btn-hero-outline {
      background: transparent;
      color: #fff;
      font-family: var(--font-head);
      font-weight: 600;
      padding: .85rem 2rem;
      border-radius: 50px;
      font-size: .95rem;
      border: 2px solid rgba(255,255,255,.35);
      transition: border-color .25s, background .25s, transform .25s;
      display:inline-flex; align-items:center; gap:.5rem;
    }
    .btn-hero-outline:hover { border-color:rgba(255,255,255,.8); background:rgba(255,255,255,.08); transform:translateY(-3px); color:#fff; }

    /* Stats chips */
    .stat-chip {
      background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
      backdrop-filter:blur(8px); border-radius:var(--radius);
      padding:.9rem 1.3rem; text-align:center;
      animation: fadeUp 1s ease both;
    }
    .stat-chip .num { font-family:var(--font-head); font-size:1.9rem; font-weight:800; color:#fff; line-height:1; }
    .stat-chip .lbl { font-size:.72rem; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.06em; margin-top:.2rem; }

    /* Scroll hint */
    .scroll-hint {
      position:absolute; bottom:2rem; left:50%; transform:translateX(-50%);
      display:flex; flex-direction:column; align-items:center; gap:.4rem;
      animation: fadeIn 1s ease 1.4s both;
    }
    .scroll-hint span { font-size:.7rem; color:rgba(255,255,255,.4); letter-spacing:.1em; text-transform:uppercase; }
    .scroll-hint .mouse {
      width:22px; height:36px; border:2px solid rgba(255,255,255,.3); border-radius:11px;
      display:flex; align-items:flex-start; justify-content:center; padding-top:5px;
    }
    .scroll-hint .wheel { width:3px; height:8px; background:rgba(255,255,255,.5); border-radius:2px; animation:float 1.6s ease-in-out infinite; }

    /* ─── SECTION COMMONS ────────────────────────────────────── */
    section { padding: 5rem 0; }
    .section-tag {
      display:inline-flex; align-items:center; gap:.5rem;
      font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
      color: var(--teal); margin-bottom:.75rem;
    }
    .section-tag::before { content:''; width:28px; height:2px; background:var(--teal); border-radius:1px; }
    .section-title {
      font-family: var(--font-head);
      font-size: clamp(1.6rem, 3.5vw, 2.4rem);
      font-weight: 800;
      line-height: 1.18;
      color: var(--navy);
    }
    .section-title span { color: var(--teal); }
    .section-lead { color: var(--muted); font-size:1rem; line-height:1.7; max-width:540px; }

    /* ─── COURSES ────────────────────────────────────────────── */
    #cursos { background: var(--light); }

    .course-card {
      background: var(--card-bg);
      border-radius: var(--radius-lg);
      padding: 2rem;
      box-shadow: var(--shadow);
      border: 1px solid rgba(11,30,61,.07);
      position: relative;
      overflow: hidden;
      transition: transform .35s cubic-bezier(.22,.68,0,1.2), box-shadow .35s ease;
      cursor: pointer;
      height: 100%;
    }
    .course-card::before {
      content:'';
      position:absolute; top:0; left:0; right:0; height:4px;
      background: var(--grad-teal);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .35s ease;
    }
    .course-card:hover { transform:translateY(-8px); box-shadow: var(--shadow-lg); }
    .course-card:hover::before { transform: scaleX(1); }
    .course-card .icon-wrap {
      width:58px; height:58px; border-radius:14px;
      display:flex; align-items:center; justify-content:center;
      font-size:1.5rem; margin-bottom:1.2rem;
      transition: transform .35s cubic-bezier(.22,.68,0,1.2);
    }
    .course-card:hover .icon-wrap { transform: scale(1.12) rotate(-4deg); }
    .course-card h3 { font-size:1.1rem; font-weight:700; margin-bottom:.5rem; }
    .course-card p { font-size:.87rem; color:var(--muted); line-height:1.65; }
    .course-card .tag-vagas {
      display:inline-block; margin-top:1rem;
      background:var(--light); color:var(--navy); border-radius:50px;
      padding:.2rem .8rem; font-size:.73rem; font-weight:600;
    }
    /* Card colors */
    .cc-admin .icon-wrap { background:rgba(0,168,150,.12); color:var(--teal); }
    .cc-cont  .icon-wrap { background:rgba(11,30,61,.08); color:var(--navy); }
    .cc-info  .icon-wrap { background:rgba(244,162,97,.15); color:var(--amber2); }
    .cc-seg   .icon-wrap { background:rgba(220,50,50,.08); color:#c0392b; }

    /* ─── COMO PARTICIPAR ── ZIGZAG TIMELINE ─────────────────── */
    #como-participar { background: #fff; }

    .timeline-wrap { position:relative; }
    .timeline-wrap::before {
      content:'';
      position:absolute; left:50%; top:0; bottom:0; width:2px;
      background: linear-gradient(to bottom, var(--teal), var(--amber));
      transform: translateX(-50%);
    }
    .tl-item {
      display:flex; align-items:center; gap:2rem; margin-bottom:3.5rem;
      position:relative;
    }
    .tl-item:nth-child(odd)  { flex-direction:row; }
    .tl-item:nth-child(even) { flex-direction:row-reverse; }

    .tl-content {
      width:45%; background:var(--card-bg); border-radius:var(--radius);
      padding:1.6rem; box-shadow:var(--shadow);
      border: 1px solid rgba(11,30,61,.06);
      transition: transform .3s, box-shadow .3s;
    }
    .tl-content:hover { transform:translateY(-4px); box-shadow:var(--shadow-lg); }
    .tl-item:nth-child(odd)  .tl-content { margin-right:calc(10% + 1rem); }
    .tl-item:nth-child(even) .tl-content { margin-left:calc(10% + 1rem); }

    .tl-node {
      position:absolute; left:50%; transform:translateX(-50%);
      width:52px; height:52px; border-radius:50%;
      background: var(--grad-teal);
      display:flex; align-items:center; justify-content:center;
      color:#fff; font-family:var(--font-head); font-weight:800; font-size:1.1rem;
      box-shadow: 0 0 0 6px rgba(0,168,150,.15), 0 4px 16px rgba(0,168,150,.35);
      z-index:2; flex-shrink:0;
    }
    .tl-node.amber-node { background:var(--grad-amber); box-shadow:0 0 0 6px rgba(244,162,97,.15), 0 4px 16px rgba(244,162,97,.35); }
    .tl-content h4 { font-size:.95rem; font-weight:700; margin-bottom:.4rem; }
    .tl-content p  { font-size:.85rem; color:var(--muted); line-height:1.6; margin:0; }

    /* Mobile timeline */
    @media(max-width:768px){
      .timeline-wrap::before { left:26px; }
      .tl-item { flex-direction:row !important; padding-left:60px; }
      .tl-item:nth-child(even) .tl-content { margin-left:0; }
      .tl-item:nth-child(odd)  .tl-content { margin-right:0; }
      .tl-content { width:100%; }
      .tl-node { left:26px; transform:none; position:absolute; }
    }

    /* ─── CALENDÁRIO ─────────────────────────────────────────── */
    #calendario { background:var(--light); }

    .cal-card {
      background:#fff; border-radius:var(--radius); padding:1.3rem 1.6rem;
      display:flex; align-items:center; gap:1.2rem;
      box-shadow:var(--shadow); border:1px solid rgba(11,30,61,.06);
      transition: transform .3s, box-shadow .3s;
    }
    .cal-card:hover { transform:translateX(6px); box-shadow:var(--shadow-lg); }
    .cal-date {
      min-width:58px; text-align:center;
      background:var(--navy); color:#fff; border-radius:12px;
      padding:.6rem .4rem;
    }
    .cal-date .day  { font-family:var(--font-head); font-size:1.6rem; font-weight:800; line-height:1; }
    .cal-date .mon  { font-size:.65rem; text-transform:uppercase; letter-spacing:.08em; opacity:.7; }
    .cal-info h5    { font-size:.92rem; font-weight:700; margin-bottom:.15rem; }
    .cal-info p     { font-size:.8rem; color:var(--muted); margin:0; }
    .cal-badge      { margin-left:auto; flex-shrink:0; font-size:.7rem; font-weight:700;
                      padding:.2rem .75rem; border-radius:50px; }
    .badge-open     { background:rgba(0,168,150,.12); color:var(--teal2); }
    .badge-close    { background:rgba(244,162,97,.15); color:var(--amber2); }
    .badge-event    { background:rgba(11,30,61,.08); color:var(--navy); }

    /* ─── FAQ ────────────────────────────────────────────────── */
    #faq { background:#fff; }

    .faq-item {
      background:var(--light); border-radius:var(--radius);
      margin-bottom:.85rem; overflow:hidden;
      border:1px solid rgba(11,30,61,.07);
      transition: box-shadow .25s;
    }
    .faq-item:hover { box-shadow:var(--shadow); }
    .faq-question {
      display:flex; align-items:center; justify-content:space-between;
      padding:1.1rem 1.4rem; cursor:pointer; gap:1rem;
      font-weight:600; font-size:.92rem;
      transition: color .2s;
      user-select:none;
    }
    .faq-question:hover { color:var(--teal); }
    .faq-question .faq-icon {
      width:28px; height:28px; flex-shrink:0;
      border-radius:50%; background:rgba(0,168,150,.12); color:var(--teal);
      display:flex; align-items:center; justify-content:center;
      font-size:.8rem;
      transition:transform .3s, background .25s;
    }
    .faq-item.open .faq-question { color:var(--teal); }
    .faq-item.open .faq-icon     { transform:rotate(45deg); background:var(--teal); color:#fff; }
    .faq-answer {
      max-height:0; overflow:hidden;
      transition:max-height .4s cubic-bezier(.4,0,.2,1), padding .3s;
      padding:0 1.4rem;
      font-size:.88rem; color:var(--muted); line-height:1.7;
    }
    .faq-item.open .faq-answer { max-height:300px; padding:.1rem 1.4rem 1.2rem; }

    .btn-faq-more {
      display:inline-flex; align-items:center; gap:.5rem;
      background:transparent; border:2px solid var(--teal); color:var(--teal);
      font-family:var(--font-head); font-weight:700; font-size:.88rem;
      padding:.65rem 1.5rem; border-radius:50px;
      transition:background .25s, color .25s, transform .25s;
    }
    .btn-faq-more:hover { background:var(--teal); color:#fff; transform:translateX(4px); }

    /* ─── LINKS RÁPIDOS ──────────────────────────────────────── */
    #links-rapidos { background:var(--navy); position:relative; overflow:hidden; }
    #links-rapidos::before {
      content:'';position:absolute;inset:0;
      background:radial-gradient(ellipse 70% 60% at 80% 50%, rgba(0,168,150,.15) 0%, transparent 60%);
      pointer-events:none;
    }
    .quick-card {
      background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1);
      border-radius:var(--radius); padding:1.8rem 1.4rem;
      text-align:center; color:#fff;
      transition:background .3s, transform .35s cubic-bezier(.22,.68,0,1.2), box-shadow .35s;
      cursor:pointer; height:100%;
      backdrop-filter:blur(6px);
    }
    .quick-card:hover {
      background:rgba(0,168,150,.2); border-color:rgba(0,168,150,.5);
      transform:translateY(-6px); box-shadow:0 12px 40px rgba(0,0,0,.3);
    }
    .quick-card .qc-icon {
      width:52px;height:52px; border-radius:14px; margin:0 auto 1rem;
      background:rgba(255,255,255,.08); display:flex; align-items:center; justify-content:center;
      font-size:1.3rem; color:var(--amber);
      transition:transform .35s cubic-bezier(.22,.68,0,1.2);
    }
    .quick-card:hover .qc-icon { transform:scale(1.15) rotate(-6deg); color:var(--teal); }
    .quick-card h5 { font-size:.9rem; font-weight:700; margin-bottom:.35rem; color:#fff; }
    .quick-card p  { font-size:.78rem; color:rgba(255,255,255,.55); margin:0; line-height:1.5; }

    /* ─── CANDIDATO CTA ──────────────────────────────────────── */
    #candidato-cta {
      background: linear-gradient(135deg, #0a2a1a 0%, #0B3D2E 50%, #0a2a1a 100%);
      position:relative; overflow:hidden;
    }
    #candidato-cta::before {
      content:''; position:absolute; inset:0;
      background: radial-gradient(ellipse 50% 80% at 50% 50%, rgba(0,168,150,.25) 0%, transparent 70%);
    }
    #candidato-cta .section-title { color:#fff; }
    #candidato-cta .section-lead   { color:rgba(255,255,255,.65); }

    .btn-cta-main {
      background: var(--grad-amber); color:var(--navy);
      font-family:var(--font-head); font-weight:700; font-size:1rem;
      padding:1rem 2.4rem; border-radius:50px; border:none;
      display:inline-flex; align-items:center; gap:.6rem;
      box-shadow:0 8px 32px rgba(244,162,97,.4);
      transition:transform .3s, box-shadow .3s;
    }
    .btn-cta-main:hover { transform:translateY(-4px); box-shadow:0 16px 48px rgba(244,162,97,.55); color:var(--navy); }

    .btn-cta-area {
      background:transparent; color:#fff;
      font-family:var(--font-head); font-weight:600; font-size:1rem;
      padding:1rem 2.4rem; border-radius:50px;
      border:2px solid rgba(255,255,255,.3);
      display:inline-flex; align-items:center; gap:.6rem;
      transition:border-color .3s, background .3s, transform .3s;
    }
    .btn-cta-area:hover { border-color:#fff; background:rgba(255,255,255,.1); transform:translateY(-4px); color:#fff; }

    /* Pulse ring on CTA */
    .pulse-wrap { position:relative; display:inline-block; }
    .pulse-wrap::before, .pulse-wrap::after {
      content:''; position:absolute; inset:-12px; border-radius:50px;
      border:2px solid rgba(244,162,97,.4);
      animation: pulse-ring 2s ease-out infinite;
    }
    .pulse-wrap::after { animation-delay:.8s; }

    /* ─── FOOTER ─────────────────────────────────────────────── */
    footer {
      background:#07152A; color:rgba(255,255,255,.6);
      padding:3rem 0 1.5rem;
    }
    footer .brand { font-family:var(--font-head); font-size:1.1rem; font-weight:800; color:#fff; }
    footer .brand small { display:block; font-size:.72rem; font-weight:300; opacity:.6; margin-top:.1rem; }
    footer a { color:rgba(255,255,255,.6); transition:color .2s; font-size:.85rem; }
    footer a:hover { color:var(--teal); }
    footer .foot-col h6 { color:#fff; font-family:var(--font-head); font-size:.8rem; font-weight:700;
                          text-transform:uppercase; letter-spacing:.1em; margin-bottom:1rem; }
    footer hr { border-color:rgba(255,255,255,.08); }
    footer .bottom p { font-size:.78rem; }

    /* ─── MODAL DE INSCRIÇÃO ─────────────────────────────────── */
    .modal-reg .modal-content {
      background:#fff; border-radius:var(--radius-lg); border:none;
      box-shadow:0 32px 80px rgba(0,0,0,.25); overflow:hidden;
    }
    .modal-reg .modal-header {
      background:var(--grad-main); border:none; padding:2rem 2rem 1.4rem;
    }
    .modal-reg .modal-title { color:#fff; font-family:var(--font-head); font-weight:800; }
    .modal-reg .btn-close { filter:invert(1) opacity(.7); }
    .modal-reg .modal-body  { padding:2rem; }
    .modal-reg label { font-size:.85rem; font-weight:600; margin-bottom:.3rem; color:var(--navy); }
    .modal-reg .form-control {
      border-radius:10px; border:1.5px solid rgba(11,30,61,.15);
      padding:.7rem .9rem; font-size:.9rem;
      transition:border-color .2s, box-shadow .2s;
    }
    .modal-reg .form-control:focus {
      border-color:var(--teal); box-shadow:0 0 0 3px rgba(0,168,150,.15);
    }
    .modal-reg .form-select {
      border-radius:10px; border:1.5px solid rgba(11,30,61,.15);
      padding:.7rem .9rem; font-size:.9rem;
    }
    .btn-submit {
      background:var(--grad-teal); color:#fff;
      font-family:var(--font-head); font-weight:700; font-size:.95rem;
      padding:.85rem; border-radius:50px; border:none; width:100%;
      transition:transform .25s, box-shadow .25s;
    }
    .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,168,150,.4); }
    .required::after { content: "*"; color: var(--amber2); margin-left: 4px; font-style: italic;
}

    /* ─── RESPONSIVE TWEAKS ──────────────────────────────────── */
    @media(max-width:991px) {
      .hero { min-height:auto; padding:6rem 0 4rem; }
      .stat-chip .num { font-size:1.4rem; }
    }
    @media(max-width:575px) {
      section { padding:3.5rem 0; }
      .hero-actions { display:flex; flex-direction:column; gap:1rem; }
      .btn-hero-primary, .btn-hero-outline { width:100%; justify-content:center; }
    }

    /* ─── UTILITIES ──────────────────────────────────────────── */
    .text-teal   { color:var(--teal) !important; }
    .text-amber  { color:var(--amber) !important; }
    .text-navy   { color:var(--navy) !important; }
    .bg-navy     { background:var(--navy) !important; }
    .fw-800      { font-weight:800; }
    .rounded-pill-sm { border-radius:50px; }
    .divider { width:48px; height:3px; background:var(--grad-teal); border-radius:2px; }
  </style>
</head>
<body>

<!-- ═══════════════════════ NAVBAR ══════════════════════════ -->
<nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
      <div style="width:38px;height:38px;border-radius:10px;background:var(--grad-teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="bi bi-mortarboard-fill text-white" style="font-size:1.1rem;"></i>
      </div>
      <div>
        <span class="school text-white">EM Dr. Leandro Franceschini</span>
        <span class="sub text-white">Vestibulinho {{ config('app.year') }}</span>
      </div>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item"><a class="nav-link" href="#cursos">Cursos</a></li>
        <li class="nav-item"><a class="nav-link" href="#como-participar">Como Participar</a></li>
        <li class="nav-item"><a class="nav-link" href="#calendario">Calendário</a></li>
        <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
        <li class="nav-item"><a class="nav-link" href="#links-rapidos">Documentos</a></li>
        <li class="nav-item ms-lg-2">
          <a class="nav-link btn-nav-cta" href="{{ route('login') }}">
            <i class="bi bi-person-circle me-1"></i> Área do Candidato
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ═══════════════════════ HERO ════════════════════════════ -->
<section class="hero" id="home">
  <div class="hero-circle hero-circle-1"></div>
  <div class="hero-circle hero-circle-2"></div>
  <div class="hero-circle hero-circle-3"></div>

  <div class="container position-relative" style="z-index:1;">
    <div class="row align-items-center g-5">
      <!-- Left text -->
      <div class="col-lg-7">
        <div class="hero-badge mb-3">
          <span class="live-dot"></span>
          Inscrições Abertas · 100% Online · Gratuito
        </div>
        <h1 class="mb-3">
          Sua carreira começa<br>aqui. No <em>Vestibulinho</em><br>{{ config('app.year') }}.
        </h1>
        <p class="hero-sub mb-4">
          4 cursos técnicos gratuitos. Uma oportunidade real de transformar<br class="d-none d-md-block">
          seu futuro. EM Dr. Leandro Franceschini — inscrição online e acessível.
        </p>
        <div class="hero-actions d-flex flex-wrap gap-3">
          <a href="{{ route('login') }}" class="btn-hero-primary">
            <i class="bi bi-pencil-square"></i> Inscrever-se Agora
          </a>
          <a href="#cursos" class="btn-hero-outline">
            <i class="bi bi-grid-3x3-gap"></i> Ver Cursos
          </a>
        </div>
      </div>
      <!-- Right stats -->
      <div class="col-lg-5">
        <div class="row g-3">
          <div class="col-6">
            <div class="stat-chip delay-1">
              <div class="num">4</div>
              <div class="lbl">Cursos Técnicos</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip delay-2">
              <div class="num">100%</div>
              <div class="lbl">Gratuito</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip delay-3">
              <div class="num" style="color:var(--amber);">Online</div>
              <div class="lbl">Inscrição Digital</div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-chip delay-4">
              <div class="num">{{ config('app.year') }}</div>
              <div class="lbl">Processo Seletivo</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scroll hint -->
  <div class="scroll-hint">
    <div class="mouse"><div class="wheel"></div></div>
    <span>rolar</span>
  </div>

</section>

<!-- ═══════════════════════ CURSOS ═══════════════════════════ -->
<section id="cursos">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-tag justify-content-center">Oferta Acadêmica</div>
      <h2 class="section-title mb-3">Escolha seu <span>Curso Técnico</span></h2>
      <p class="section-lead mx-auto text-center">
        Todos os cursos são gratuitos, presenciais e emitem certificado de técnico. Escolha sua área e construa sua carreira.
      </p>
    </div>

    <div class="row g-4">
      <!-- Cursos -->
      @foreach ($courses as $course)
      <div class="col-sm-6 col-lg-3 reveal delay-{{ $course->delay }}">
        <div class="course-card {{ $course->card }}">
          <div class="icon-wrap"><i class="bi bi-{{ $course->icone }}"></i></div>
          <h3>{{ $course->name }}</h3>
          <p>{{ $course->info }}</p>
          <span class="tag-vagas"><i class="bi bi-people-fill me-1"></i>{{ ($course->vacancies ? $course->vacancies : '') }} Vagas disponíveis</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ═══════════════════════ COMO PARTICIPAR ══════════════════ -->
<section id="como-participar">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col-lg-6 reveal">
        <div class="section-tag">Passo a Passo</div>
        <h2 class="section-title mb-3">Como <span>Participar</span><br>do Vestibulinho</h2>
        <p class="section-lead">
          O processo é simples, rápido e totalmente gratuito. Siga as etapas abaixo e garanta sua vaga.
        </p>
      </div>
      <div class="col-lg-6 reveal delay-2 text-lg-end">
        <a href="{{ route('login') }}" class="btn-faq-more">
          <i class="bi bi-pencil-fill"></i> Iniciar Inscrição
        </a>
      </div>
    </div>

    <div class="timeline-wrap">
      <!-- Step 1 -->
      <div class="tl-item reveal-left">
        <div class="tl-node">1</div>
        <div class="tl-content">
          <h4><i class="bi bi-search me-2 text-teal"></i>Leia o Edital</h4>
          <p>Acesse o edital completo na seção de documentos. Leia todas as regras, requisitos de inscrição, datas e critérios de avaliação.</p>
        </div>
      </div>
      <!-- Step 2 -->
      <div class="tl-item reveal-right">
        <div class="tl-node amber-node">2</div>
        <div class="tl-content">
          <h4><i class="bi bi-person-fill me-2 text-amber"></i>Registre-se</h4>
          Preencha o <a href="{{ route('register') }}" class="text-decoration-none text-amber">formulário de registro</a>, informe seu e-mail e crie uma senha de acesso. Você receberá um e-mail de confirmação. Clique no <i>link</i> recebido no e-mail para validar seu cadastro.  <strong class="text-danger">Sem essa confirmação não será possível realizar a inscrição.</strong>
        </div>
      </div>
      <!-- Step 3 -->
      <div class="tl-item reveal-left">
        <div class="tl-node">3</div>
        <div class="tl-content">
          <h4><i class="bi bi-clipboard-fill me-2 text-teal"></i>Faça sua Inscrição</h4>
          <p>Na <a href="{{ route('login') }}" class="text-decoration-none text-teal">Área do Candidato</a>, preencha o formulário de inscrição com suas informações pessoais, acadêmicas e demais dados solicitados. Confirme os dados e guarde o número de inscrição gerado.</p>
        </div>
      </div>
      <!-- Step 4 -->
      <div class="tl-item reveal-right">
        <div class="tl-node amber-node">4</div>
        <div class="tl-content">
          <h4><i class="bi bi-book-fill me-2 text-amber"></i>Estude e Prepare-se</h4>
          <p>Acesse as provas anteriores disponíveis aqui no site para se preparar. A prova aborda Português, Matemática e conhecimentos gerais.</p>
        </div>
      </div>
      <!-- Step 5 -->
      <div class="tl-item reveal-left">
        <div class="tl-node">5</div>
        <div class="tl-content">
          <h4><i class="bi bi-pen-fill me-2 text-teal"></i>Realize a Prova</h4>
          <p>Compareça no dia, horário e local indicados no cartão de confirmação. Leve documento com foto original.</p>
        </div>
      </div>
      <!-- Step 6 -->
      <div class="tl-item reveal-right">
        <div class="tl-node amber-node">6</div>
        <div class="tl-content">
          <h4><i class="bi bi-trophy-fill me-2 text-amber"></i>Acompanhe o Resultado</h4>
          <p>Acesse a classificação e a convocação para matrícula aqui no site. Se convocado, compareça no prazo indicado com os documentos exigidos.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ CALENDÁRIO ═══════════════════════ -->
<section id="calendario">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-tag justify-content-center">Datas Importantes</div>
      <h2 class="section-title mb-3">Calendário do <span>Processo Seletivo</span></h2>
      <p class="section-lead mx-auto text-center">Fique atento a todas as datas. Recomendamos salvar os prazos com antecedência.</p>
    </div>

    <div class="row g-3 justify-content-center">
      <div class="col-lg-8">
        <!-- Item 1 -->
        <div class="cal-card mb-3 reveal delay-1">
          <div class="cal-date">
            <div class="day">{{ $calendar->inscription_start?->format('d') }}</div>
            <div class="mon">{{ ucfirst($calendar->inscription_start?->translatedFormat('M')) }}</div>
          </div>
          <div class="cal-info flex-grow-1">
            <h5>Início das Inscrições</h5>
            <p>Abertura do portal de inscrições online — acesso pelo site oficial.</p>
          </div>
          <span class="cal-badge badge-open">Abertura</span>
        </div>
        <!-- Item 2 -->
        <div class="cal-card mb-3 reveal delay-2">
          <div class="cal-date" style="background:var(--teal2);">
            <div class="day">{{ $calendar->inscription_end?->format('d') }}</div>
            <div class="mon">{{ ucfirst($calendar->inscription_end?->translatedFormat('M')) }}</div>
          </div>
          <div class="cal-info flex-grow-1">
            <h5>Encerramento das Inscrições</h5>
            <p>Último dia para realizar a inscrição. Não haverá prorrogação.</p>
          </div>
          <span class="cal-badge badge-close">Prazo</span>
        </div>
        <!-- Item 3 -->
        <div class="cal-card mb-3 reveal delay-3">
          <div class="cal-date" style="background:#7B3FA0;">
            <div class="day">{{ $calendar->exam_location_publish?->format('d') }}</div>
            <div class="mon">{{ ucfirst($calendar->exam_location_publish?->translatedFormat('M')) }}</div>
          </div>
          <div class="cal-info flex-grow-1">
            <h5>Divulgação dos Locais de Prova</h5>
            <p>Local e horário de prova disponíveis na Área do Candidato.</p>
          </div>
          <span class="cal-badge badge-event">Evento</span>
        </div>
        <!-- Item 4 -->
        <div class="cal-card mb-3 reveal delay-2">
          <div class="cal-date" style="background:#C0392B;">
            <div class="day">{{ $calendar->exam_date?->format('d') }}</div>
            <div class="mon">{{ ucfirst($calendar->exam_date?->translatedFormat('M')) }}</div>
          </div>
          <div class="cal-info flex-grow-1">
            <h5>Dia da Prova</h5>
            <p>Realização da prova escrita. Levar RG original. Portões fecham às 8h.</p>
          </div>
          <span class="cal-badge badge-event">Prova</span>
        </div>
        <!-- Item 5 -->
        <div class="cal-card mb-3 reveal delay-3">
          <div class="cal-date" style="background:var(--amber2);">
            <div class="day">{{ $calendar->final_result_publish?->format('d') }}</div>
            <div class="mon">{{ ucfirst($calendar->final_result_publish?->translatedFormat('M')) }}</div>
          </div>
          <div class="cal-info flex-grow-1">
            <h5>Divulgação da Classificação</h5>
            <p>Lista de classificados publicada no site e na Área do Candidato.</p>
          </div>
          <span class="cal-badge" style="background:rgba(224,122,58,.15);color:var(--amber2);">Resultado</span>
        </div>
        <!-- Item 6 -->
        <div class="cal-card reveal delay-4">
          <div class="cal-date" style="background:var(--teal);">
            <div class="day">{{ $calendar->enrollment_start?->format('d') }}</div>
            <div class="mon">{{ ucfirst($calendar->enrollment_start?->translatedFormat('M')) }}</div>
          </div>
          <div class="cal-info flex-grow-1">
            <h5>Convocação e Matrícula</h5>
            <p>Candidatos convocados devem realizar a matrícula presencialmente.</p>
          </div>
          <span class="cal-badge badge-open">Matrícula</span>
        </div>
        <div class="text-center mt-4 reveal delay-4">
          <a href="#" class="btn-faq-more">
            Ver todas as datas do Vestibulinho <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ FAQ ══════════════════════════════ -->
<section id="faq">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col-lg-6 reveal">
        <div class="section-tag">Dúvidas Comuns</div>
        <h2 class="section-title mb-3">Perguntas <span>Frequentes</span></h2>
        <p class="section-lead">Selecionamos as dúvidas mais comuns dos candidatos. Não encontrou o que procurava? Acesse a página completa de FAQ.</p>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        @foreach ($faqs as $faq)
        
        <div class="faq-item reveal delay-1">
          <div class="faq-question" onclick="toggleFaq(this)">
            {{ $faq->question }}
            <div class="faq-icon"><i class="bi bi-plus-lg"></i></div>
          </div>
          <div class="faq-answer">
            {!! $faq->answer !!}
          </div>
        </div>

        @endforeach

        <div class="text-center mt-4 reveal">
          <a href="#" class="btn-faq-more">
            Ver todas as perguntas frequentes <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ LINKS RÁPIDOS ════════════════════ -->
<section id="links-rapidos">
  <div class="container position-relative" style="z-index:1;">
    <div class="text-center mb-5 reveal">
      <div class="section-tag justify-content-center" style="color:var(--amber);">
        <span style="background:var(--amber);"></span>Documentos e Acesso
      </div>
      <h2 class="section-title mb-3" style="color:#fff;">Tudo que você <span style="color:var(--teal);">precisa</span> em um lugar</h2>
      <p class="section-lead mx-auto text-center" style="color:rgba(255,255,255,.6);">Acesse documentos, resultados e sua área pessoal de candidato diretamente por aqui.</p>
    </div>

    <div class="row g-4">
      <div class="col-6 col-md-4 col-lg-2 reveal delay-1">
        <a href="{{ ($settings->isNoticeEnabled() && $notice->file) 
        ? asset('storage/' . $notice->file) 
        : '#' }}"
        class="quick-card d-block"
   @if($settings->isNoticeEnabled() && $notice->file)
       target="_blank"
   @endif>
          <div class="qc-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
          <h5>Edital</h5>
          <p>Regras e regulamento completo</p>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2 reveal delay-2">
        <a href="#" class="quick-card d-block">
          <div class="qc-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
          <h5>Provas Anteriores</h5>
          <p>Treine com edições passadas</p>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2 reveal delay-3">
        <a href="#" class="quick-card d-block">
          <div class="qc-icon"><i class="bi bi-bar-chart-fill"></i></div>
          <h5>Classificação</h5>
          <p>Resultado e lista de aprovados</p>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2 reveal delay-2">
        <a href="#" class="quick-card d-block">
          <div class="qc-icon"><i class="bi bi-bell-fill"></i></div>
          <h5>Convocação</h5>
          <p>Chamada para matrícula</p>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2 reveal delay-3">
        <a href="{{ route('login') }}" class="quick-card d-block">
          <div class="qc-icon"><i class="bi bi-person-badge-fill"></i></div>
          <h5>Área do Candidato</h5>
          <p>Acompanhe sua inscrição</p>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2 reveal delay-4">
        <a href="{{ route('register') }}" class="quick-card d-block">
          <div class="qc-icon"><i class="bi bi-person-plus-fill"></i></div>
          <h5>Registrar-se</h5>
          <p>Cadastre seus dados de acesso agora</p>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ CTA INSCRIÇÃO ════════════════════ -->
<section id="candidato-cta">
  <div class="container text-center position-relative" style="z-index:1;">
    <div class="reveal">
      <div class="section-tag justify-content-center" style="color:var(--teal);">
        <span style="background:var(--teal);"></span>Não Perca o Prazo
      </div>
      <h2 class="section-title mb-3">Garanta sua vaga no<br><span style="color:var(--amber);">curso técnico gratuito</span></h2>
      <p class="section-lead mx-auto text-center mb-5">Inscrições encerram em <strong style="color:var(--amber);">{{ $calendar?->inscription_end?->translatedFormat('d \d\e F Y') }}</strong>. Comece agora mesmo — leva menos de 5 minutos.</p>
      <div class="d-flex flex-wrap justify-content-center gap-3">
        <div class="pulse-wrap">
          <a href="{{ route('register') }}" class="btn-cta-main">
            <i class="bi bi-pencil-square"></i> Fazer Inscrição Agora
          </a>
        </div>
        {{-- <a href="#" class="btn-cta-area">
          <i class="bi bi-person-circle"></i> Acessar Área do Candidato
        </a> --}}
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════ FOOTER ═══════════════════════════ -->
<footer>
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-lg-4">
        <div class="brand mb-2">EM Dr. Leandro Franceschini<small>Escola Municipal · Vestibulinho {{ config('app.year') }}</small></div>
        <p style="font-size:.82rem;line-height:1.7;" class="mb-3">
          Oferecendo educação técnica de qualidade e oportunidades reais de crescimento profissional para toda a comunidade.
        </p>
        <div class="d-flex gap-2">
          <a href="#" class="d-flex align-items-center justify-content-center" style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;" onmouseover="this.style.background='rgba(0,168,150,.25)'" onmouseout="this.style.background='rgba(255,255,255,.07)'"><i class="bi bi-instagram"></i></a>
          <a href="#" class="d-flex align-items-center justify-content-center" style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;" onmouseover="this.style.background='rgba(0,168,150,.25)'" onmouseout="this.style.background='rgba(255,255,255,.07)'"><i class="bi bi-facebook"></i></a>
          <a href="#" class="d-flex align-items-center justify-content-center" style="width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,.07);transition:background .2s;" onmouseover="this.style.background='rgba(0,168,150,.25)'" onmouseout="this.style.background='rgba(255,255,255,.07)'"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2 foot-col">
        <h6>Processo Seletivo</h6>
        <ul class="list-unstyled d-flex flex-column gap-2">
          <li><a href="#">Edital</a></li>
          <li><a href="#">Calendário</a></li>
          <li><a href="#">Provas Anteriores</a></li>
          <li><a href="#">Classificação</a></li>
          <li><a href="#">Convocação</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2 foot-col">
        <h6>Cursos</h6>
        <ul class="list-unstyled d-flex flex-column gap-2">
          <li><a href="#">Administração</a></li>
          <li><a href="#">Contabilidade</a></li>
          <li><a href="#">Informática</a></li>
          <li><a href="#">Seg. do Trabalho</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2 foot-col">
        <h6>Candidato</h6>
        <ul class="list-unstyled d-flex flex-column gap-2">
          <li><a href="{{ route('register') }}">Inscrever-se</a></li>
          <li><a href="{{ route('login') }}">Área do Candidato</a></li>
          <li><a href="#">FAQ Completo</a></li>
          <li><a href="#como-participar">Como Participar</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2 foot-col">
        <h6>Contato</h6>
        <ul class="list-unstyled d-flex flex-column gap-2">
          <li><a href="#"><i class="bi bi-envelope me-1"></i>contato@escola.edu.br</a></li>
          <li><a href="#"><i class="bi bi-telephone me-1"></i>(11) 0000-0000</a></li>
          <li><a href="#"><i class="bi bi-geo-alt me-1"></i>Ver endereço</a></li>
        </ul>
      </div>
    </div>
    <hr>
    <div class="bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <p class="mb-0">© {{ $currentYear }} EM Dr. Leandro Franceschini · Todos os direitos reservados.</p>
      <p class="mb-0"><a href="#">Política de Privacidade</a> · <a href="#">Acessibilidade</a></p>
    </div>
  </div>
</footer>

<!-- ═══════════════════════ MODAL INSCRIÇÃO ══════════════════ -->

<!-- ═══════════════════════ SCRIPTS ══════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // ── Scroll reveal ──────────────────────────────────────────
  const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
  const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        revealObs.unobserve(e.target);
      }
    });
  }, { threshold: 0.15 });
  revealEls.forEach(el => revealObs.observe(el));

  // ── Sticky navbar shadow ────────────────────────────────────
  window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 40);
  });

  // ── Active nav link on scroll ───────────────────────────────
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.navbar-custom .nav-link[href^="#"]');
  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(sec => {
      if (window.scrollY >= sec.offsetTop - 120) current = sec.id;
    });
    navLinks.forEach(link => {
      link.classList.toggle('active', link.getAttribute('href') === '#' + current);
    });
  });

  // ── FAQ toggle ──────────────────────────────────────────────
  function toggleFaq(el) {
    const item = el.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  }
</script>
</body>
</html>