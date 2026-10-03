{{-- ═══════════════ ACESSO RÁPIDO ═══════════════
     Colocar logo depois do <section class="hero"> em home/index.blade.php:
         @include('partials.site.quick-access')
     Fica fora de qualquer @if de inscrição: os links continuam úteis
     depois de 31/10 (listas, classificação, convocação).
--}}
@php
    // Process::isInscriptionOpen() já inclui status === 'open'
    $inscricoesAbertas = (bool) $process?->isInscriptionOpen();
    $hasPublications = Route::has('site.publications.index');
    $hasCalls = Route::has('site.calls.index');

    // A ordem aqui é a ordem na tela. Para esconder um card, use 'show' => false.
    $quickLinks = [
        [
            'show' => $hasPublications,
            'href' => $hasPublications ? route('site.publications.index') : '#',
            'icon' => 'megaphone-fill',
            'title' => 'Listas e comunicados',
            'desc' => 'Deferidas, indeferidas e avisos',
        ],
        [
            'show' => true,
            'href' => route('login'),
            'icon' => 'person-badge-fill',
            'title' => 'Área do Candidato',
            'desc' => 'Acompanhe sua inscrição',
        ],
        [
            'show' => (bool) $process?->edital,
            'href' => $process?->edital ? Storage::url($process->edital) : '#',
            'icon' => 'file-earmark-text-fill',
            'title' => 'Edital',
            'desc' => 'Regras e regulamento completo',
            'external' => true,
        ],
        [
            'show' => $inscricoesAbertas,
            'href' => route('register'),
            'icon' => 'person-plus-fill',
            'title' => 'Registrar-se',
            'desc' => 'Crie seu acesso',
        ],
        [
            'show' => true,
            'href' => route('site.results.index'),
            'icon' => 'bar-chart-fill',
            'title' => 'Classificação',
            'desc' => 'Resultado e lista de aprovados',
        ],
        [
            'show' => $hasCalls,
            'href' => $hasCalls ? route('site.calls.index') : '#',
            'icon' => 'bell-fill',
            'title' => 'Convocação',
            'desc' => 'Chamada para matrícula',
        ],
        [
            'show' => true,
            'href' => route('site.archives.index'),
            'icon' => 'journal-bookmark-fill',
            'title' => 'Provas Anteriores',
            'desc' => 'Treine com edições passadas',
        ],
    ];
@endphp

<section class="quick-access" id="acesso-rapido">
    <div class="container">
        <nav class="qa-grid reveal" aria-label="Acesso rápido">
            @foreach ($quickLinks as $link)
                @continue(!$link['show'])
                <a href="{{ $link['href'] }}" class="qa-card"
                    @if (!empty($link['external'])) target="_blank" rel="noopener" @endif>
                    <span class="qa-icon"><i class="bi bi-{{ $link['icon'] }}"></i></span>
                    <span class="qa-title">{{ $link['title'] }}</span>
                    <span class="qa-desc">{{ $link['desc'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</section>
