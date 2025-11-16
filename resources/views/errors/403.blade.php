@extends('layouts.errors.master')

@section('page-title', '403 - Acesso Negado')

@section('error_title', 'Acesso Negado')

@section('error_message', 'Você não tem permissão para acessar esta página.')

@push('scripts')
    <script src="{{ asset('assets/js/403.js') }}"></script>
@endpush