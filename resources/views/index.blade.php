@extends('layouts.home.master')
@section('page-title', 'Vestibulinho LF ' . $calendar?->year)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/quick-access/styles.css') }}">
@endpush

@section('has-footer', 'has-footer')

@section('content')

    @include('home.navbar')
    @include('home.hero-banner')
    @include('home.countdown')
    @include('home.quick-access')
    @include('home.courses')
    @include('home.footer')

@endsection

@push('scripts')
    <script src="{{ asset('assets/home/navbar-scrolled.js') }}"></script>
    <script src="{{ asset('assets/home/navbar-scroll-effect.js') }}"></script>
    <script src="{{ asset('assets/home/scrolling.js') }}"></script>
    <script src="{{ asset('assets/components/countdown.js') }}"></script>
@endpush