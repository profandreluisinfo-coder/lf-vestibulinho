@extends('layouts.site')

@section('title', $post?->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/posts/show.css') }}" />
@endpush

@section('content')

    @if (!empty($isPreview))
        <div class="alert alert-warning text-center mb-0" role="status">
            <i class="bi bi-eye me-1"></i>
            Pré-visualização administrativa — esta postagem ainda não foi publicada.
        </div>
    @endif

    @php
        $isComunicado = $post->type === \App\Models\Post::TYPE_INFO;
        $typeLabel = $isComunicado ? 'Comunicado' : 'Notícia';
        $badgeClass = $isComunicado ? 'news-card-badge-navy' : 'news-card-badge-teal';
        $listaRoute = $isComunicado ? route('site.notices.index') : route('site.news.index');
        $listaLabel = $isComunicado ? 'Comunicados' : 'Notícias';
    @endphp

    <section class="post-breadcrumb-section">
        <div class="container-lg">
            <nav class="post-breadcrumb-nav" aria-label="Navegação estrutural">
                <a href="{{ route('home') }}" class="post-breadcrumb-link">
                    <i class="bi bi-house-door" aria-hidden="true"></i>
                    <span>Início</span>
                </a>
                <i class="bi bi-chevron-right post-breadcrumb-separator" aria-hidden="true"></i>
                <a href="{{ $listaRoute }}" class="post-breadcrumb-link">{{ $listaLabel }}</a>
                <i class="bi bi-chevron-right post-breadcrumb-separator" aria-hidden="true"></i>
                <span class="post-breadcrumb-current" aria-current="page">{{ $post->title }}</span>
            </nav>
        </div>
    </section>

    <section class="post-section">
        <div class="container-lg">
            <article class="post-container">
                <header class="post-header">
                    <span class="news-card-badge {{ $badgeClass }} post-badge">
                        <i class="bi {{ $isComunicado ? 'bi-megaphone' : 'bi-journal-text' }}" aria-hidden="true"></i>
                        {{ $typeLabel }}
                    </span>

                    <h1 class="section-title post-title">{{ $post->title }}</h1>

                    <div class="post-info-container" aria-label="Informações da publicação">
                        <div class="post-meta-item">
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                            <span>
                                <strong class="post-info-label">Publicado em</strong>
                                {{ $post->published_at->translatedFormat('d \d\e F \d\e Y') }}
                            </span>
                        </div>

                        @if ($post->author)
                            <div class="post-meta-item">
                                <i class="bi bi-person-circle" aria-hidden="true"></i>
                                <span>
                                    <strong class="post-info-label">Publicado por</strong>
                                    {{ $post->author->name }}
                                </span>
                            </div>
                        @endif
                    </div>
                </header>

                @if ($post->image)
                    <figure class="post-image-container">
                        <img src="{{ $previewImageUrl ?? Storage::url($post->image) }}" alt="{{ $post->title }}">
                    </figure>
                @endif

                <div class="post-content">
                    {!! $post->content !!}
                </div>

                @if ($post->video)
                    <div class="post-video-container" aria-label="Vídeo relacionado">
                        @if ($post->video['type'] === 'iframe')
                            <iframe src="{{ $post->video['src'] }}" title="{{ $post->title }}" loading="lazy"
                                allowfullscreen></iframe>
                        @else
                            <video controls preload="metadata" src="{{ $post->video['src'] }}"></video>
                        @endif
                    </div>
                @endif

                @if (!$post->video && $post->url && \Illuminate\Support\Str::startsWith($post->url, ['http://', 'https://']))
                    <div class="post-link-container">
                        <a href="{{ $post->url }}" class="post-external-link" target="_blank" rel="noopener noreferrer">
                            <span class="post-external-link-icon"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></span>
                            <span><strong>Acesse o conteúdo relacionado</strong><small>Você será direcionado para uma página externa</small></span>
                            <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif

                @if ($post->attachments->isNotEmpty())
                    <section class="post-attachments" aria-labelledby="post-attachments-title">
                        <div class="post-attachments-heading">
                            <span class="post-attachments-icon"><i class="bi bi-paperclip" aria-hidden="true"></i></span>
                            <div>
                                <h2 class="post-attachments-title" id="post-attachments-title">Arquivos para download</h2>
                                <p>Materiais e documentos desta publicação</p>
                            </div>
                        </div>

                        <ul class="post-attachments-list">
                            @foreach ($post->attachments as $attachment)
                                <li>
                                    @if (!empty($isPreview))
                                        <span class="post-attachment-link">
                                            <i class="bi {{ $attachment->mime_type === 'application/pdf' ? 'bi-file-earmark-pdf-fill' : 'bi-file-earmark-fill' }}" aria-hidden="true"></i>
                                            <span class="post-attachment-name">{{ $attachment->name }}</span>
                                            @if ($attachment->size)
                                                <small class="post-attachment-size">{{ number_format($attachment->size / 1024, 0, ',', '.') }} KB</small>
                                            @endif
                                        </span>
                                    @else
                                        <a href="{{ Storage::url($attachment->path) }}" class="post-attachment-link" target="_blank" rel="noopener">
                                            <i class="bi {{ $attachment->mime_type === 'application/pdf' ? 'bi-file-earmark-pdf-fill' : 'bi-file-earmark-fill' }}" aria-hidden="true"></i>
                                            <span class="post-attachment-name">{{ $attachment->name }}</span>
                                            @if ($attachment->size)
                                                <small class="post-attachment-size">{{ number_format($attachment->size / 1024, 0, ',', '.') }} KB</small>
                                            @endif
                                            <i class="bi bi-download post-attachment-download" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($previous || $next)
                    <nav class="post-nav-container" aria-label="Outras publicações">
                        @if ($previous)
                            <a href="{{ route('site.posts.show', $previous->slug) }}" class="post-nav-link">
                                <span class="post-nav-label"><i class="bi bi-arrow-left" aria-hidden="true"></i> Publicação anterior</span>
                                <span class="post-nav-title">{{ \Illuminate\Support\Str::limit($previous->title, 70) }}</span>
                            </a>
                        @endif

                        @if ($next)
                            <a href="{{ route('site.posts.show', $next->slug) }}" class="post-nav-link post-nav-link-next">
                                <span class="post-nav-label">Próxima publicação <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                                <span class="post-nav-title">{{ \Illuminate\Support\Str::limit($next->title, 70) }}</span>
                            </a>
                        @endif
                    </nav>
                @endif

                <footer class="post-back-container">
                    <a href="{{ $listaRoute }}" class="post-back-link">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        Voltar para {{ mb_strtolower($listaLabel) }}
                    </a>
                </footer>
            </article>
        </div>
    </section>

@endsection
