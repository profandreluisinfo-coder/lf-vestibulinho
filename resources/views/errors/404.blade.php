@extends('layouts.errors.master')

@section('page-title', '404 - Página Não Encontrada')

@section('error_title', 'Ops! Página Não Encontrada')

@section('error_message', 'Desculpe, a página que você está procurando não existe.')

@push('scripts')
    <script src="{{ asset('assets/js/404.js') }}"></script>
@endpush