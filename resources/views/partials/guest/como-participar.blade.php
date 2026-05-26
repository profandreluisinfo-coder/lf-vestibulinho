    <section id="como-participar">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 reveal">
                    <div class="section-tag">Passo a Passo</div>
                    <h2 class="section-title mb-3">Como <span>Participar</span><br>do Vestibulinho</h2>
                    <p class="section-lead">
                        O processo é simples, rápido e totalmente gratuito. Siga as etapas abaixo e garanta sua vaga.
                    </p>
                </div>
                <div class="col-lg-6 reveal delay-2 text-lg-end">
                    @if ($open)
                        <a href="{{ route('login') }}" class="btn-faq-more">
                            <i class="bi bi-pencil-fill"></i> Iniciar Inscrição
                        </a>
                    @endif
                </div>
            </div>

            <div class="timeline-wrap">
                <div class="tl-item reveal-left">
                    <div class="tl-node">1</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-search me-2 text-teal"></i>Leia o Edital</h4>
                        <p>Acesse o edital completo na seção de <a href="#links-rapidos" class="text-decoration-none text-teal">documentos</a>. Leia todas as regras, requisitos de inscrição, datas e critérios de avaliação.</p>
                    </div>
                </div>
                <div class="tl-item reveal-right">
                    <div class="tl-node amber-node">2</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-person-fill me-2 text-amber"></i>Registre-se</h4>
                        <p>Acesse o <a href="{{ route('register') }}" class="text-amber">formulário de registro</a>, informe seu e-mail e crie uma senha de acesso. Você receberá um e-mail de confirmação. Clique no <i>link</i> recebido no e-mail para validar seu cadastro. <strong class="text-danger">Sem essa confirmação não será possível realizar a inscrição.</strong></p>
                    </div>
                </div>
                <div class="tl-item reveal-left">
                    <div class="tl-node">3</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-clipboard-fill me-2 text-teal"></i>Faça sua Inscrição</h4>
                        <p>Após confirmar seu e-mail, acesse a <a href="{{ route('login') }}" class="text-decoration-none text-teal">Área do Candidato</a>, preencha o formulário de inscrição com suas informações pessoais, acadêmicas e demais dados solicitados. Confirme os dados e guarde o número de inscrição gerado.</p>
                    </div>
                </div>
                <div class="tl-item reveal-right">
                    <div class="tl-node amber-node">4</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-book-fill me-2 text-amber"></i>Estude e Prepare-se</h4>
                        <p>Acesse as provas anteriores disponíveis aqui no site para se preparar.</p>
                    </div>
                </div>
                <div class="tl-item reveal-left">
                    <div class="tl-node">5</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-pen-fill me-2 text-teal"></i>Realize a Prova</h4>
                        <p>Compareça no dia, horário e local indicados no cartão de confirmação. Leve documento com foto original.</p>
                    </div>
                </div>
                <div class="tl-item reveal-right">
                    <div class="tl-node amber-node">6</div>
                    <div class="tl-content">
                        <h4><i class="bi bi-trophy-fill me-2 text-amber"></i>Acompanhe o Resultado</h4>
                        <p>Acesse a classificação e a convocação para matrícula aqui no site. Se convocado, compareça no prazo indicado com os documentos exigidos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>