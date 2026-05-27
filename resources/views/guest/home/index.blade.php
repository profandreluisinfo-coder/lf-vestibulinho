{{-- ═══════════════════════════════════════════════════════════════
     Herança do layout master
════════════════════════════════════════════════════════════════ --}}
@extends('layouts.guest')

{{-- ── Título da página ──────────────────────────────────────── --}}
@section('title', 'Vestibulinho LF ' . ($calendar?->year) . ' · EM Dr. Leandro Franceschini')

{{-- ── CSS específico desta página ──────────────────────────── --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guest/home/index.css') }}" />
@endpush

{{-- ══════════════════════════════════════════════════════════════
     CONTEÚDO PRINCIPAL
══════════════════════════════════════════════════════════════ --}}
@section('content')

{{-- @php
    $open = $calendar?->isInscriptionOpen() ? true : false;
    $show = $calendar?->exists && $settings?->calendar;
@endphp --}}

    {{-- ═══════════════════════ HERO ════════════════════════════ --}}
    @include('partials.hero.home')
    {{-- ═══════════════════════ CURSOS ════════════════════════════ --}}
    @include('partials.guest.cursos')

    @if ($show)
        
        {{-- ═══════════════════════ COMO PARTICIPAR ═════════════════════ --}}    
        @include('partials.guest.como-participar')
        {{-- ═══════════════════════ CALENDÁRIO ═══════════════════════ --}}    
        @include('partials.guest.calendario')
    @endif

    {{-- ═══════════════════════ FAQ ════════════════════════════════ --}}
    @include('partials.guest.faq')

    @if ($show)
        {{-- ═══════════════════════ LINKS RÁPIDOS ════════════════════ --}}
        @include('partials.guest.links-rapidos')
        {{-- ═══════════════════════ CTA INSCRIÇÃO ════════════════════ --}}
        @include('partials.guest.cta')
    @endif

@endsection

{{-- ── JS específico desta página ───────────────────────────── --}}
@push('scripts')
    <script src="{{ asset('assets/js/guest/home/index.js') }}"></script>
@endpush