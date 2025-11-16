@extends('layouts.errors.master')

@section('title', '429 - Muitas Requisições')

@section('error_title', 'Calma aí!')

@section('error_message', 'Você enviou muitas requisições em um curto período. Por favor, aguarde e tente novamente em alguns instantes.')

@push('scripts')
    <script src="{{ asset('assets/js/429.js') }}"></script>
@endpush