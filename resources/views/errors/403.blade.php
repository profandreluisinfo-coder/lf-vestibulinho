@extends('layouts.errors')

@section('error_title', 'Acesso não autorizado')
@section('error_code',    '403')
@section('error_icon',    'bi-shield-lock')
@section('error_heading', 'Acesso não autorizado')
@section('error_message')
    Você não tem permissão para acessar esta página.<br>
    Faça login com uma conta autorizada ou volte ao início.
@endsection

@section('error_actions')
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('guest.login') }}" class="err-btn-primary">
            <i class="bi bi-box-arrow-in-right me-2"></i> Fazer Login
        </a>
        <a href="{{ route('home') }}" class="err-btn-outline">
            <i class="bi bi-house me-2"></i> Voltar ao Início
        </a>
    </div>
@endsection