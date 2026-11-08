{{-- ═══════════════ ACESSO RÁPIDO ═══════════════ --}}
@php
    $temPublicacoes = $totalPublicacoes > 0;
    $temComunicados = $totalComunicados > 0;

    $quickLinks = [
        [
            'show' => $temPublicacoes,
            'href' => route('site.publications.index'),
            'icon' => 'card-checklist',
            'title' => 'Publicações',
            'desc' => 'Listas de deferidos e indeferidos',
            'badge' => $totalPublicacoes ?: null,
            'badge_title' => "{$totalPublicacoes} lista(s) liberada(s)",
        ],
        [
            'show' => $temComunicados,
            'href' => route('site.notices.index'),
            'icon' => 'megaphone-fill',
            'title' => 'Comunicados',
            'desc' => 'Avisos e comunicados importantes',
            'badge' => $totalComunicados ?: null,
            'badge_title' => "{$totalComunicados} comunicado(s) importante(s)",
        ],
        [
            'show' => filled($process?->edital),
            'href' => filled($process?->edital) ? Storage::url($process?->edital) : '#',
            'icon' => 'file-earmark-text-fill',
            'title' => 'Edital',
            'desc' => 'Regras e regulamento completo',
            'external' => true,
        ],
        [
            'show' => true,
            'href' => route('site.archives.index'),
            'icon' => 'journal-bookmark-fill',
            'title' => 'Provas Anteriores',
            'desc' => 'Treine com edições passadas',
            'badge' => '',
        ],
        [
            'show' => $isInscriptionOpen,
            'href' => route('register'),
            'icon' => 'person-plus-fill',
            'title' => 'Registrar-se',
            'desc' => 'Crie seu acesso',
            'badge' => '',
        ],[
            'show' => $process?->isOpen() ?? false,
            'href' => route('login'),
            'icon' => 'person-fill-lock',
            'title' => 'Área do Candidato',
            'desc' => 'Entre com suas credenciais',
            'badge' => '',
        ],
        [
            'show' => $resultReleased,
            'href' => route('site.results.index'),
            'icon' => 'bar-chart-fill',
            'title' => 'Classificação',
            'desc' => 'Resultado e lista de aprovados',
            'badge' => '',
        ],
        [
            'show' => $calls_exists,
            'href' => route('site.calls.index'),
            'icon' => 'bell-fill',
            'title' => 'Chamadas',
            'desc' => 'Convocação para matrícula',
            'badge' => $totalFinalizedCalls ?: null,
            'badge_title' => "{$totalFinalizedCalls} chamada(s) finalizada(s)",
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
                    @if (!empty($link['badge']))
                        <span class="badge bg-secondary"
                            title="{{ $link['badge_title'] ?? '' }}">{{ $link['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>
</section>
