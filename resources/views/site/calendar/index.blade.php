{{-- ═══════════════════════════════════════════════════════════════
     Herança do layout master
════════════════════════════════════════════════════════════════ --}}
@extends('layouts.site')

{{-- ── Título da página ──────────────────────────────────────── --}}
@section('title', 'Vestibulinho LF ' . ($calendar?->year) . ' · Calendário · EM Dr. Leandro Franceschini')

{{-- ── CSS específico desta página ──────────────────────────── --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/pages/calendar.css') }}" />
    <style>
        .calendar-sparkle {
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .95);
            box-shadow: 0 0 18px rgba(255, 255, 255, .45), 0 0 40px rgba(255, 255, 255, .12);
            opacity: 0;
            transform: translate(-50%, -50%) scale(.5);
            pointer-events: none;
            transition: transform .22s ease, opacity .22s ease;
        }

        .tf-step:hover .tf-node {
            transform: scale(1.08);
        }

        .btn-cta-main:hover {
            transform: translateY(-4px) scale(1.02);
        }
    </style>
@endpush

{{-- ══════════════════════════════════════════════════════════════
     CONTEÚDO PRINCIPAL
══════════════════════════════════════════════════════════════ --}}
@section('content')

    <!-- ═══════════════════════ PAGE HERO ════════════════════════ -->
    @include('partials.hero.calendar')
    
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

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/guest/calendar/index.js') }}"></script>
@endpush