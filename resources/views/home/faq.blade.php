<section id="faq" class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center">Dúvidas Frequentes</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
            @if ($faqs->isNotEmpty())
                <div class="accordion accordion-flush" id="faqAccordion">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false"
                                    aria-controls="collapse{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{ route('questions') }}" class="btn btn-sm btn-primary">Ver todas as perguntas</a>
                </div>
            @endif
            </div>
        </div>
    </div>
</section>