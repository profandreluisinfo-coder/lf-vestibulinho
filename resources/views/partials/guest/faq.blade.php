    <section id="faq">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 reveal">
                    <div class="section-tag">Dúvidas Comuns</div>
                    <h2 class="section-title mb-3">Perguntas <span>Frequentes</span></h2>
                    <p class="section-lead">Selecionamos as dúvidas mais comuns dos candidatos. Não encontrou o que procurava? Acesse a página completa de FAQ.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @foreach ($faqs as $faq)
                        <div class="faq-item reveal delay-1">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                {{ $faq->question }}
                                <div class="faq-icon"><i class="bi bi-plus-lg"></i></div>
                            </div>
                            <div class="faq-answer">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center mt-4 reveal">
                        <a href="{{ route('guest.faqs.index') }}" class="btn-faq-more">
                            Ver todas as perguntas frequentes <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>