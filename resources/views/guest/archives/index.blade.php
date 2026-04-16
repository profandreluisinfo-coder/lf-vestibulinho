@extends('layouts.guest.master')

@push('metas')
    <meta name="description" content="Área de provas anteriores do {{ config('app.name') }}">
@endpush

@section('page-title', config('app.name') . ' | Provas Anteriores')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/exam/public/styles.css') }}">
@endpush

@section('body-class', 'bg-light')

@section('has-footer', 'has-footer')

@section('content')

    @include('guest.home.navbar')

    @php
        $recenteId = $archives->first()?->id;
    @endphp

    <section class="pa-section">
        <div class="container" style="max-width: 860px;">

            <div class="pa-header">
                <div>
                    <h2>Provas Anteriores</h2>
                    <p>Faça o download das provas e gabaritos disponíveis</p>
                </div>
                <div class="pa-count-badge">
                    <strong>{{ $archives->count() }}</strong>
                    {{ Str::plural('prova', $archives->count()) }} disponíve{{ $archives->count() === 1 ? 'l' : 'is' }}
                </div>
            </div>

            @if ($archives->isEmpty())
                <div class="pa-empty">
                    <i class="bi bi-folder2-open fs-2 d-block mb-2 text-muted"></i>
                    Nenhuma prova encontrada.
                </div>
            @else

                <div class="pa-list">

                    @foreach ($archives as $index => $archive)

                        @if ($index === 1)
                            <div class="pa-divider">Edições anteriores</div>
                        @endif

                        <div class="pa-item {{ $archive->id === $recenteId ? 'recent' : '' }}">

                            <div>
                                <div class="pa-year">{{ $archive->year }}</div>
                                @if ($archive->id === $recenteId)
                                    <span class="pa-recent-tag">Recente</span>
                                @endif
                            </div>

                            <div class="pa-info">
                                <strong>Edição {{ $archive->year }}</strong>
                                <span>
                                    @if ($archive->answer?->file)
                                        Prova e gabarito disponíveis
                                    @else
                                        Prova disponível — gabarito não associado
                                    @endif
                                </span>
                            </div>

                            <div class="pa-btn-group">
                                <a class="pa-btn" href="{{ asset('storage/' . $archive->file) }}" target="_blank">
                                    <i class="bi bi-download"></i> Prova
                                </a>

                                @if ($archive->answer?->file)
                                    <a class="pa-btn" href="{{ asset('storage/' . $archive->answer->file) }}" target="_blank">
                                        <i class="bi bi-download"></i> Gabarito
                                    </a>
                                @else
                                    <span class="pa-btn unavailable">
                                        <i class="bi bi-dash-circle"></i> Sem gabarito
                                    </span>
                                @endif
                            </div>

                        </div>

                    @endforeach

                </div>

                <p class="pa-footer-note">(*) Provas sem gabarito associado exibem o aviso "Sem gabarito".</p>

            @endif

        </div>
    </section>

    @include('guest.home.footer')

@endsection