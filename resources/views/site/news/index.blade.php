@extends('layouts.site')

@section('title', 'Notícias - EM Dr Leandro Franceschini')

@section('content')

<!-- ===== HEADER SECTION ===== -->
<section class="news-section">
    <div class="container-lg">
        <div class="section-header">
            <h1 class="section-title">
                Todas as <span>Notícias</span>
            </h1>
            
            <p class="section-lead">
                Acompanhe os acontecimentos, eventos e informações importantes 
                da EM Dr Leandro Franceschini.
            </p>
        </div>

        <!-- Grid de Notícias -->
        <div class="news-grid">
            @forelse($noticias as $noticia)
                <div class="reveal">
                    <div class="news-card">
                        <div class="news-card-image news-card-image-teal">
                            📰
                        </div>
                        
                        <div class="news-card-body">
                            <span class="news-card-badge news-card-badge-teal">
                                Notícia
                            </span>
                            
                            <h3 class="news-card-title">
                                {{ $noticia->titulo }}
                            </h3>
                            
                            <p class="news-card-desc">
                                {{ $noticia->resumo }}
                            </p>
                            
                            <div class="news-card-meta">
                                <span class="news-card-date">
                                    {{ $noticia->publicada_em->format('d/m/Y') }}
                                </span>
                                <a href="{{ route('noticias.show', $noticia->slug) }}" 
                                   class="news-card-link news-card-link-teal">
                                    Ler mais →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 0;">
                    <p style="color: var(--muted); font-size: 1rem;">
                        Nenhuma notícia publicada no momento.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($noticias->hasPages())
            <div class="has-pagination">
                {{ $noticias->links() }}
            </div>
        @endif
    </div>
</section>

@endsection