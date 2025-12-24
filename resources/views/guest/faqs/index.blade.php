@extends('layouts.home.master')

@push('metas')
    <meta name="description" content="Área de perguntas frequentes sobre o {{ config('app.name') }} {{ $calendar?->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Perguntas Frequentes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/faqs/styles.css') }}">
@endpush

@section('body-class', 'bg-light')

@section('content')

    @include('guest.home.navbar')

    <section id="faqs" class="bg-light py-5">
        <div class="container">
            <h2 class="section-title text-center">Dúvidas Frequentes</h2>
            <div class="row mb-3">
                <div class="col-lg-8 mx-auto">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Pesquisar por..." autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        @foreach ($faqs as $faq)
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                    <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $faq->id }}"
                                        aria-expanded="false"
                                        aria-controls="collapse{{ $faq->id }}">
                                        {!! $faq->question !!}
                                    </button>
                                </h2>

                                <div id="collapse{{ $faq->id }}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $faq->id }}"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {!! $faq->answer !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto text-center pt-3">
                    <div class="text-muted mb-3">Não encontrou a resposta que procurava?</div> <a class="btn btn-sm btn-primary js-confirm-external"
                    href="https://leandrofranceschini.com.br/#form-contato"
                    title="Entre em contato conosco"
                    target="_blank">
                    <i class="bi bi-telephone me-2"></i> Contato
                </a>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('assets/filters/faqs.js') }}"></script>
    <script src="{{ asset('assets/interactions/infinite-scroll.js') }}"></script>
@endpush
