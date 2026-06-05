@extends('layouts.errors')

@section('error_title', 'Muitas requisições')
@section('error_code',    '429')
@section('error_icon',    'bi-speedometer2')
@section('error_heading', 'Muitas tentativas')

{{-- 429 — Muitas requisições · ícone bi-speedometer2 · mensagem orienta o usuário a aguardar · ação principal volta à página anterior · hint aponta para o suporte caso o bloqueio seja indevido. --}}

@section('error_message')
    Você realizou muitas requisições em um curto período de tempo.<br>
    Aguarde alguns instantes antes de tentar novamente.
@endsection

@section('error_actions')
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="javascript:history.back()" class="err-btn-primary">
            <i class="bi bi-arrow-left me-2"></i> Voltar
        </a>
        <a href="{{ route('home') }}" class="err-btn-outline">
            <i class="bi bi-house me-2"></i> Ir ao Início
        </a>
    </div>
@endsection

@section('error_hints')
    <p>Se você acredita que isso é um engano, aguarde um minuto e tente novamente.</p>
    <div class="err-hint-links">
        <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br" class="err-hint-link">
            <i class="bi bi-envelope"></i> Contatar Suporte
        </a>
    </div>
@endsection