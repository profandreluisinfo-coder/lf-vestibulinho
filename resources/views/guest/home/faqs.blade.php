<section class="faqs">
    <div class="container">
        <h2 class="simple-title">Dúvidas Frequentes</h2>
        <div class="row rounded-3 shadow p-4">
            <div class="col-lg-12 mx-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search"
                        placeholder="Pesquisar por..." autocomplete="off">
                </div>
                <div class="accordion accordion-flush mt-3" id="faqAccordion">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item shadow mb-2">
                            <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $faq->id }}" aria-expanded="false"
                                    aria-controls="collapse{{ $faq->id }}">
                                    {!! $faq->question !!}
                                </button>
                            </h2>
                            <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
