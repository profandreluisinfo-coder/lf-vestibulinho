@extends('layouts.site')

@section('title', 'Publicações')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/publications/index.css') }}" />
@endpush

@section('content')
    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb-nav" aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">Início</a> / <span>Publicações</span>
            </nav>
        </div>
    </section>

    <section class="pub-section">
        <div class="container">
            <div class="mb-4 reveal">
                <div class="section-tag">Listas e Comunicados</div>
                <h1 class="section-title mb-2">Publicações do <span>Vestibulinho {{ $process?->year }}</span></h1>
                <p class="section-lead">Listas de inscrições, nome social e demais comunicados oficiais, da mais
                    recente para a mais antiga.</p>
            </div>

            {{-- Ajuste o texto conforme o que o edital prevê sobre a divulgação das listas --}}
            <div class="pub-notice reveal">
                <i class="bi bi-shield-check"></i>
                <p>As listas identificam os candidatos pelo <strong>número de inscrição</strong>. Para ver sua
                    situação individual, acesse a <a href="{{ route('login') }}">Área do Candidato</a>.</p>
            </div>

            {{-- Filtros: categorias são links (URL compartilhável), a busca é um formulário GET --}}
            <form method="GET" action="{{ route('site.publications.index') }}" class="pub-filters">
                @if ($activeCategory)
                    <input type="hidden" name="categoria" value="{{ $activeCategory }}">
                @endif

                <div class="pub-chips" role="group" aria-label="Filtrar por categoria">
                    <a href="{{ route('site.publications.index', array_filter(['q' => $search])) }}"
                        class="pub-chip @unless ($activeCategory) active @endunless">Todas</a>
                    @foreach ($categories as $key => $cat)
                        <a href="{{ route('site.publications.index', array_filter(['categoria' => $key, 'q' => $search])) }}"
                            class="pub-chip @if ($activeCategory === $key) active @endif">
                            <i class="bi bi-{{ $cat['icon'] }}"></i> {{ $cat['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="pub-search">
                    <i class="bi bi-search"></i>
                    <input type="search" name="q" value="{{ $search }}" placeholder="Buscar por título"
                        aria-label="Buscar publicações">
                </div>
            </form>

            @forelse ($publications as $pub)
                @php
                    $cat = $categories[$pub->category] ?? ['label' => 'Comunicado', 'icon' => 'megaphone'];
                @endphp

                <article class="pub-card reveal delay-{{ min($loop->iteration, 4) }}">
                    <div class="pub-date" aria-hidden="true">
                        <div class="day">{{ $pub->published_at->format('d') }}</div>
                        <div class="mon">{{ ucfirst($pub->published_at->translatedFormat('M')) }}</div>
                        <div class="year">{{ $pub->published_at->format('Y') }}</div>
                    </div>

                    <div class="pub-body">
                        <div class="pub-tags">
                            <span class="pub-cat"><i class="bi bi-{{ $cat['icon'] }}"></i> {{ $cat['label'] }}</span>

                            @if ($pub->is_new)
                                <span class="pub-tag pub-tag-new">Nova</span>
                            @endif

                            @if ($pub->rectified_at)
                                <span class="pub-tag pub-tag-rect">Retificada em
                                    {{ $pub->rectified_at->format('d/m') }}</span>
                            @endif

                            @if ($pub->appeal_deadline)
                                @if ($pub->is_appeal_open)
                                    <span class="pub-tag pub-tag-appeal"><i class="bi bi-hourglass-split"></i> Recurso
                                        até {{ $pub->appeal_deadline->format('d/m') }} às
                                        {{ $pub->appeal_deadline->format('H:i') }}</span>
                                @else
                                    <span class="pub-tag pub-tag-muted">Prazo de recurso encerrado</span>
                                @endif
                            @endif
                        </div>

                        <h2 class="pub-title">{{ $pub->title }}</h2>

                        @if ($pub->description)
                            <p class="pub-desc">{{ $pub->description }}</p>
                        @endif

                        <div class="pub-meta">Publicada em {{ $pub->published_at->format('d/m/Y') }} às
                            {{ $pub->published_at->format('H:i') }}</div>
                    </div>

                    <a href="{{ Storage::url($pub->file) }}" class="pub-download" target="_blank"
                        rel="noopener noreferrer">
                        <i class="bi bi-file-earmark-pdf-fill"></i> <span>Baixar PDF</span>
                    </a>
                </article>
            @empty
                <div class="pub-empty reveal">
                    <i class="bi bi-inbox"></i>
                    <h3>Nenhuma publicação encontrada</h3>
                    <p>
                        @if ($activeCategory || $search)
                            Tente outra categoria ou limpe a busca.
                            <a href="{{ route('site.publications.index') }}">Ver todas</a>
                        @else
                            As listas e comunicados aparecem aqui conforme forem publicados.
                        @endif
                    </p>
                </div>
            @endforelse

            @if ($publications->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $publications->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>
@endsection