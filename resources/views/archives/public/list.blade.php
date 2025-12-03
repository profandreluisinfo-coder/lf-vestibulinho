@extends('layouts.home.master')

@push('metas')
    <meta name="description" content="Área de provas anteriores do {{ config('app.name') }}">
@endpush

@section('page-title', config('app.name') . ' | Provas Anteriores')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/archives/styles.css') }}">
@endpush

@section('body-class', 'bg-light')

@section('content')

    @include('home.navbar')

    <section id="provas-anteriores" class="bg-light my-5 py-5">

        <div class="container">

            <h2 class="section-title mb-4 text-center">Provas Anteriores</h2>

            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-4 justify-content-center">

                @if ($files->isNotEmpty())
                    @foreach ($files as $file)
                        <div class="col">
                            <div class="card h-100 card-animada shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-pdf text-danger mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="card-title">Vestibulinho {{ $file->year }}</h5>
                                    <p class="card-text">Prova completa para download</p>
                                    <a href="{{ asset('storage/' . $file->file) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download me-2"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info text-center">
                        Nenhum resultado foi encontrado.
                    </div>
                @endif

            </div>

        </div>

    </section>

    @include('home.footer')

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/archives.js') }}"></script>
@endpush