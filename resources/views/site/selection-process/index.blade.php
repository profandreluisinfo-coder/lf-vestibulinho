@extends('layouts.site')

@section('title', 'Vestibulinho LF · Calendário')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/selection-process/index.css') }}" />
@endpush

@section('content')

    @php
        $event = $selection_process->latestEvent;
    @endphp
    <!-- ═══════════════════════ PAGE HERO ════════════════════════ -->
    <section class="cal-hero">
        <div class="hero-circle hero-circle-1"></div>
        <div class="hero-circle hero-circle-2"></div>
        <div class="hero-circle hero-circle-3"></div>

        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-5">
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
                        <span class="year-chip">{{ $selection_process->year }}</span>
                    </h1>
                    <p class="hero-sub mb-0">
                        Todas as datas e prazos do Vestibulinho em um único lugar.<br class="d-none d-md-block">
                        Salve as datas e não perca nenhum prazo.
                    </p>
                </div>
                <div class="col-lg-5">
                    @if ($selection_process->isInscriptionOpen())
                        <div class="status-card status-open">
                            <div class="status-icon"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições Abertas</div>
                                <div class="status-detail">
                                    Encerram em
                                    <strong>{{ $event?->end?->format('d/m/Y') }}</strong>
                                </div>
                            </div>
                        </div>
                    @elseif($selection_process->isInscriptionEnded())
                        <div class="status-card status-closed">
                            <div class="status-icon"><i class="bi bi-x-circle-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições Encerradas</div>
                                <div class="status-detail">O período de inscrições foi concluído.</div>
                            </div>
                        </div>
                    @elseif($selection_process->isInscriptionStarted() === false && $event?->start)
                        <div class="status-card status-soon">
                            <div class="status-icon"><i class="bi bi-clock-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições em Breve</div>
                                <div class="status-detail">
                                    Abertura em <strong>{{ $event?->formatDate($event?->start) }}</strong>
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

            @if (!$selection_process)
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
                                    'label' => 'Inscrições',
                                    'icon' => 'bi-person-lines-fill',
                                    'color' => 'teal',
                                    'date' => $event?->formatPeriod($event?->start, $event?->end),
                                    'active' => $selection_process->isInscriptionOpen(),
                                    'done' => $selection_process->isInscriptionEnded(),
                                ],
                                [
                                    'label' => 'Local da Prova',
                                    'icon' => 'bi-geo-alt-fill',
                                    'color' => 'purple',
                                    'date' => $event?->location_publish?->format('d/m/Y'),
                                    'active' => $event?->location_publish && now()->isSameDay($event?->location_publish),
                                    'done' => $event?->location_publish && now()->gt($event?->location_publish),
                                ],
                                [
                                    'label' => 'Dia da Prova',
                                    'icon' => 'bi-calendar2-week-fill',
                                    'color' => 'red',
                                    'date' => $event?->exam_date?->format('d/m/Y'),
                                    'active' => $event?->exam_date && now()->isSameDay($event?->exam_date),
                                    'done' => $event?->exam_date && now()->gt($event?->exam_date),
                                ],
                                [
                                    'label' => 'Gabarito',
                                    'icon' => 'bi-list-check',
                                    'color' => 'amber',
                                    'date' => $event?->answer_publish?->format('d/m/Y'),
                                    'active' => $event?->answer_publish && now()->isSameDay($event?->answer_publish),
                                    'done' => $event?->answer_publish && now()->gt($event?->answer_publish),
                                ],
                                [
                                    'label' => 'Resultado',
                                    'icon' => 'bi-list-ol',
                                    'color' => 'orange',
                                    'date' => $event?->result_publish?->format('d/m/Y'),
                                    'active' => $event?->result_publish && now()->isSameDay($event?->result_publish),
                                    'done' => $event?->result_publish && now()->gt($event?->result_publish),
                                ],
                                [
                                    'label' => 'Matrícula',
                                    'icon' => 'bi-pin-angle-fill',
                                    'color' => 'teal',
                                    'date' => $event?->formatPeriod($event?->enrol_start, $event?->enrol_remaining),
                                    'active' =>
                                        $event?->enrol_start &&
                                        $event?->enrol_remaining &&
                                        now()->between($event?->enrol_start, $event?->enrol_remaining),
                                    'done' => $event?->enrol_remaining && now()->gt($event?->enrol_remaining),
                                ],
                            ];
                        @endphp

                        @foreach ($phases as $i => $phase)
                            <div
                                class="tf-step reveal delay-{{ ($i % 4) + 1 }}
                            {{ $phase['active'] ? 'tf-active' : '' }}
                            {{ $phase['done'] ? 'tf-done' : '' }}
                            tf-color-{{ $phase['color'] }}">
                                <div class="tf-node">
                                    <i class="bi {{ $phase['icon'] }}"></i>
                                </div>
                                @if (!$loop->last)
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
                    <p class="section-lead mx-auto text-center">Informações completas de cada etapa do processo seletivo.
                    </p>
                </div>

                <div class="row g-4 justify-content-center">

                    {{-- Inscrições --}}
                    <div class="col-lg-10 reveal delay-1">
                        <div
                            class="detail-card detail-teal {{ $selection_process->isInscriptionOpen() ? 'detail-active' : ($selection_process->isInscriptionEnded() ? 'detail-done' : '') }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon teal-bg"><i class="bi bi-person-lines-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 01</span>
                                    <h3>Inscrições</h3>
                                    @if ($selection_process->isInscriptionOpen())
                                        <span class="detail-badge badge-open"><span class="live-dot me-1"></span>Aberto
                                            agora</span>
                                    @elseif($selection_process->isInscriptionEnded())
                                        <span class="detail-badge badge-done"><i
                                                class="bi bi-check-lg me-1"></i>Encerrado</span>
                                    @else
                                        <span class="detail-badge badge-upcoming"><i class="bi bi-clock me-1"></i>Em
                                            breve</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="date-box">
                                            <div class="date-box-label">Início</div>
                                            <div class="date-box-day">
                                                {{ $event?->start?->format('d') ?? '—' }}</div>
                                            <div class="date-box-month">
                                                {{ $event?->start ? ucfirst($event?->start->translatedFormat('F Y')) : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="date-box date-box-end">
                                            <div class="date-box-label">Encerramento</div>
                                            <div class="date-box-day">{{ $event?->end?->format('d') ?? '—' }}
                                            </div>
                                            <div class="date-box-month">
                                                {{ $event?->end ? ucfirst($event?->end->translatedFormat('F Y')) : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="detail-note mt-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Inscrições realizadas exclusivamente pelo portal online. Acesse a <strong>Área do
                                        Candidato</strong> para se inscrever.
                                </p>
                                @if ($selection_process->isInscriptionOpen())
                                    <a href="{{ route('login') }}" class="btn-detail-cta mt-3">
                                        <i class="bi bi-pencil-square me-1"></i> Inscrever-se Agora
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Divulgação Local da Prova --}}
                    <div class="col-md-6 col-lg-5 reveal delay-2">
                        <div
                            class="detail-card detail-purple {{ $event?->location_publish && now()->gt($event?->location_publish) ? 'detail-done' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon purple-bg"><i class="bi bi-geo-alt-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 02</span>
                                    <h3>Local da Prova</h3>
                                    @if ($event?->location_publish && now()->gt($event?->location_publish))
                                        <span class="detail-badge badge-done"><i
                                                class="bi bi-check-lg me-1"></i>Divulgado</span>
                                    @else
                                        <span class="detail-badge badge-upcoming"><i
                                                class="bi bi-clock me-1"></i>Pendente</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-body">
                                <div class="date-box-single">
                                    <div class="date-box-single-day">
                                        {{ $event?->location_publish?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $event?->location_publish ? ucfirst($event?->location_publish->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $event?->location_publish?->format('Y') ?? '' }}</div>
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
                        <div
                            class="detail-card detail-red {{ $event?->exam_date && now()->gt($event?->exam_date) ? 'detail-done' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon red-bg"><i class="bi bi-calendar2-week-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 03</span>
                                    <h3>Dia da Prova</h3>
                                    @if ($event?->exam_date && now()->isSameDay($event?->exam_date))
                                        <span class="detail-badge badge-open"><span
                                                class="live-dot me-1"></span>Hoje!</span>
                                    @elseif($event?->exam_date && now()->gt($event?->exam_date))
                                        <span class="detail-badge badge-done"><i
                                                class="bi bi-check-lg me-1"></i>Realizada</span>
                                    @else
                                        <span class="detail-badge badge-upcoming"><i
                                                class="bi bi-clock me-1"></i>Pendente</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-body">
                                <div class="date-box-single">
                                    <div class="date-box-single-day">
                                        {{ $event?->exam_date?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $event?->exam_date ? ucfirst($event?->exam_date->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $event?->exam_date?->format('Y') ?? '' }}</div>
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
                        <div
                            class="detail-card detail-amber {{ $event?->answer_publish && now()->gt($event?->answer_publish) ? 'detail-done' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon amber-bg"><i class="bi bi-list-check"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 04</span>
                                    <h3>Gabarito</h3>
                                    @if ($event?->answer_publish && now()->gt($event?->answer_publish))
                                        <span class="detail-badge badge-done"><i
                                                class="bi bi-check-lg me-1"></i>Divulgado</span>
                                    @else
                                        <span class="detail-badge badge-upcoming"><i
                                                class="bi bi-clock me-1"></i>Pendente</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-body">
                                <div class="date-box-single">
                                    <div class="date-box-single-day">
                                        {{ $event?->answer_publish?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $event?->answer_publish ? ucfirst($event?->answer_publish->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $event?->answer_publish?->format('Y') ?? '' }}</div>
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
                                    <span class="detail-badge badge-upcoming"><i
                                            class="bi bi-clock me-1"></i>Pendente</span>
                                </div>
                            </div>
                            <div class="detail-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="date-box">
                                            <div class="date-box-label">Início</div>
                                            <div class="date-box-day">
                                                {{ $event?->revision_start?->format('d') ?? '—' }}</div>
                                            <div class="date-box-month">
                                                {{ $event?->revision_start ? ucfirst($event?->revision_start->translatedFormat('F Y')) : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="date-box date-box-end">
                                            <div class="date-box-label">Encerramento</div>
                                            <div class="date-box-day">
                                                {{ $event?->revision_end?->format('d') ?? '—' }}</div>
                                            <div class="date-box-month">
                                                {{ $event?->revision_end ? ucfirst($event?->revision_end->translatedFormat('F Y')) : '—' }}
                                            </div>
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
                        <div
                            class="detail-card detail-orange {{ $event?->result_publish && now()->gt($event?->result_publish) ? 'detail-done' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon orange-bg"><i class="bi bi-list-ol"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 06</span>
                                    <h3>Resultado Final</h3>
                                    @if ($event?->result_publish && now()->gt($event?->result_publish))
                                        <span class="detail-badge badge-done"><i
                                                class="bi bi-check-lg me-1"></i>Divulgado</span>
                                    @else
                                        <span class="detail-badge badge-upcoming"><i
                                                class="bi bi-clock me-1"></i>Pendente</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-body">
                                <div class="date-box-single">
                                    <div class="date-box-single-day">
                                        {{ $event?->result_publish?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $event?->result_publish ? ucfirst($event?->result_publish->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $event?->result_publish?->format('Y') ?? '' }}</div>
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
                        <div
                            class="detail-card detail-teal {{ $event?->enrol_start && now()->gte($event?->enrol_start) ? 'detail-active' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon teal-bg"><i class="bi bi-pin-angle-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 07</span>
                                    <h3>1ª Chamada</h3>
                                    @if ($event?->enrol_start && now()->gte($event?->enrol_start))
                                        <span class="detail-badge badge-open"><span
                                                class="live-dot me-1"></span>Publicado</span>
                                    @else
                                        <span class="detail-badge badge-upcoming"><i
                                                class="bi bi-clock me-1"></i>Pendente</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-body">
                                <div class="date-box-single">
                                    <div class="date-box-single-day">
                                        {{ $event?->enrol_start?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $event?->enrol_start ? ucfirst($event?->enrol_start->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $event?->enrol_start?->format('Y') ?? '' }}</div>
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
                        <div
                            class="detail-card detail-teal {{ $event?->enrol_remaining && now()->gte($event?->enrol_remaining) ? 'detail-active' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon teal2-bg"><i class="bi bi-pin-angle-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 08</span>
                                    <h3>Vagas Remanescentes</h3>
                                    @if ($event?->enrol_remaining && now()->gte($event?->enrol_remaining))
                                        <span class="detail-badge badge-open"><span
                                                class="live-dot me-1"></span>Publicado</span>
                                    @else
                                        <span class="detail-badge badge-upcoming"><i
                                                class="bi bi-clock me-1"></i>Pendente</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-body">
                                <div class="date-box-single">
                                    <div class="date-box-single-day">
                                        {{ $event?->enrol_remaining?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $event?->enrol_remaining ? ucfirst($event?->enrol_remaining->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $event?->enrol_remaining?->format('Y') ?? '' }}</div>
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
    @if ($selection_process->isInscriptionOpen())
        <section id="candidato-cta">
            <div class="container text-center position-relative" style="z-index:1;">
                <div class="reveal">
                    <div class="section-tag justify-content-center" style="color:var(--teal);">
                        <span style="background:var(--teal);"></span>Não Perca o Prazo
                    </div>
                    <h2 class="section-title mb-3">Garanta sua vaga no<br><span style="color:var(--amber);">curso técnico
                            gratuito</span></h2>
                    <p class="section-lead mx-auto text-center mb-5">Inscrições encerram em <strong
                            style="color:var(--amber);">{{ $event?->end?->translatedFormat('d \d\e F Y') }}</strong>.
                        Comece agora — leva menos de 5 minutos.</p>
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

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/site/selection-process/index.js') }}"></script>
@endpush
