@extends('layouts.errors')

@section('error_title', 'Sessão expirada')
@section('error_code',    '419')
@section('error_icon',    'bi-clock-history')
@section('error_heading', 'Sessão expirada')

{{-- 419 — Sessão expirada · ícone bi-clock-history · botão principal recarrega a página (resolve o problema na maioria dos casos) · dicas apontam para login e cadastro, pois é onde o CSRF geralmente expira. --}}

@section('error_message')
    Sua sessão expirou por inatividade ou o token de segurança ficou inválido.<br>
    Recarregue a página e tente novamente.
@endsection

@section('error_actions')
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="javascript:location.reload()" class="err-btn-primary">
            <i class="bi bi-arrow-clockwise me-2"></i> Recarregar Página
        </a>
        <a href="{{ route('home') }}" class="err-btn-outline">
            <i class="bi bi-house me-2"></i> Voltar ao Início
        </a>
    </div>
@endsection

@section('error_hints')
    <p>Isso costuma acontecer quando a aba fica aberta por muito tempo sem uso.</p>
    <div class="err-hint-links">
        <a href="{{ route('guest.login') }}" class="err-hint-link">
            <i class="bi bi-box-arrow-in-right"></i> Fazer Login
        </a>
        <a href="{{ route('guest.register') }}" class="err-hint-link">
            <i class="bi bi-person-plus"></i> Criar Conta
        </a>
    </div>
@endsection