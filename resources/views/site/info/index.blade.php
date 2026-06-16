@extends('layouts.site')

@section('title', 'Comunicados — Vestibulinho ' . ($calendar?->year ?? config('app.year')))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/pages/news.css') }}" />
@endpush

@section('content')
    <section class="section-news">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 reveal">
                    <div class="fa-header-accent">
                        <i class="bi bi-megaphone-fill"></i>
                        Comunicados
                    </div>

                    <h2 class="section-title">Todas os comunicados</h2>

                    <p class="section-lead mt-2">
                        Confira todas os comunicados publicados sobre a escola.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 reveal">

                    @if ($infos->isEmpty())
                        <div class="fa-empty">
                            <i class="bi bi-inbox"></i>
                            Nenhum comunicado publicado no momento.
                        </div>
                    @else
                        <div class="news-list">
                            @foreach ($infos as $info)
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
                                    $icone = $iconeMap[$info->category->type] ?? 'bi-megaphone-fill';

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
                                    $label = $labelMap[$info->category->type] ?? 'Aviso';
                                @endphp

                                <a href="{{ route('infos.show', $info->slug) }}"
                                    class="news-item delay-{{ ($loop->index % 4) + 1 }}">

                                    <div class="news-icon type-{{ $info->category->type ?? 'info' }}">
                                        <i class="bi {{ $icone }}"></i>
                                    </div>

                                    <div class="news-body">
                                        <div class="news-titulo">{{ $info->title }}</div>

                                        @if (!empty($info->resume))
                                            <div class="news-resumo">{!! $info->resume !!}</div>
                                        @endif

                                        <div class="news-meta">
                                            <span class="news-data">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $info->published_at?->format('d/m/Y') ?? $info->created_at->format('d/m/Y') }}
                                            </span>
                                            <span class="news-badge badge-{{ $info->category->type ?? 'info' }}">
                                                {{ $label }}
                                            </span>
                                        </div>
                                    </div>
                                    <i class="bi bi-arrow-right news-arrow"></i>
                                </a>
                            @endforeach
                        </div>
                        @if ($infos->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $infos->links() }}
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
