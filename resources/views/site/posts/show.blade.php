@extends('layouts.site')

@section('title', $post?->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/posts/show.css') }}" />
@endpush

@section('content')

@php
    // Define o tipo do post uma única vez e reaproveita na página toda
    $isComunicado = $post->type === \App\Models\Post::TYPE_INFO;
    $typeLabel    = $isComunicado ? 'Comunicado' : 'Notícia';
    $badgeClass   = $isComunicado ? 'news-card-badge-navy' : 'news-card-badge-teal';

    // Lista de origem (usada no breadcrumb e no botão "Voltar")
    $listaRoute = $isComunicado ? route('site.notices.index') : route('site.news.index');
    $listaLabel = $isComunicado ? 'Comunicados' : 'Notícias';
@endphp

<!-- ===== BREADCRUMB ===== -->
<section class="post-breadcrumb-section">
    <div class="container-lg">
        <nav class="post-breadcrumb-nav">
            <a href="{{ route('home') }}" class="post-breadcrumb-link">
                Home
            </a>
            <span> / </span>
            <a href="{{ $listaRoute }}" class="post-breadcrumb-link">
                {{ $listaLabel }}
            </a>
            <span> / </span>
            <span>{{ $post->title }}</span>
        </nav>
    </div>
</section>

<!-- ===== NOTÍCIA / COMUNICADO ===== -->
<section class="post-section">
    <div class="container-lg">
        <div class="post-container">

            <!-- Cabeçalho -->
            <div class="post-header">
                <span class="news-card-badge {{ $badgeClass }} post-badge">
                    {{ $typeLabel }}
                </span>

                <h1 class="section-title post-title">
                    {{ $post->title }}
                </h1>

                <div class="post-info-container">
                    <div>
                        <strong class="post-info-label">Publicado em:</strong>
                        {{ $post->published_at->translatedFormat('d \d\e F \d\e Y') }}
                    </div>

                    @if ($post->author)
                        <div>
                            <strong class="post-info-label">Por:</strong>
                            {{ $post->author->name }}
                        </div>
                    @endif
                </div>

                <div class="post-divider"></div>
            </div>

            <!-- Imagem -->
            @if ($post->image)
                <div class="post-image-container">
                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}">
                </div>
            @endif

            <!-- Conteúdo -->
            <div class="post-content">
                {!! $post->content !!}
            </div>

            <!-- Vídeo (quando a url for um vídeo) -->
@if ($post->video)
    <div class="post-video-container">
        @if ($post->video['type'] === 'iframe')
            <iframe src="{{ $post->video['src'] }}"
                    title="{{ $post->title }}"
                    loading="lazy"
                    allowfullscreen></iframe>
        @else
            <video controls preload="metadata" src="{{ $post->video['src'] }}"></video>
        @endif
    </div>
@endif

            <!-- Link relacionado (coluna "url") -->
            @if ($post->url && \Illuminate\Support\Str::startsWith($post->url, ['http://', 'https://']))
                <div class="post-link-container">
                    <a href="{{ $post->url }}"
                       class="post-external-link"
                       target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-box-arrow-up-right"></i>
                        Acessar link relacionado
                    </a>
                </div>
            @endif

            <!-- Arquivos anexos -->
            @if ($post->attachments->isNotEmpty())
                <div class="post-attachments">
                    <h2 class="post-attachments-title">
                        <i class="bi bi-paperclip"></i> Arquivos para download
                    </h2>

                    <ul class="post-attachments-list">
                        @foreach ($post->attachments as $attachment)
                            <li>
                                <a href="{{ Storage::url($attachment->path) }}"
                                   class="post-attachment-link"
                                   target="_blank" rel="noopener">
                                    <i class="bi {{ $attachment->mime_type === 'application/pdf' ? 'bi-file-earmark-pdf-fill' : 'bi-file-earmark-fill' }}"></i>
                                    <span class="post-attachment-name">{{ $attachment->name }}</span>

                                    @if ($attachment->size)
                                        <small class="post-attachment-size">
                                            {{ number_format($attachment->size / 1024, 0, ',', '.') }} KB
                                        </small>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Divisor -->
            <div class="post-divider"></div>

            <!-- Navegação entre posts -->
            <div class="post-nav-container">
                <!-- Anterior -->
                @if ($previous)
                    <a href="{{ route('site.posts.show', $previous->slug) }}"
                       class="post-nav-link">
                        <div class="post-nav-label">
                            ← Anterior
                        </div>
                        <div class="post-nav-title">
                            {{ \Illuminate\Support\Str::limit($previous->title, 50) }}
                        </div>
                    </a>
                @else
                    <div></div>
                @endif

                <!-- Próximo -->
                @if ($next)
                    <a href="{{ route('site.posts.show', $next->slug) }}"
                       class="post-nav-link post-nav-link-next">
                        <div class="post-nav-label">
                            Próximo →
                        </div>
                        <div class="post-nav-title">
                            {{ \Illuminate\Support\Str::limit($next->title, 50) }}
                        </div>
                    </a>
                @else
                    <div></div>
                @endif
            </div>

            <!-- Voltar -->
            <div class="post-back-container">
                <a href="{{ $listaRoute }}" class="btn-hero-primary">
                    <i class="bi bi-arrow-left me-2"></i> Voltar para {{ mb_strtolower($listaLabel) }}
                </a>
            </div>
        </div>
    </div>
</section>

@endsection