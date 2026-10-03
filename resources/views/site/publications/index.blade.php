@extends('layouts.site')

@section('title', 'Publicações')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/publications/index.css') }}" />
@endpush

@section('content')
    @php
        // Listas liberadas pelo administrador (Admin → Publicações)
        $released = \App\Models\Setting::releasedPublications();

        /*
         * Para criar um card novo, basta incluir um item em 'cards' (ou um grupo novo).
         *  - status: 'approved' (deferido, cor teal) ou 'rejected' (indeferido, cor âmbar)
         *  - route: nome da rota da página específica. Enquanto a rota não existir
         *           ou a lista não for liberada no admin, o card aparece como "Em breve"
         *           e não leva a lugar nenhum.
         */
        $groups = [
            [
                'title' => 'Inscrições',
                'icon' => 'person-lines-fill',
                'cards' => [
                    [
                        'status' => 'approved',
                        'title' => 'Inscrições deferidas',
                        'desc' => 'Candidatos com inscrição aceita',
                        'route' => 'site.publications.inscriptions.approved',
                    ],
                    [
                        'status' => 'rejected',
                        'title' => 'Inscrições indeferidas',
                        'desc' => 'Inscrições não aceitas',
                        'route' => 'site.publications.inscriptions.rejected',
                    ],
                ],
            ],
            [
                'title' => 'Nome social',
                'icon' => 'person-vcard',
                'cards' => [
                    [
                        'status' => 'approved',
                        'title' => 'Nome social deferidos',
                        'desc' => 'Pedidos de uso de nome social aceitos',
                        'route' => 'site.publications.social-names.approved',
                    ],
                    [
                        'status' => 'rejected',
                        'title' => 'Nome social indeferidos',
                        'desc' => 'Pedidos de uso de nome social não aceitos',
                        'route' => 'site.publications.social-names.rejected',
                    ],
                ],
            ],
            [
                'title' => 'Laudos e relatórios médicos',
                'icon' => 'file-earmark-medical',
                'cards' => [
                    [
                        'status' => 'approved',
                        'title' => 'Laudos e relatórios deferidos',
                        'desc' => 'Documentos médicos aceitos',
                        'route' => 'site.publications.medical-reports.approved',
                    ],
                    [
                        'status' => 'rejected',
                        'title' => 'Laudos e relatórios indeferidos',
                        'desc' => 'Documentos médicos não aceitos',
                        'route' => 'site.publications.medical-reports.rejected',
                    ],
                ],
            ],
        ];
    @endphp

    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb-nav" aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">Início</a> / <span>Publicações</span>
            </nav>
        </div>
    </section>

    <section class="pub-section">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <div class="section-tag justify-content-center">Publicações</div>
                <h1 class="section-title mb-3">Listas do <span>Vestibulinho {{ $process?->year }}</span></h1>
                <p class="section-lead mx-auto text-center">Escolha o tipo de lista que você quer consultar.</p>
            </div>

            @foreach ($groups as $group)
                <div class="pub-group reveal">
                    <h2 class="pub-group-title">
                        <i class="bi bi-{{ $group['icon'] }}"></i> {{ $group['title'] }}
                    </h2>

                    <div class="pub-grid">
                        @foreach ($group['cards'] as $card)
                            @php
                                $key = \Illuminate\Support\Str::after($card['route'], 'site.publications.');
                                $href = Route::has($card['route']) && in_array($key, $released, true)
                                    ? route($card['route'])
                                    : null;
                                $isApproved = $card['status'] === 'approved';
                            @endphp

                            <a @if ($href) href="{{ $href }}" @else aria-disabled="true" @endif
                                class="pub-card {{ $href ? '' : 'is-soon' }}">
                                <span class="pub-icon {{ $isApproved ? 'pub-icon-approved' : 'pub-icon-rejected' }}">
                                    <i class="bi bi-{{ $isApproved ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
                                </span>
                                <span class="pub-title">{{ $card['title'] }}</span>
                                <span class="pub-desc">{{ $card['desc'] }}</span>
                                <span class="pub-go">
                                    @if ($href)
                                        Ver lista <i class="bi bi-arrow-right"></i>
                                    @else
                                        Em breve
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection