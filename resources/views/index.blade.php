@extends('layouts.guest.master')

@section('page-title', 'Vestibulinho LF ' . $calendar?->year)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/quick-access/styles.css') }}">
@endpush

@section('has-footer', 'has-footer')

@section('content')

    @include('guest.home.navbar')
    @include('guest.home.hero-banner')
    @include('guest.home.quick-access')
    @include('guest.home.guidelines')
    @include('guest.home.courses')
    @include('guest.home.countdown')
    @include('guest.home.footer')

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/ui/guest/navbar-scrolled.js') }}"></script>
    <script src="{{ asset('assets/js/ui/guest/navbar-scroll-effect.js') }}"></script>
    <script src="{{ asset('assets/js/ui/guest/scrolling.js') }}"></script>
    <script src="{{ asset('assets/js/ui/guest/countdown.js') }}"></script>
@endpush
