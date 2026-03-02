@extends('layouts.guest.master')

@section('page-title', 'Vestibulinho LF ' . $calendar?->year)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/quick-access/styles.css') }}">
@endpush

@section('has-footer', 'has-footer')

@section('content')

    @include('components.guest.navbar')
    @include('components.guest.hero-banner')
    @include('components.guest.quick-access')
    @include('components.guest.courses')
    @include('components.guest.countdown')
    @include('components.guest.footer')

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/guest/navbar-scrolled.js') }}"></script>
    <script src="{{ asset('assets/js/guest/navbar-scroll-effect.js') }}"></script>
    <script src="{{ asset('assets/js/guest/scrolling.js') }}"></script>
    <script src="{{ asset('assets/js/guest/countdown.js') }}"></script>
@endpush
