@extends('layouts.home.master')

@section('page-title', 'Vestibulinho LF ' . $calendar?->year)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/quick-access/styles.css') }}">
@endpush

@section('has-footer', 'has-footer')

@section('content')

    @include('guest.home.navbar')
    @include('guest.home.hero-banner')
    @include('guest.home.quick-access')
    @include('guest.home.courses')
    @include('guest.home.countdown')
    @include('guest.home.footer')

@endsection

@push('scripts')
    <script src="{{ asset('assets/home/navbar-scrolled.js') }}"></script>
    <script src="{{ asset('assets/home/navbar-scroll-effect.js') }}"></script>
    <script src="{{ asset('assets/home/scrolling.js') }}"></script>
    <script src="{{ asset('assets/components/countdown.js') }}"></script>
@endpush
