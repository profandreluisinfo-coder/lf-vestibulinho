@extends('layouts.errors')

@section('error_title', 'Erro interno')
@section('error_code',    '500')
@section('error_icon',    'bi-exclamation-triangle')
@section('error_heading', 'Erro interno do servidor')
@section('error_message')
    Algo inesperado aconteceu no servidor.<br>
    Nossa equipe já foi notificada. Tente novamente em alguns instantes.
@endsection

@section('error_actions')
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('home') }}" class="err-btn-primary">
            <i class="bi bi-house-fill me-2"></i> Voltar ao Início
        </a>
        <a href="javascript:location.reload()" class="err-btn-outline">
            <i class="bi bi-arrow-clockwise me-2"></i> Tentar Novamente
        </a>
    </div>
@endsection

@section('error_hints')
    <p>Se o problema persistir, entre em contato:</p>
    <div class="err-hint-links">
        <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br" class="err-hint-link">
            <i class="bi bi-envelope"></i> Suporte por e-mail
        </a>
    </div>
@endsection