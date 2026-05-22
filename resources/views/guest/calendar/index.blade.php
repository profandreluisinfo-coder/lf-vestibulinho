<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendário {{ $calendar?->year ?? config('app.year') }} — Vestibulinho LF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    {{-- Reutiliza os tokens globais do site --}}
    <link rel="stylesheet" href="{{ asset('assets/css/guest/home/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/guest/home/calendar.css') }}" />
</head>

<body>

    <!-- ═══════════════════════ NAVBAR ══════════════════════════ -->
    <nav class="navbar navbar-expand-lg navbar-custom" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div
                    style="width:38px;height:38px;border-radius:10px;background:var(--grad-teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-mortarboard-fill text-white" style="font-size:1.1rem;"></i>
                </div>
                <div>
                    <span class="school text-white">EM Dr. Leandro Franceschini</span>
                    <span class="sub text-white">Vestibulinho {{ $calendar?->year ?? config('app.year') }}</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#cursos">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#como-participar">Como Participar</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Calendário</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#links-rapidos">Documentos</a></li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn-nav-cta" href="{{ route('login') }}">
                            <i class="bi bi-person-circle me-1"></i> Área do Candidato
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ═══════════════════════ PAGE HERO ════════════════════════ -->
    <section class="cal-hero">
        <div class="cal-hero-bg"></div>
        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb cal-breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Calendário</li>
                        </ol>
                    </nav>
                    <div class="hero-badge mb-3" style="animation:fadeDown .8s ease both;">
                        <span class="live-dot"></span>
                        Datas Importantes · Processo Seletivo
                    </div>
                    <h1 class="cal-hero-title mb-3">
                        Calendário do<br><em>Processo Seletivo</em><br>
                        <span class="year-chip">{{ $calendar?->year ?? config('app.year') }}</span>
                    </h1>
                    <p class="hero-sub mb-0">
                        Todas as datas e prazos do Vestibulinho em um único lugar.<br class="d-none d-md-block">
                        Salve as datas e não perca nenhum prazo.
                    </p>
                </div>
                <div class="col-lg-5">
                    @if($calendar?->isInscriptionOpen())
                        <div class="status-card status-open">
                            <div class="status-icon"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições Abertas</div>
                                <div class="status-detail">
                                    Encerram em <strong>{{ $calendar->formatDate($calendar->inscription_end) }}</strong>
                                </div>
                            </div>
                        </div>
                    @elseif($calendar?->hasInscriptionEnded())
                        <div class="status-card status-closed">
                            <div class="status-icon"><i class="bi bi-x-circle-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições Encerradas</div>
                                <div class="status-detail">O período de inscrições foi concluído.</div>
                            </div>
                        </div>
                    @elseif($calendar?->hasInscriptionStarted() === false && $calendar?->inscription_start)
                        <div class="status-card status-soon">
                            <div class="status-icon"><i class="bi bi-clock-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições em Breve</div>
                                <div class="status-detail">
                                    Abertura em <strong>{{ $calendar->formatDate($calendar->inscription_start) }}</strong>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="status-card status-pending">
                            <div class="status-icon"><i class="bi bi-calendar3"></i></div>
                            <div>
                                <div class="status-label">Calendário</div>
                                <div class="status-detail">Confira todas as datas abaixo.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ CALENDÁRIO PRINCIPAL ══════════════ -->
    <section id="calendario-completo">
        <div class="container">

            @if(!$calendar)
                {{-- Estado vazio --}}
                <div class="empty-state text-center reveal">
                    <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                    <h3>Calendário não disponível</h3>
                    <p>O calendário do processo seletivo ainda não foi publicado. Volte em breve.</p>
                    <a href="{{ route('home') }}" class="btn-faq-more mt-3">
                        <i class="bi bi-arrow-left me-1"></i> Voltar ao início
                    </a>
                </div>
            @else

            {{-- ── LINHA DO TEMPO VISUAL ─────────────────────────── --}}
            <div class="timeline-section mb-5">
                <div class="text-center mb-5 reveal">
                    <div class="section-tag justify-content-center">Visão Geral</div>
                    <h2 class="section-title mb-2">Linha do <span>Tempo</span></h2>
                    <p class="section-lead mx-auto text-center">Acompanhe o fluxo completo do processo seletivo.</p>
                </div>

                <div class="timeline-flow">
                    @php
                        $phases = [
                            [
                                'label'   => 'Inscrições',
                                'icon'    => 'bi-person-lines-fill',
                                'color'   => 'teal',
                                'date'    => $calendar->formatPeriod($calendar->inscription_start, $calendar->inscription_end),
                                'active'  => $calendar->isInscriptionOpen(),
                                'done'    => $calendar->hasInscriptionEnded(),
                            ],
                            [
                                'label'   => 'Local da Prova',
                                'icon'    => 'bi-geo-alt-fill',
                                'color'   => 'purple',
                                'date'    => $calendar->formatDate($calendar->exam_location_publish),
                                'active'  => $calendar->exam_location_publish && now()->isSameDay($calendar->exam_location_publish),
                                'done'    => $calendar->exam_location_publish && now()->gt($calendar->exam_location_publish),
                            ],
                            [
                                'label'   => 'Dia da Prova',
                                'icon'    => 'bi-calendar2-week-fill',
                                'color'   => 'red',
                                'date'    => $calendar->formatDate($calendar->exam_date),
                                'active'  => $calendar->exam_date && now()->isSameDay($calendar->exam_date),
                                'done'    => $calendar->exam_date && now()->gt($calendar->exam_date),
                            ],
                            [
                                'label'   => 'Gabarito',
                                'icon'    => 'bi-list-check',
                                'color'   => 'amber',
                                'date'    => $calendar->formatDate($calendar->answer_key_publish),
                                'active'  => $calendar->answer_key_publish && now()->isSameDay($calendar->answer_key_publish),
                                'done'    => $calendar->answer_key_publish && now()->gt($calendar->answer_key_publish),
                            ],
                            [
                                'label'   => 'Resultado',
                                'icon'    => 'bi-list-ol',
                                'color'   => 'orange',
                                'date'    => $calendar->formatDate($calendar->final_result_publish),
                                'active'  => $calendar->final_result_publish && now()->isSameDay($calendar->final_result_publish),
                                'done'    => $calendar->final_result_publish && now()->gt($calendar->final_result_publish),
                            ],
                            [
                                'label'   => 'Matrícula',
                                'icon'    => 'bi-pin-angle-fill',
                                'color'   => 'teal',
                                'date'    => $calendar->formatPeriod($calendar->enrollment_start, $calendar->enrollment_end),
                                'active'  => $calendar->enrollment_start && $calendar->enrollment_end && now()->between($calendar->enrollment_start, $calendar->enrollment_end),
                                'done'    => $calendar->enrollment_end && now()->gt($calendar->enrollment_end),
                            ],
                        ];
                    @endphp

                    @foreach($phases as $i => $phase)
                        <div class="tf-step reveal delay-{{ ($i % 4) + 1 }}
                            {{ $phase['active'] ? 'tf-active' : '' }}
                            {{ $phase['done']   ? 'tf-done'   : '' }}
                            tf-color-{{ $phase['color'] }}">
                            <div class="tf-node">
                                <i class="bi {{ $phase['icon'] }}"></i>
                            </div>
                            @if(!$loop->last)
                                <div class="tf-connector {{ $phase['done'] ? 'tf-connector-done' : '' }}"></div>
                            @endif
                            <div class="tf-label">{{ $phase['label'] }}</div>
                            <div class="tf-date">{{ $phase['date'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── CARDS DETALHADOS ──────────────────────────────── --}}
            <div class="text-center mb-5 reveal">
                <div class="section-tag justify-content-center">Detalhamento</div>
                <h2 class="section-title mb-2">Todas as <span>Datas</span></h2>
                <p class="section-lead mx-auto text-center">Informações completas de cada etapa do processo seletivo.</p>
            </div>

            <div class="row g-4 justify-content-center">

                {{-- Inscrições --}}
                <div class="col-lg-10 reveal delay-1">
                    <div class="detail-card detail-teal {{ $calendar->isInscriptionOpen() ? 'detail-active' : ($calendar->hasInscriptionEnded() ? 'detail-done' : '') }}">
                        <div class="detail-phase">
                            <div class="detail-phase-icon teal-bg"><i class="bi bi-person-lines-fill"></i></div>
                            <div class="detail-phase-info">
                                <span class="detail-phase-num">Etapa 01</span>
                                <h3>Inscrições</h3>
                                @if($calendar->isInscriptionOpen())
                                    <span class="detail-badge badge-open"><span class="live-dot me-1"></span>Aberto agora</span>
                                @elseif($calendar->hasInscriptionEnded())
                                    <span class="detail-badge badge-done"><i class="bi bi-check-lg me-1"></i>Encerrado</span>
                                @else
                                    <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Em breve</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-body">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="date-box">
                                        <div class="date-box-label">Início</div>
                                        <div class="date-box-day">{{ $calendar->inscription_start?->format('d') ?? '—' }}</div>
                                        <div class="date-box-month">{{ $calendar->inscription_start ? ucfirst($calendar->inscription_start->translatedFormat('F Y')) : '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="date-box date-box-end">
                                        <div class="date-box-label">Encerramento</div>
                                        <div class="date-box-day">{{ $calendar->inscription_end?->format('d') ?? '—' }}</div>
                                        <div class="date-box-month">{{ $calendar->inscription_end ? ucfirst($calendar->inscription_end->translatedFormat('F Y')) : '—' }}</div>
                                    </div>
                                </div>
                            </div>
                            <p class="detail-note mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Inscrições realizadas exclusivamente pelo portal online. Acesse a <strong>Área do Candidato</strong> para se inscrever.
                            </p>
                            @if($calendar->isInscriptionOpen())
                                <a href="{{ route('login') }}" class="btn-detail-cta mt-3">
                                    <i class="bi bi-pencil-square me-1"></i> Inscrever-se Agora
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Divulgação Local da Prova --}}
                <div class="col-md-6 col-lg-5 reveal delay-2">
                    <div class="detail-card detail-purple {{ $calendar->exam_location_publish && now()->gt($calendar->exam_location_publish) ? 'detail-done' : '' }}">
                        <div class="detail-phase">
                            <div class="detail-phase-icon purple-bg"><i class="bi bi-geo-alt-fill"></i></div>
                            <div class="detail-phase-info">
                                <span class="detail-phase-num">Etapa 02</span>
                                <h3>Local da Prova</h3>
                                @if($calendar->exam_location_publish && now()->gt($calendar->exam_location_publish))
                                    <span class="detail-badge badge-done"><i class="bi bi-check-lg me-1"></i>Divulgado</span>
                                @else
                                    <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Pendente</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-body">
                            <div class="date-box-single">
                                <div class="date-box-single-day">{{ $calendar->exam_location_publish?->format('d') ?? '—' }}</div>
                                <div class="date-box-single-rest">
                                    <div class="date-box-single-month">{{ $calendar->exam_location_publish ? ucfirst($calendar->exam_location_publish->translatedFormat('F')) : '—' }}</div>
                                    <div class="date-box-single-year">{{ $calendar->exam_location_publish?->format('Y') ?? '' }}</div>
                                </div>
                            </div>
                            <p class="detail-note mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Local e horário disponíveis na Área do Candidato.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Dia da Prova --}}
                <div class="col-md-6 col-lg-5 reveal delay-3">
                    <div class="detail-card detail-red {{ $calendar->exam_date && now()->gt($calendar->exam_date) ? 'detail-done' : '' }}">
                        <div class="detail-phase">
                            <div class="detail-phase-icon red-bg"><i class="bi bi-calendar2-week-fill"></i></div>
                            <div class="detail-phase-info">
                                <span class="detail-phase-num">Etapa 03</span>
                                <h3>Dia da Prova</h3>
                                @if($calendar->exam_date && now()->isSameDay($calendar->exam_date))
                                    <span class="detail-badge badge-open"><span class="live-dot me-1"></span>Hoje!</span>
                                @elseif($calendar->exam_date && now()->gt($calendar->exam_date))
                                    <span class="detail-badge badge-done"><i class="bi bi-check-lg me-1"></i>Realizada</span>
                                @else
                                    <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Pendente</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-body">
                            <div class="date-box-single">
                                <div class="date-box-single-day">{{ $calendar->exam_date?->format('d') ?? '—' }}</div>
                                <div class="date-box-single-rest">
                                    <div class="date-box-single-month">{{ $calendar->exam_date ? ucfirst($calendar->exam_date->translatedFormat('F')) : '—' }}</div>
                                    <div class="date-box-single-year">{{ $calendar->exam_date?->format('Y') ?? '' }}</div>
                                </div>
                            </div>
                            <p class="detail-note mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Levar RG original. Portões fecham às 8h. Chegue com antecedência.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Gabarito --}}
                <div class="col-md-6 col-lg-5 reveal delay-2">
                    <div class="detail-card detail-amber {{ $calendar->answer_key_publish && now()->gt($calendar->answer_key_publish) ? 'detail-done' : '' }}">
                        <div class="detail-phase">
                            <div class="detail-phase-icon amber-bg"><i class="bi bi-list-check"></i></div>
                            <div class="detail-phase-info">
                                <span class="detail-phase-num">Etapa 04</span>
                                <h3>Gabarito</h3>
                                @if($calendar->answer_key_publish && now()->gt($calendar->answer_key_publish))
                                    <span class="detail-badge badge-done"><i class="bi bi-check-lg me-1"></i>Divulgado</span>
                                @else
                                    <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Pendente</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-body">
                            <div class="date-box-single">
                                <div class="date-box-single-day">{{ $calendar->answer_key_publish?->format('d') ?? '—' }}</div>
                                <div class="date-box-single-rest">
                                    <div class="date-box-single-month">{{ $calendar->answer_key_publish ? ucfirst($calendar->answer_key_publish->translatedFormat('F')) : '—' }}</div>
                                    <div class="date-box-single-year">{{ $calendar->answer_key_publish?->format('Y') ?? '' }}</div>
                                </div>
                            </div>
                            <p class="detail-note mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Gabarito preliminar publicado no site oficial.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Revisão de Prova --}}
                <div class="col-md-6 col-lg-5 reveal delay-3">
                    <div class="detail-card detail-teal2">
                        <div class="detail-phase">
                            <div class="detail-phase-icon teal2-bg"><i class="bi bi-search"></i></div>
                            <div class="detail-phase-info">
                                <span class="detail-phase-num">Etapa 05</span>
                                <h3>Revisão de Prova</h3>
                                <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Pendente</span>
                            </div>
                        </div>
                        <div class="detail-body">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="date-box">
                                        <div class="date-box-label">Início</div>
                                        <div class="date-box-day">{{ $calendar->exam_revision_start?->format('d') ?? '—' }}</div>
                                        <div class="date-box-month">{{ $calendar->exam_revision_start ? ucfirst($calendar->exam_revision_start->translatedFormat('F Y')) : '—' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="date-box date-box-end">
                                        <div class="date-box-label">Encerramento</div>
                                        <div class="date-box-day">{{ $calendar->exam_revision_end?->format('d') ?? '—' }}</div>
                                        <div class="date-box-month">{{ $calendar->exam_revision_end ? ucfirst($calendar->exam_revision_end->translatedFormat('F Y')) : '—' }}</div>
                                    </div>
                                </div>
                            </div>
                            <p class="detail-note mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Solicitações de revisão devem ser feitas dentro do prazo estabelecido.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Resultado Final --}}
                <div class="col-lg-10 reveal delay-1">
                    <div class="detail-card detail-orange {{ $calendar->final_result_publish && now()->gt($calendar->final_result_publish) ? 'detail-done' : '' }}">
                        <div class="detail-phase">
                            <div class="detail-phase-icon orange-bg"><i class="bi bi-list-ol"></i></div>
                            <div class="detail-phase-info">
                                <span class="detail-phase-num">Etapa 06</span>
                                <h3>Resultado Final</h3>
                                @if($calendar->final_result_publish && now()->gt($calendar->final_result_publish))
                                    <span class="detail-badge badge-done"><i class="bi bi-check-lg me-1"></i>Divulgado</span>
                                @else
                                    <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Pendente</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-body">
                            <div class="date-box-single">
                                <div class="date-box-single-day">{{ $calendar->final_result_publish?->format('d') ?? '—' }}</div>
                                <div class="date-box-single-rest">
                                    <div class="date-box-single-month">{{ $calendar->final_result_publish ? ucfirst($calendar->final_result_publish->translatedFormat('F')) : '—' }}</div>
                                    <div class="date-box-single-year">{{ $calendar->final_result_publish?->format('Y') ?? '' }}</div>
                                </div>
                            </div>
                            <p class="detail-note mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Lista de classificados publicada no site e na Área do Candidato.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Convocações --}}
                <div class="col-md-6 col-lg-5 reveal delay-2">
                    <div class="detail-card detail-teal {{ $calendar->enrollment_start && now()->gte($calendar->enrollment_start) ? 'detail-active' : '' }}">
                        <div class="detail-phase">
                            <div class="detail-phase-icon teal-bg"><i class="bi bi-pin-angle-fill"></i></div>
                            <div class="detail-phase-info">
                                <span class="detail-phase-num">Etapa 07</span>
                                <h3>1ª Chamada</h3>
                                @if($calendar->enrollment_start && now()->gte($calendar->enrollment_start))
                                    <span class="detail-badge badge-open"><span class="live-dot me-1"></span>Publicado</span>
                                @else
                                    <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Pendente</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-body">
                            <div class="date-box-single">
                                <div class="date-box-single-day">{{ $calendar->enrollment_start?->format('d') ?? '—' }}</div>
                                <div class="date-box-single-rest">
                                    <div class="date-box-single-month">{{ $calendar->enrollment_start ? ucfirst($calendar->enrollment_start->translatedFormat('F')) : '—' }}</div>
                                    <div class="date-box-single-year">{{ $calendar->enrollment_start?->format('Y') ?? '' }}</div>
                                </div>
                            </div>
                            <p class="detail-note mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Candidatos convocados devem realizar matrícula presencialmente.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Vagas Remanescentes --}}
                <div class="col-md-6 col-lg-5 reveal delay-3">
                    <div class="detail-card detail-teal {{ $calendar->enrollment_end && now()->gte($calendar->enrollment_end) ? 'detail-active' : '' }}">
                        <div class="detail-phase">
                            <div class="detail-phase-icon teal2-bg"><i class="bi bi-pin-angle-fill"></i></div>
                            <div class="detail-phase-info">
                                <span class="detail-phase-num">Etapa 08</span>
                                <h3>Vagas Remanescentes</h3>
                                @if($calendar->enrollment_end && now()->gte($calendar->enrollment_end))
                                    <span class="detail-badge badge-open"><span class="live-dot me-1"></span>Publicado</span>
                                @else
                                    <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Pendente</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-body">
                            <div class="date-box-single">
                                <div class="date-box-single-day">{{ $calendar->enrollment_end?->format('d') ?? '—' }}</div>
                                <div class="date-box-single-rest">
                                    <div class="date-box-single-month">{{ $calendar->enrollment_end ? ucfirst($calendar->enrollment_end->translatedFormat('F')) : '—' }}</div>
                                    <div class="date-box-single-year">{{ $calendar->enrollment_end?->format('Y') ?? '' }}</div>
                                </div>
                            </div>
                            <p class="detail-note mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Chamamento para preenchimento de vagas remanescentes.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            @endif

        </div>
    </section>

    <!-- ═══════════════════════ CTA ═══════════════════════════════ -->
    @if($calendar?->isInscriptionOpen())
    <section id="candidato-cta">
        <div class="container text-center position-relative" style="z-index:1;">
            <div class="reveal">
                <div class="section-tag justify-content-center" style="color:var(--teal);">
                    <span style="background:var(--teal);"></span>Não Perca o Prazo
                </div>
                <h2 class="section-title mb-3">Garanta sua vaga no<br><span style="color:var(--amber);">curso técnico gratuito</span></h2>
                <p class="section-lead mx-auto text-center mb-5">Inscrições encerram em <strong style="color:var(--amber);">{{ $calendar->inscription_end?->translatedFormat('d \d\e F Y') }}</strong>. Comece agora — leva menos de 5 minutos.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <div class="pulse-wrap">
                        <a href="{{ route('login') }}" class="btn-cta-main">
                            <i class="bi bi-pencil-square"></i> Fazer Inscrição Agora
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ═══════════════════════ FOOTER ═══════════════════════════ -->
    <footer>
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="brand mb-2">EM Dr. Leandro Franceschini<small>Escola Municipal · Vestibulinho {{ config('app.year') }}</small></div>
                    <p style="font-size:.82rem;line-height:1.7;" class="mb-3">
                        Oferecendo educação técnica de qualidade e oportunidades reais de crescimento profissional para toda a comunidade.
                    </p>
                </div>
                <div class="col-6 col-lg-2 foot-col">
                    <h6>Processo Seletivo</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#">Edital</a></li>
                        <li><a href="#" class="text-teal">Calendário</a></li>
                        <li><a href="#">Provas Anteriores</a></li>
                        <li><a href="#">Classificação</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2 foot-col">
                    <h6>Candidato</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('register') }}">Inscrever-se</a></li>
                        <li><a href="{{ route('login') }}">Área do Candidato</a></li>
                        <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2 foot-col">
                    <h6>Contato</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="mailto:emdrleandrofranceschini@educacaosumare.com.br" title="emdrleandrofranceschini@educacaosumare.com.br"><i class="bi bi-envelope me-1"></i>emdrleandrofranceschini@...</a></li>
                        <li><a href="#"><i class="bi bi-telephone me-1"></i>(19) 3873-2605</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="mb-0">© {{ date('Y') }} EM Dr. Leandro Franceschini · Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/guest/home/index.js') }}"></script>
</body>

</html>
