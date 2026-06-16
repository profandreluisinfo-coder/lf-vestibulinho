@extends('layouts.site')

@section('title', $noticia->titulo . ' - EM Dr Leandro Franceschini')

@section('content')

<!-- ===== BREADCRUMB ===== -->
<section style="background: var(--light); padding: 1.5rem 0;">
    <div class="container-lg">
        <nav style="font-size: 0.9rem; color: var(--muted);">
            <a href="/" style="color: var(--teal); text-decoration: none;">
                Home
            </a>
            <span> / </span>
            <a href="{{ route('noticias.index') }}" style="color: var(--teal); text-decoration: none;">
                Notícias
            </a>
            <span> / </span>
            <span>{{ $noticia->titulo }}</span>
        </nav>
    </div>
</section>

<!-- ===== ARTIGO ===== -->
<section style="background: var(--white); padding: 3rem 0;">
    <div class="container-lg">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Header do Artigo -->
            <div style="margin-bottom: 2rem;">
                <span class="news-card-badge news-card-badge-teal" style="display: inline-block;">
                    Notícia
                </span>

                <h1 class="section-title" style="margin: 1rem 0; color: var(--navy);">
                    {{ $noticia->titulo }}
                </h1>

                <div style="display: flex; gap: 2rem; flex-wrap: wrap; color: var(--muted); font-size: 0.9rem;">
                    <div>
                        <strong style="color: var(--navy);">Por:</strong> 
                        {{ $noticia->autor->name }}
                    </div>
                    <div>
                        <strong style="color: var(--navy);">Publicado em:</strong> 
                        {{ $noticia->publicada_em->format('d \d\e M \d\e Y') }}
                    </div>
                </div>

                <div style="height: 1px; background: rgba(11, 30, 61, 0.1); margin: 2rem 0;"></div>
            </div>

            <!-- Conteúdo -->
            <div style="color: var(--navy); line-height: 1.8; font-size: 1.05rem;">
                {!! nl2br(e($noticia->conteudo)) !!}
            </div>

            <!-- Divider -->
            <div style="height: 2px; background: var(--grad-teal); margin: 3rem 0;"></div>

            <!-- Navegação entre artigos -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 3rem;">
                <!-- Anterior -->
                @if($anterior)
                    <a href="{{ route('noticias.show', $anterior->slug) }}" 
                       style="padding: 1.5rem; background: var(--light); border-radius: var(--radius); 
                              text-decoration: none; transition: all 0.3s;"
                       onmouseover="this.style.background='rgba(0, 168, 150, 0.15)'; this.style.transform='translateX(-4px)';"
                       onmouseout="this.style.background='var(--light)'; this.style.transform='translateX(0)';">
                        <div style="color: var(--muted); font-size: 0.85rem; text-transform: uppercase; 
                                   font-weight: 600; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                            ← Notícia Anterior
                        </div>
                        <div style="color: var(--navy); font-weight: 600;">
                            {{ \Illuminate\Support\Str::limit($anterior->titulo, 50) }}
                        </div>
                    </a>
                @else
                    <div></div>
                @endif

                <!-- Próxima -->
                @if($proxima)
                    <a href="{{ route('noticias.show', $proxima->slug) }}" 
                       style="padding: 1.5rem; background: var(--light); border-radius: var(--radius); 
                              text-decoration: none; transition: all 0.3s; text-align: right;"
                       onmouseover="this.style.background='rgba(0, 168, 150, 0.15)'; this.style.transform='translateX(4px)';"
                       onmouseout="this.style.background='var(--light)'; this.style.transform='translateX(0)';">
                        <div style="color: var(--muted); font-size: 0.85rem; text-transform: uppercase; 
                                   font-weight: 600; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                            Próxima Notícia →
                        </div>
                        <div style="color: var(--navy); font-weight: 600;">
                            {{ \Illuminate\Support\Str::limit($proxima->titulo, 50) }}
                        </div>
                    </a>
                @else
                    <div></div>
                @endif
            </div>

            <!-- Voltar -->
            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('noticias.index') }}" class="btn-hero-primary">
                    ← Voltar para Notícias
                </a>
            </div>
        </div>
    </div>
</section>

@endsection