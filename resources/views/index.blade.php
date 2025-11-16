@extends('layouts.home.master')
@section('page-title', 'Vestibulinho LF ' . $calendar?->year)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/calendar/styles.css') }}">
@endpush

@section('body-class', 'py-5')

@section('content')
    @include('home.navbar')
    @include('home.hero-banner')
    @include('home.countdown')
    @include('home.quick-access')
    @include('home.informations')
    @include('home.courses')
    @include('home.calendar')
    @include('home.previous-exams')
    @include('home.faq')
    @include('home.cta')
    @include('home.footer')
@endsection

@push('scripts')
<script src="{{ asset('assets/home/scrolling.js') }}"></script>
<script src="{{ asset('assets/home/navbar-scrolled.js') }}"></script>
<script src="{{ asset('assets/components/countdown.js') }}"></script>
@endpush