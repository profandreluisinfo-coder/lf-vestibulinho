@extends('layouts.errors')

@section('error_title', 'Página não encontrada')
@section('error_code',    '404')
@section('error_icon',    'bi-compass')
@section('error_heading', 'Página não encontrada')

@section('error_message')
    O endereço que você tentou acessar não existe ou foi movido.<br>
    Verifique o link e tente novamente.
@endsection

{{-- Sobrescreve os botões para adicionar o link do calendário --}}
@section('error_actions')
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('home') }}" class="err-btn-primary">
            <i class="bi bi-house-fill me-2"></i> Voltar ao Início
        </a>
        <a href="javascript:history.back()" class="err-btn-outline">
            <i class="bi bi-arrow-left me-2"></i> Página Anterior
        </a>
    </div>
@endsection

{{-- Dicas de navegação --}}
@section('error_hints')
    <p>Talvez você estivesse procurando por:</p>
    <div class="err-hint-links">
        <a href="{{ route('home') }}#cursos" class="err-hint-link">
            <i class="bi bi-grid-3x3-gap"></i> Cursos
        </a>
        <a href="{{ route('site.calendar.show') }}" class="err-hint-link">
            <i class="bi bi-calendar3"></i> Calendário
        </a>
        <a href="{{ route('login') }}" class="err-hint-link">
            <i class="bi bi-person-circle"></i> Área do Candidato
        </a>
        <a href="{{ route('site.faqs.index') }}#faq" class="err-hint-link">
            <i class="bi bi-question-circle"></i> FAQ
        </a>
    </div>
@endsection