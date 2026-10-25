@extends('layouts.site')

@section('title', 'Calendário')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/process/index.css') }}" />
@endpush

@section('content')

    @php
        $isInscriptionEnded   = $process?->isInscriptionEnded() ?? false;
        $isInscriptionStarted = $process?->isInscriptionStarted() ?? false;
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
                        Datas Importantes · Vestibulinho {{ $process?->year ?? '' }}
                    </div>
                    <h1 class="cal-hero-title mb-3">
                        Calendário do<br><em>Vestibulinho {{ $process?->year ?? '' }}</em><br>
                    </h1>
                    <p class="hero-sub mb-0">
                        Todas as datas e prazos do Vestibulinho em um único lugar.<br class="d-none d-md-block">
                        Salve as datas e não perca nenhum prazo.
                    </p>
                </div>
                <div class="col-lg-5">
                    @if ($isInscriptionOpen)
                        <div class="status-card status-open">
                            <div class="status-icon"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições Abertas</div>
                                <div class="status-detail">
                                    Encerram em
                                    <strong>{{ $process?->end?->format('d/m/Y') }}</strong>
                                </div>
                            </div>
                        </div>
                    @elseif($isInscriptionEnded)
                        <div class="status-card status-closed">
                            <div class="status-icon"><i class="bi bi-x-circle-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições Encerradas</div>
                                <div class="status-detail">O período de inscrições foi concluído.</div>
                            </div>
                        </div>
                    @elseif($isInscriptionStarted === false && $process?->start)
                        <div class="status-card status-soon">
                            <div class="status-icon"><i class="bi bi-clock-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições em Breve</div>
                                <div class="status-detail">
                                    Abertura em <strong>{{ $process?->formatDate($process?->start) }}</strong>
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

            @if (!$process)
                {{-- Estado vazio --}}
                <div class="empty-state text-center reveal">
                    <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                    <h3>Calendário não disponível</h3>
                    <p>O calendário do Vestibulinho {{ $process?->year ?? '' }} ainda não foi publicado. Volte em breve.</p>
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
                        <p class="section-lead mx-auto text-center">Acompanhe o fluxo completo do Vestibulinho {{ $process?->year ?? '' }}.</p>
                    </div>

                    <div class="timeline-flow">
                        @php
                            $phases = [
                                [
                                    'label' => 'Inscrições',
                                    'icon' => 'bi-person-lines-fill',
                                    'color' => 'teal',
                                    'date' => $process?->formatPeriod($process?->start, $process?->end),
                                    'active' => $isInscriptionOpen,
                                    'done' => $isInscriptionEnded,
                                ],
                                [
                                    'label' => 'Local da Prova',
                                    'icon' => 'bi-geo-alt-fill',
                                    'color' => 'purple',
                                    'date' => $process?->location_publish?->format('d/m/Y'),
                                    'active' => $process?->location_publish && now()->isSameDay($process?->location_publish),
                                    'done' => $process?->location_publish && now()->gt($process?->location_publish),
                                ],
                                [
                                    'label' => 'Dia da Prova',
                                    'icon' => 'bi-calendar2-week-fill',
                                    'color' => 'red',
                                    'date' => $process?->exam_date?->format('d/m/Y'),
                                    'active' => $process?->exam_date && now()->isSameDay($process?->exam_date),
                                    'done' => $process?->exam_date && now()->gt($process?->exam_date),
                                ],
                                [
                                    'label' => 'Gabarito',
                                    'icon' => 'bi-list-check',
                                    'color' => 'amber',
                                    'date' => $process?->answer_publish?->format('d/m/Y'),
                                    'active' => $process?->answer_publish && now()->isSameDay($process?->answer_publish),
                                    'done' => $process?->answer_publish && now()->gt($process?->answer_publish),
                                ],
                                [
                                    'label' => 'Resultado',
                                    'icon' => 'bi-list-ol',
                                    'color' => 'orange',
                                    'date' => $process?->result_publish?->format('d/m/Y'),
                                    'active' => $process?->result_publish && now()->isSameDay($process?->result_publish),
                                    'done' => $process?->result_publish && now()->gt($process?->result_publish),
                                ],
                                [
                                    'label' => 'Matrícula',
                                    'icon' => 'bi-pin-angle-fill',
                                    'color' => 'teal',
                                    'date' => $process?->formatPeriod($process?->enrol_start, $process?->enrol_remaining),
                                    'active' =>
                                        $process?->enrol_start &&
                                        $process?->enrol_remaining &&
                                        now()->between($process?->enrol_start, $process?->enrol_remaining),
                                    'done' => $process?->enrol_remaining && now()->gt($process?->enrol_remaining),
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
                    <p class="section-lead mx-auto text-center">Informações completas de cada etapa do Vestibulinho {{ $process?->year ?? '' }}.
                    </p>
                </div>

                <div class="row g-4 justify-content-center">

                    {{-- Inscrições --}}
                    <div class="col-lg-10 reveal delay-1">
                        <div
                            class="detail-card detail-teal {{ $isInscriptionOpen ? 'detail-active' : ($isInscriptionEnded ? 'detail-done' : '') }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon teal-bg"><i class="bi bi-person-lines-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 01</span>
                                    <h3>Inscrições</h3>
                                    @if ($isInscriptionOpen)
                                        <span class="detail-badge badge-open"><span class="live-dot me-1"></span>Aberto
                                            agora</span>
                                    @elseif($isInscriptionEnded)
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
                                                {{ $process?->start?->format('d') ?? '—' }}</div>
                                            <div class="date-box-month">
                                                {{ $process?->start ? ucfirst($process?->start->translatedFormat('F Y')) : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="date-box date-box-end">
                                            <div class="date-box-label">Encerramento</div>
                                            <div class="date-box-day">{{ $process?->end?->format('d') ?? '—' }}
                                            </div>
                                            <div class="date-box-month">
                                                {{ $process?->end ? ucfirst($process?->end->translatedFormat('F Y')) : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="detail-note mt-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Inscrições realizadas exclusivamente pelo portal online. Acesse a <strong>Área do
                                        Candidato</strong> para se inscrever.
                                </p>
                                @if ($isInscriptionOpen)
                                    <a href="{{ route('login') }}" class="btn-detail-cta js-inscription-link mt-3">
                                        <i class="bi bi-pencil-square me-1"></i> Inscrever-se Agora
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Divulgação Local da Prova --}}
                    <div class="col-md-6 col-lg-5 reveal delay-2">
                        <div
                            class="detail-card detail-purple {{ $process?->location_publish && now()->gt($process?->location_publish) ? 'detail-done' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon purple-bg"><i class="bi bi-geo-alt-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 02</span>
                                    <h3>Local da Prova</h3>
                                    @if ($process?->location_publish && now()->gt($process?->location_publish))
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
                                        {{ $process?->location_publish?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $process?->location_publish ? ucfirst($process?->location_publish->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $process?->location_publish?->format('Y') ?? '' }}</div>
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
                            class="detail-card detail-red {{ $process?->exam_date && now()->gt($process?->exam_date) ? 'detail-done' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon red-bg"><i class="bi bi-calendar2-week-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 03</span>
                                    <h3>Dia da Prova</h3>
                                    @if ($process?->exam_date && now()->isSameDay($process?->exam_date))
                                        <span class="detail-badge badge-open"><span
                                                class="live-dot me-1"></span>Hoje!</span>
                                    @elseif($process?->exam_date && now()->gt($process?->exam_date))
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
                                        {{ $process?->exam_date?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $process?->exam_date ? ucfirst($process?->exam_date->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $process?->exam_date?->format('Y') ?? '' }}</div>
                                    </div>
                                </div>
                                <p class="detail-note mt-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Levar documento original com foto atual. Portões fecham às 8h. Chegue com antecedência.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Gabarito --}}
                    <div class="col-md-6 col-lg-5 reveal delay-2">
                        <div
                            class="detail-card detail-amber {{ $process?->answer_publish && now()->gt($process?->answer_publish) ? 'detail-done' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon amber-bg"><i class="bi bi-list-check"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 04</span>
                                    <h3>Gabarito</h3>
                                    @if ($process?->answer_publish && now()->gt($process?->answer_publish))
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
                                        {{ $process?->answer_publish?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $process?->answer_publish ? ucfirst($process?->answer_publish->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $process?->answer_publish?->format('Y') ?? '' }}</div>
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
                                                {{ $process?->revision_start?->format('d') ?? '—' }}</div>
                                            <div class="date-box-month">
                                                {{ $process?->revision_start ? ucfirst($process?->revision_start->translatedFormat('F Y')) : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="date-box date-box-end">
                                            <div class="date-box-label">Encerramento</div>
                                            <div class="date-box-day">
                                                {{ $process?->revision_end?->format('d') ?? '—' }}</div>
                                            <div class="date-box-month">
                                                {{ $process?->revision_end ? ucfirst($process?->revision_end->translatedFormat('F Y')) : '—' }}
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
                            class="detail-card detail-orange {{ $process?->result_publish && now()->gt($process?->result_publish) ? 'detail-done' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon orange-bg"><i class="bi bi-list-ol"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 06</span>
                                    <h3>Resultado Final</h3>
                                    @if ($process?->result_publish && now()->gt($process?->result_publish))
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
                                        {{ $process?->result_publish?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $process?->result_publish ? ucfirst($process?->result_publish->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $process?->result_publish?->format('Y') ?? '' }}</div>
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
                            class="detail-card detail-teal {{ $process?->enrol_start && now()->gte($process?->enrol_start) ? 'detail-active' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon teal-bg"><i class="bi bi-pin-angle-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 07</span>
                                    <h3>1ª Chamada</h3>
                                    @if ($process?->enrol_start && now()->gte($process?->enrol_start))
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
                                        {{ $process?->enrol_start?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $process?->enrol_start ? ucfirst($process?->enrol_start->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $process?->enrol_start?->format('Y') ?? '' }}</div>
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
                            class="detail-card detail-teal {{ $process?->enrol_remaining && now()->gte($process?->enrol_remaining) ? 'detail-active' : '' }}">
                            <div class="detail-phase">
                                <div class="detail-phase-icon teal2-bg"><i class="bi bi-pin-angle-fill"></i></div>
                                <div class="detail-phase-info">
                                    <span class="detail-phase-num">Etapa 08</span>
                                    <h3>Vagas Remanescentes</h3>
                                    @if ($process?->enrol_remaining && now()->gte($process?->enrol_remaining))
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
                                        {{ $process?->enrol_remaining?->format('d') ?? '—' }}</div>
                                    <div class="date-box-single-rest">
                                        <div class="date-box-single-month">
                                            {{ $process?->enrol_remaining ? ucfirst($process?->enrol_remaining->translatedFormat('F')) : '—' }}
                                        </div>
                                        <div class="date-box-single-year">
                                            {{ $process?->enrol_remaining?->format('Y') ?? '' }}</div>
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
    @if ($isInscriptionOpen)
        @include('partials.site.cta')
    @endif

@endsection

@push('scripts')    
    <script src="{{ asset('assets/js/site/process/index.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/site/home/confirm.js') }}"></script>
@endpush
