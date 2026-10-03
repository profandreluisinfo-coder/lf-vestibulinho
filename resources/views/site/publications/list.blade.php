@extends('layouts.site')

@section('title', $subject . ' ' . $statusLabel)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/site/publications/index.css') }}" />
    {{-- Listas de candidatos não devem aparecer em buscadores --}}
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
    @php
        $total = $inscriptions->count();
    @endphp

    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb-nav" aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-link">Início</a> /
                <a href="{{ route('site.publications.index') }}" class="breadcrumb-link">Publicações</a> /
                <span>{{ $subject }} {{ $statusLabel }}</span>
            </nav>
        </div>
    </section>

    <section class="pub-section">
        <div class="container">
            <div class="text-center mb-4 reveal">
                <div class="section-tag justify-content-center">Publicações</div>
                <h1 class="section-title mb-3">
                    {{ $subject }}
                    <span @unless ($approved) style="color:var(--amber2);" @endunless>
                        {{ $statusLabel }}
                    </span>
                </h1>
                <p class="section-lead mx-auto text-center mb-3">
                    {{ $lead }} no Vestibulinho {{ $process?->year }}.
                </p>
                @if ($released)
                    <span class="pub-count">
                        <i class="bi bi-list-ol"></i> {{ $total }} {{ $total === 1 ? 'inscrição' : 'inscrições' }}
                    </span>
                @endif
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <a href="{{ route('site.publications.index') }}" class="pub-back">
                        <i class="bi bi-arrow-left"></i> Voltar às publicações
                    </a>

                    <div class="pub-panel reveal">
                        @if (! $released)
                            <div class="pub-empty">
                                <i class="bi bi-hourglass-split"></i>
                                <h3>Lista ainda não divulgada</h3>
                                <p>Esta lista será publicada em breve. Volte mais tarde.</p>
                            </div>
                        @elseif ($total > 0)
                            <p class="pub-panel-hint">
                                Os candidatos são identificados pelo número de inscrição.
                                Use a busca do navegador (Ctrl + F) para localizar o seu.
                            </p>

                            <ul class="pub-numbers {{ $approved ? 'is-approved' : 'is-rejected' }}">
                                @foreach ($inscriptions as $inscription)
                                    {{-- AJUSTE: troque id pelo campo/formatação do número de inscrição que o candidato vê --}}
                                    <li>{{ $inscription->id }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="pub-empty">
                                <i class="bi bi-inbox"></i>
                                <h3>Nenhuma inscrição nesta lista</h3>
                                <p>Esta lista ainda não possui inscrições publicadas.</p>
                            </div>
                        @endif
                    </div>

                    <p class="pub-help">
                        Consulte sua situação na <a href="{{ route('login') }}">Área do Candidato</a>.
                        @unless ($approved)
                            Dúvidas sobre o indeferimento? Consulte o
                            @if ($process?->edital)
                                <a href="{{ Storage::url($process->edital) }}" target="_blank"
                                    rel="noopener noreferrer">edital</a>.
                            @else
                                edital.
                            @endif
                        @endunless
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection