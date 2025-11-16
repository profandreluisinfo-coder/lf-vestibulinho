@extends('layouts.errors.master')

@section('page-title', '419 - Sessão Expirada')

@section('error_title', 'Sessão Expirada')

@section('error_message', 'Sua sessão expirou. Por favor, recarregue a página e tente novamente.')

@push('scripts')
    <script src="{{ asset('assets/js/419.js') }}"></script>
@endpush