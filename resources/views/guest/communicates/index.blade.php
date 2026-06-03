@extends('layouts.guest')

@section('title', 'Comunicados — Vestibulinho ' . ($calendar?->year ?? config('app.year')))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guest/home/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/guest/communicates/index.css') }}" />
@endpush

@section('content')
    <section class="section-communicates py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 reveal">
                    <div class="fa-header-accent">
                        <i class="bi bi-megaphone-fill"></i>
                        Comunicados
                    </div>

                    <h2 class="section-title">Todos os comunicados</h2>

                    <p class="section-lead mt-2">
                        Confira todos os comunicados publicados sobre o Vestibulinho.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 reveal">

                    @if ($comunicados->isEmpty())
                        <div class="fa-empty">
                            <i class="bi bi-inbox"></i>
                            Nenhum comunicado publicado no momento.
                        </div>
                    @else
                        <div class="comunicado-list">
                            @foreach ($comunicados as $item)
                                @php
                                    $iconeMap = [
                                        'info'         => 'bi-info-circle-fill',
                                        'alerta'       => 'bi-exclamation-triangle-fill',
                                        'urgente'      => 'bi-exclamation-octagon-fill',
                                        'importante'   => 'bi-bookmark-star-fill',
                                        'prazo'        => 'bi-clock-fill',
                                        'edital'       => 'bi-file-earmark-text-fill',
                                        'resultado'    => 'bi-trophy-fill',
                                        'aprovacao'    => 'bi-patch-check-fill',
                                        'inscricao'    => 'bi-pencil-square',
                                        'documento'    => 'bi-folder-fill',
                                        'calendario'   => 'bi-calendar-event-fill',
                                        'prova'        => 'bi-journal-check',
                                        'convocacao'   => 'bi-person-lines-fill',
                                        'cancelamento' => 'bi-x-octagon-fill',
                                        'manutencao'   => 'bi-tools',
                                        'sistema'      => 'bi-cpu-fill',
                                        'novidade'     => 'bi-stars',
                                        'sucesso'      => 'bi-check-circle-fill',
                                        'erro'         => 'bi-bug-fill',
                                        'financeiro'   => 'bi-cash-stack',
                                        'local'        => 'bi-geo-alt-fill',
                                    ];
                                    $icone = $iconeMap[$item->tipo] ?? 'bi-megaphone-fill';

                                    $labelMap = [
                                        'info'         => 'Informativo',
                                        'alerta'       => 'Atenção',
                                        'urgente'      => 'Urgente',
                                        'importante'   => 'Importante',
                                        'prazo'        => 'Prazo',
                                        'edital'       => 'Edital',
                                        'resultado'    => 'Resultado',
                                        'aprovacao'    => 'Aprovação',
                                        'inscricao'    => 'Inscrições',
                                        'documento'    => 'Documentação',
                                        'calendario'   => 'Calendário',
                                        'prova'        => 'Prova',
                                        'convocacao'   => 'Convocação',
                                        'cancelamento' => 'Cancelamento',
                                        'manutencao'   => 'Manutenção',
                                        'sistema'      => 'Sistema',
                                        'novidade'     => 'Novidade',
                                        'sucesso'      => 'Concluído',
                                        'erro'         => 'Erro',
                                        'financeiro'   => 'Financeiro',
                                        'local'        => 'Local de Prova',
                                    ];
                                    $label = $labelMap[$item->tipo] ?? 'Aviso';
                                @endphp

                                <a href="{{ route('guest.communicates.show', $item) }}"
                                   class="comunicado-item delay-{{ ($loop->index % 4) + 1 }}">

                                <div class="comunicado-icon tipo-{{ $item->tipo ?? 'info' }}">
                                    <i class="bi {{ $icone }}"></i>
                                </div>

                                <div class="comunicado-body">
                                    <div class="comunicado-titulo">{{ $item->titulo }}</div>

                                    @if (!empty($item->resumo))
                                        <p class="comunicado-resumo">{!! $item->resumo !!}</p>
                                    @endif

                                    <div class="comunicado-meta">
                                        <span class="comunicado-data">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $item->published_at?->format('d/m/Y') ?? $item->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="comunicado-badge badge-{{ $item->tipo ?? 'info' }}">
                                            {{ $label }}
                                        </span>
                                    </div>
                                </div>

                                <i class="bi bi-arrow-right comunicado-arrow"></i>

                                </a>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-center">
                            {{ $comunicados->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection