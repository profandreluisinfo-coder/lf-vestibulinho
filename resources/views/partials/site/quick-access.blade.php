{{-- ═══════════════ ACESSO RÁPIDO ═══════════════ --}}
@php
    // Process::isInscriptionOpen() já inclui status === 'open'
    $inscricoesAbertas = (bool) $process?->isInscriptionOpen();
    $hasPublications = Route::has('site.publications.index');

    // A ordem aqui é a ordem na tela. Para esconder um card, use 'show' => false.
    $quickLinks = [
        [
            'show' => $hasPublications,
            'href' => $hasPublications ? route('site.publications.index') : '#',
            'icon' => 'card-checklist',
            'title' => 'Publicações',
            'desc' => 'Listas de deferidos e indeferidos',
        ],
        [
            'show' => true,
            'href' => route('site.notices.index'),
            'icon' => 'megaphone-fill',
            'title' => 'Comunicados',
            'desc' => 'Avisos e comunicados importantes',
        ],
        [
            'show' => false, // Desativado temporariamente
            'href' => '#',
            'icon' => 'file-earmark-text-fill',
            'title' => 'Recursos',
            'desc' => 'Listas de resultados de análises de recursos',
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
            'href' => '#',
            'icon' => 'bar-chart-fill',
            'title' => 'Classificação',
            'desc' => 'Resultado e lista de aprovados',
        ],
        [
            'show' => true,
            // 'href' => route('site.calls.index'),
            'href' => '#',
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
