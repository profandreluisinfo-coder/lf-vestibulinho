@extends('layouts.site')

@section('title', 'Vestibulinho - Notícias e Comunicados')

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
                        Notícias e Comunicados
                    </div>

                    <h2 class="section-title">Todas as notícias e comunicados</h2>

                    <p class="section-lead mt-2">
                        Confira todas as notícias e comunicados publicados sobre a escola.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 reveal">

                    @if ($posts->isEmpty())
                        <div class="fa-empty">
                            <i class="bi bi-inbox"></i>
                            Nenhuma noticia publicada no momento.
                        </div>
                    @else
                        <div class="posts-list">
                            @foreach ($posts as $post)
                                @php
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
                                    $icone = $iconeMap[$post->category->type] ?? 'bi-megaphone-fill';

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
                                    $label = $labelMap[$post->category->type] ?? 'Aviso';
                                @endphp

                                <a href="{{ route('site.posts.show', $post->slug) }}"
                                    class="posts-item delay-{{ ($loop->index % 4) + 1 }}">

                                    <div class="posts-icon type-{{ $post->category->type ?? 'info' }}">
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
                                            <span class="posts-badge badge-{{ $post->category->type ?? 'info' }}">
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