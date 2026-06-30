@extends('layouts.inscription')

@section('page-title', 'Vestibulinho LF | Procedimentos para inscrição')

@section('content')

    @include('inscription.partials.navbar')

    <div class="wrapper">
        <div class="important-info-box mb-5">
            <div class="info-header">
                <i class="bi bi-info-circle-fill"></i> Informações importantes
            </div>
            <div class="info-content">
                <div class="info-item">
                    <span class="fw-bold">Antes de inscrever-se, leia com muito atenção as informações abaixo:</span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Candidatos transgêneros menores de idade que desejarem utilizar o nome social deverão anexar, no ato da inscrição, autorização assinada por seu responsável legal, conforme modelo disponibilizado pela instituição.
                    </span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Os candidatos que se enquadrarem na condição de Pessoa com Deficiência (PCD) e necessitarem de atendimento especializado, adaptações ou recursos de acessibilidade para a realização das etapas do processo seletivo deverão anexar, no ato da inscrição, <strong>laudo</strong> ou <strong>relatório médico</strong> atualizado que comprove a condição declarada, nos termos estabelecidos neste edital.
                    </span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Este processo seletivo é <strong>exclusivo</strong> para moradores de <strong>Sumaré - SP</strong>.
                    </span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        O acompanhamento do processo seletivo é responsabilidade do candidato.
                    </span>
                </div>
            </div>
        </div>
        <div class="section-title mb-4">
            <h4><i class="bi bi-list-check me-2"></i> Como fazer sua inscrição</h4>
            <div class="divider-teal"></div>
        </div>
        <div class="row mb-5 g-4">
            <div class="col-lg-6">
                <div class="registration-step-card h-100">
                    <div class="step-icon">1</div>
                    <div class="step-content">
                        <h5>Leia o Edital</h5>
                        <p>Consulte o edital oficial do processo seletivo antes de iniciar.</p>
                        @if (file_exists(public_path('storage/' . $process?->edital)))
                            <a href="{{ asset('storage/' . $process?->edital) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-file-earmark-pdf me-1"></i> Abrir Edital
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="registration-step-card h-100">
                    <div class="step-icon">2</div>
                    <div class="step-content">
                        <h5>Preencha o Formulário</h5>
                        <p>Complete cuidadosamente todos os campos obrigatórios.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="registration-step-card h-100">
                    <div class="step-icon">3</div>
                    <div class="step-content">
                        <h5>Informe seu CPF</h5>
                        <p>Utilize exclusivamente seu próprio CPF durante a inscrição.</p>
                        <a href="https://www.gov.br/pt-br/servicos/inscrever-no-cpf" target="_blank"
                            class="small text-decoration-none">
                            Não possui CPF?
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="registration-step-card h-100">
                    <div class="step-icon">4</div>
                    <div class="step-content">
                        <h5>Revise os Dados</h5>
                        <p>Confira atentamente todas as informações antes de finalizar. <span class="fw-semibold">Não será possível editar seus dados após finalizar a inscrição</span>.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="registration-step-card final-step">
                    <div class="step-icon">5</div>
                    <div class="step-content">
                        <h5>Guarde sua Ficha</h5>
                        <p class="mb-0">Recomendamos que imprima ou salve sua ficha de inscrição para futuras consultas.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="registration-cta text-center">
            <p class="text-muted mb-4">
                <i class="bi bi-question-circle me-1"></i>
                Em caso de dúvidas, consulte nossos canais oficiais de atendimento.
            </p>
            <a href="{{ route('inscription.step.personal') }}" class="btn btn-primary btn-lg px-5 py-3">
                <i class="bi bi-person-plus me-2"></i> Iniciar Inscrição
            </a>
        </div>
        <div class="registration-footer text-center">
            <strong>EM Dr Leandro Franceschini</strong>
            <span>Prefeitura Municipal de Sumaré</span>
        </div>
    </div>

@endsection