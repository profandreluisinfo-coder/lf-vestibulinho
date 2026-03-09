@extends('layouts.guest.master')

@section('page-title', 'Vestibulinho LF ' . $calendar?->year)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guest/quick-access.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/guest/faqs.css') }}">
@endpush

@section('body-class', 'bg-light')
@section('has-footer', 'has-footer')

@section('content')

    @include('guest.home.navbar')
    @include('guest.home.hero-banner')
    @include('guest.home.quick-access')
    @include('guest.home.register')
    @include('guest.home.guidelines')
    @include('guest.home.courses')
    @include('guest.home.countdown')
    @include('guest.home.faqs')
    @include('guest.home.footer')

@endsection


@push('plugins')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.4/dist/additional-methods.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/auth.js') }}" type="module"></script>
    <script src="{{ asset('assets/js/ui/guest/navbar-scrolled.js') }}"></script>
    <script src="{{ asset('assets/js/ui/guest/navbar-scroll-effect.js') }}"></script>
    <script src="{{ asset('assets/js/ui/guest/autoScrollEvents.js') }}"></script>
    <script src="{{ asset('assets/js/ui/guest/scrolling.js') }}"></script>
    <script src="{{ asset('assets/js/ui/guest/countdown.js') }}"></script>
    <script src="{{ asset('assets/js/filters/faqs/public.js') }}"></script>
    <script src="{{ asset('assets/js/ui/guest/infinite-scroll.js') }}"></script>
    <script src="{{ asset('assets/js/ui/auth/toggleAllPasswords.js') }}"></script>
@endpush
