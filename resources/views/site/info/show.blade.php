@extends('layouts.site')

@section('title', $info->title . ' - EM Dr Leandro Franceschini')

@section('content')

<!-- ===== BREADCRUMB ===== -->
<section class="post-breadcrumb-section">
    <div class="container-lg">
        <nav class="post-breadcrumb-nav">
            <a href="{{ route('home') }}" class="post-breadcrumb-link">
                Home
            </a>
            <span> / </span>
            <a href="{{ route('infos.index') }}" class="post-breadcrumb-link">
                Comunicados
            </a>
            <span> / </span>
            <span>{{ $info->title }}</span>
        </nav>
    </div>
</section>

<!-- ===== COMUNICADO ===== -->
<section class="post-comunicado-section">
    <div class="container-lg">
        <div class="post-comunicado-container">
            <!-- Header do Comunicado -->
            <div class="post-header">
                <span class="news-card-badge news-card-badge-navy post-badge">
                    @if($info->type === 'oficial')
                        Comunicado Oficial
                    @elseif($info->type === 'importante')
                        Comunicado Importante
                    @else
                        Comunicado
                    @endif
                </span>

                <h1 class="section-title post-title">
                    {{ $info->title }}
                </h1>

                <div class="post-info-container">
                    <div>
                        <strong class="post-info-label">Publicado em:</strong> 
                        {{ $info->published_at->format('d \d\e M \d\e Y') }}
                    </div>
                    <div>
                        <strong class="post-info-label">Por:</strong> 
                        {{ $info->author->name }}
                    </div>
                </div>

                <div class="post-divider"></div>
            </div>

            <!-- Imagem do Comunicado -->
            @if($info->image)
            <div class="post-image-container">
                <img src="{{ $info->image ? Storage::url($info->image) : '' }}"
                    alt="Imagem do Comunicado">
            </div>
            @endif

            <!-- Conteúdo -->
            <div class="post-content">
                {!! $info->content !!}
            </div>

            <!-- Divider -->
            <div class="post-divider"></div>

            <!-- Navegação entre news -->
            <div class="post-nav-container">
                <!-- Anterior -->
                @if($previous)
                    <a href="{{ route('news.show', $previous->slug) }}" 
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
                    <a href="{{ route('news.show', $next->slug) }}" 
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
                <a href="{{ route('infos.index') }}" class="btn-hero-primary">
                    <i class="bi bi-arrow-left me-2"></i> Voltar para comunicados
                </a>
            </div>
        </div>
    </div>
</section>

@endsection