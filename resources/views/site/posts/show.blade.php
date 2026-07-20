@extends('layouts.site')

@section('title', $post->title . ' - EM Dr Leandro Franceschini')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/posts/show.css') }}" />
@endpush

@section('content')

<!-- ===== BREADCRUMB ===== -->
<section class="post-breadcrumb-section">
    <div class="container-lg">
        <nav class="post-breadcrumb-nav">
            <a href="{{ route('home') }}" class="post-breadcrumb-link">
                Home
            </a>
            <span> / </span>
            <a href="{{ route('site.faqs.index') }}" class="post-breadcrumb-link">
                Notícias e Comunicados
            </a>
            <span> / </span>
            <span>{{ $post->title }}</span>
        </nav>
    </div>
</section>

<!-- ===== COMUNICADO ===== -->
<section class="post-section">
    <div class="container-lg">
        <div class="post-container">
            <!-- Header do Comunicado -->
            <div class="post-header">
                <span class="news-card-badge news-card-badge-navy post-badge">
                    @if($post->type === 'oficial')
                        Comunicado Oficial
                    @elseif($post->type === 'importante')
                        Comunicado Importante
                    @else
                        Comunicado
                    @endif
                </span>

                <h1 class="section-title post-title">
                    {{ $post->title }}
                </h1>

                <div class="post-info-container">
                    <div>
                        <strong class="post-info-label">Publicado em:</strong> 
                        {{ $post->published_at->format('d \d\e M \d\e Y') }}
                    </div>
                    <div>
                        <strong class="post-info-label">Por:</strong> 
                        {{ $post->author->name }}
                    </div>
                </div>

                <div class="post-divider"></div>
            </div>

            <!-- Imagem do Comunicado -->
            @if($post->image)
                <div class="post-image-container">
                    <img src="{{ Storage::url($post->image) }}"
                        alt="Imagem do Comunicado">
                </div>
            @endif

            <!-- Conteúdo -->
            <div class="post-content">
                {!! $post->content !!}
            </div>

            <!-- Divider -->
            <div class="post-divider"></div>

            <!-- Navegação entre news -->
            <div class="post-nav-container">
                <!-- Anterior -->
                @if($previous)
                    <a href="{{ route('site.posts.show', $previous->slug) }}" 
                       class="post-nav-link">
                        <div class="post-nav-label">
                            ← Comunicado Anterior
                        </div>
                        <div class="post-nav-title">
                            {{ \Illuminate\Support\Str::limit($previous->title, 50) }}
                        </div>
                    </a>
                @else
                    <div></div>
                @endif

                <!-- Próximo -->
                @if($next)
                    <a href="{{ route('site.posts.show', $next->slug) }}" 
                       class="post-nav-link post-nav-link-next">
                        <div class="post-nav-label">
                            Próximo Comunicado →
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
                <a href="{{ route('site.posts.index') }}" class="btn-hero-primary">
                    <i class="bi bi-arrow-left me-2"></i> Voltar para notícias
                </a>
            </div>
        </div>
    </div>
</section>

@endsection