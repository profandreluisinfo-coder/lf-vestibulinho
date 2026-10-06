@extends('layouts.site')

@php
    // ===== Textos da página (mudam conforme a rota) =====
    $titulo = match ($type ?? null) {
        \App\Models\Post::TYPE_NOTICIA => 'Notícias',
        \App\Models\Post::TYPE_INFO    => 'Comunicados',
        default                        => 'Notícias e Comunicados',
    };

    $subtitulo = match ($type ?? null) {
        \App\Models\Post::TYPE_NOTICIA => 'Todas as notícias',
        \App\Models\Post::TYPE_INFO    => 'Todos os comunicados',
        default                        => 'Todas as notícias e comunicados',
    };

    $descricao = match ($type ?? null) {
        \App\Models\Post::TYPE_NOTICIA => 'Confira todas as notícias publicadas sobre o Vestibulinho LF.',
        \App\Models\Post::TYPE_INFO    => 'Confira todos os comunicados publicados sobre o Vestibulinho LF.',
        default                        => 'Confira todas as notícias e comunicados publicados sobre o Vestibulinho LF.',
    };

    $mensagemVazia = match ($type ?? null) {
        \App\Models\Post::TYPE_NOTICIA => 'Nenhuma notícia publicada no momento.',
        \App\Models\Post::TYPE_INFO    => 'Nenhum comunicado publicado no momento.',
        default                        => 'Nenhuma notícia ou comunicado publicado no momento.',
    };

    // ===== Ícones e rótulos por tipo de categoria =====
    // (ficam aqui fora do @foreach para não serem recriados a cada post)
    $iconeMap = [
        'info' => 'bi-info-circle-fill',
        'alerta' => 'bi-exclamation-triangle-fill',
        'urgente' => 'bi-exclamation-octagon-fill',
        'importante' => 'bi-bookmark-star-fill',
        'prazo' => 'bi-clock-fill',
        'edital' => 'bi-file-earmark-text-fill',
        'resultado' => 'bi-trophy-fill',
        'aprovacao' => 'bi-patch-check-fill',
        'inscricao' => 'bi-pencil-square',
        'documento' => 'bi-folder-fill',
        'calendario' => 'bi-calendar-event-fill',
        'prova' => 'bi-journal-check',
        'convocacao' => 'bi-person-lines-fill',
        'cancelamento' => 'bi-x-octagon-fill',
        'manutencao' => 'bi-tools',
        'sistema' => 'bi-cpu-fill',
        'novidade' => 'bi-stars',
        'sucesso' => 'bi-check-circle-fill',
        'erro' => 'bi-bug-fill',
        'financeiro' => 'bi-cash-stack',
        'local' => 'bi-geo-alt-fill',
    ];

    $labelMap = [
        'info' => 'Informativo',
        'alerta' => 'Atenção',
        'urgente' => 'Urgente',
        'importante' => 'Importante',
        'prazo' => 'Prazo',
        'edital' => 'Edital',
        'resultado' => 'Resultado',
        'aprovacao' => 'Aprovação',
        'inscricao' => 'Inscrições',
        'documento' => 'Documentação',
        'calendario' => 'Calendário',
        'prova' => 'Prova',
        'convocacao' => 'Convocação',
        'cancelamento' => 'Cancelamento',
        'manutencao' => 'Manutenção',
        'sistema' => 'Sistema',
        'novidade' => 'Novidade',
        'sucesso' => 'Concluído',
        'erro' => 'Erro',
        'financeiro' => 'Financeiro',
        'local' => 'Local de Prova',
    ];
@endphp

@section('title', $titulo)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/posts/index.css') }}" />
@endpush

@section('content')
    <section class="section-posts">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 reveal">
                    <div class="fa-header-accent">
                        <i class="bi bi-megaphone-fill"></i>
                        {{ $titulo }}
                    </div>

                    <h2 class="section-title">{{ $subtitulo }}</h2>

                    <p class="section-lead mt-2">
                        {{ $descricao }}
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 reveal">

                    @if ($posts->isEmpty())
                        <div class="fa-empty">
                            <i class="bi bi-inbox"></i>
                            {{ $mensagemVazia }}
                        </div>
                    @else
                        <div class="posts-list">
                            @foreach ($posts as $post)
                                @php
                                    // O "?" evita erro se o post não tiver categoria
                                    $categoriaTipo = $post->category?->type ?? 'info';
                                    $icone = $iconeMap[$categoriaTipo] ?? 'bi-megaphone-fill';
                                    $label = $labelMap[$categoriaTipo] ?? 'Aviso';
                                @endphp

                                <a href="{{ route('site.posts.show', $post->slug) }}"
                                    class="posts-item delay-{{ ($loop->index % 4) + 1 }}">

                                    <div class="posts-icon type-{{ $categoriaTipo }}">
                                        <i class="bi {{ $icone }}"></i>
                                    </div>

                                    <div class="posts-body">
                                        <div class="posts-titulo">{{ $post->title }}</div>

                                        @if (!empty($post->resume))
                                            <div class="posts-resume">{!! $post->resume !!}</div>
                                        @endif

                                        <div class="posts-meta">
                                            <span class="posts-data">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $post->published_at?->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}
                                            </span>
                                            <span class="posts-badge badge-{{ $categoriaTipo }}">
                                                {{ $label }}
                                            </span>
                                        </div>
                                    </div>
                                    <i class="bi bi-arrow-right posts-arrow"></i>
                                </a>
                            @endforeach
                        </div>

                        @if ($posts->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection