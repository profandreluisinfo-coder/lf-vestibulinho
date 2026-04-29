@if ($calendar->isInscriptionOpen())
<section class="guidelines">
    <div class="container">
        <h3 class="simple-title">Orientações</h3>
        <div class="row g-4">
            <div class="col-lg-12">
                <h5 class="mb-4"><i class="bi bi-info-circle me-2 fs-5"></i>Como participar do {{ config('app.name') }} {{ $calendar?->year }}</h5>

                <div class="row g-3">

                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-person-plus fs-1 text-primary mb-2"></i>
                                <h6 class="card-title">1. Registre-se</h6>
                                <p class="card-text small">
                                    Preencha o
                                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#register">formulário de
                                        registro</a>
                                    informando seu e-mail e criando sua senha de acesso.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-envelope-check fs-1 text-primary mb-2"></i>
                                <h6 class="card-title">2. Confirmar seu e-mail</h6>
                                <p class="card-text small">
                                    Após o cadastro, verifique sua caixa de entrada.
                                    Você receberá um <i>link</i> para confirmar o endereço de e-mail informado.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-patch-check fs-1 text-primary mb-2"></i>
                                <h6 class="card-title">3. Validar o cadastro</h6>
                                <p class="card-text small">
                                    Clique no <i>link</i> recebido no e-mail para validar seu cadastro.
                                    <br>
                                    <strong>Sem essa confirmação não será possível realizar a inscrição.</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-text fs-1 text-primary mb-2"></i>
                                <h6 class="card-title">4. Realizar a inscrição</h6>
                                <p class="card-text small">
                                    Acesse a
                                    <a href="{{ route('login') }}" class="text-decoration-none">Área do
                                        Candidato</a>
                                    e preencha o formulário de inscrição com suas informações pessoais, acadêmicas e
                                    demais dados solicitados.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="alert alert-info mt-4 small">
                    <strong>Atenção:</strong> utilize um endereço de e-mail válido e de fácil acesso.
                    Todas as comunicações oficiais do processo seletivo poderão ser enviadas para o e-mail
                    cadastrado.
                </div>
            </div>
        </div>
    </div>
</section>
@endif