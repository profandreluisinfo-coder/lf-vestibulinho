@extends('dash.master')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Área do Candidato')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/user/inscription.css') }}">
@endpush

@section('dash-content')

    <div class="mb-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold">
                <i class="bi bi-list-check text-primary me-2"></i>
                Como fazer sua inscrição
            </h5>

            <span class="badge bg-danger"><i class="bi bi-exclamation-diamond me-2"></i>Você ainda não possui uma inscrição</span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="step-card bg-white border rounded-3 p-3">
                    <div class="d-flex align-items-center gap-3">
                        <span
                            class="step-number badge bg-primary rounded-circle d-flex align-items-center justify-content-center">1</span>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">Leia o Edital</h6>
                            <p class="text-muted small mb-0">
                                Consulte o
                                @if ($notice && $notice->file && file_exists(public_path('storage/' . $notice->file)))
                                    <a href="{{ asset('storage/' . $notice->file) }}" target="_blank"
                                        class="text-decoration-none fw-medium">
                                        Edital {{ config('app.name') }} {{ $calendar->year }}
                                    </a>
                                @else
                                    <a href="javascript:void(0);" class="text-decoration-none fw-medium">
                                        Edital {{ config('app.name') }} {{ $calendar->year }}
                                    </a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="step-card bg-white border rounded-3 p-3">
                    <div class="d-flex align-items-center gap-3">
                        <span
                            class="step-number badge bg-primary rounded-circle d-flex align-items-center justify-content-center">2</span>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">Preencha o Formulário de Inscrição</h6>
                            <p class="text-muted small mb-0">Complete todos os campos obrigatórios</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="step-card bg-white border rounded-3 p-3">
                    <div class="d-flex align-items-center gap-3">
                        <span
                            class="step-number badge bg-primary rounded-circle d-flex align-items-center justify-content-center">3</span>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">Informe seu CPF</h6>
                            <p class="text-muted small mb-0">
                                Use seu próprio CPF. <b>NUNCA</b> use o CPF de outra pessoa. <a href="https://www.gov.br/pt-br/servicos/inscrever-no-cpf"
                                    target="_blank" class="text-decoration-none">Não possui CPF?</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="step-card bg-white border rounded-3 p-3">
                    <div class="d-flex align-items-center gap-3">
                        <span
                            class="step-number badge bg-primary rounded-circle d-flex align-items-center justify-content-center">4</span>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">Revise seus Dados</h6>
                            <p class="text-muted small mb-0">Confira todas as informações antes de finalizar</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="step-card bg-white border rounded-3 p-3">
                    <div class="d-flex align-items-center gap-3">
                        <span
                            class="step-number badge bg-primary rounded-circle d-flex align-items-center justify-content-center">5</span>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">Guarde sua Ficha</h6>
                            <p class="text-muted small mb-0">Imprima sua ficha de inscrição para apresentar no dia da prova, caso necessário</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="info-box bg-white border rounded-3 p-3 mb-4">
        <h6 class="fw-semibold mb-3">
            <i class="bi bi-info-circle text-warning me-1"></i>
            Informações Importantes
        </h6>
        <div class="small">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div><i class="bi bi-check2-circle me-2"></i>Candidatos transgêneros podem informar o nome social/afetivo no campo específico durante a inscrição desde que tenham autorização dos pais ou responsáveis legais e enviem o comprovante de autorização preenchido e assinado pelo responsável legal.</div></li>
                <li class="list-group-item"><div><i class="bi bi-check2-circle me-2"></i>Este Processo Seletivo é <strong>exclusivo</strong> para candidatos residentes no Município de Sumaré - SP.</div></li>
                <li class="list-group-item"><div><i class="bi bi-check2-circle me-2"></i>É de inteira responsabilidade do candidato e de seu responsável legal acompanhar o andamento do processo seletivo através do site oficial da EM Dr. Leandro Franceschini e da Área do Candidato.</div></li>
            </ul>
        </div>
    </div>

    <div class="text-center mb-4">
        <p class="text-muted mb-3">
            <i class="bi bi-question-circle me-1"></i>
            Dúvidas? Entre em contato através dos nossos canais de atendimento.
        </p>

        <a href="{{ route('step.personal') }}" class="btn btn-primary btn-lg px-5 shadow-sm">
            <i class="bi bi-person-plus me-2"></i>
            Iniciar Inscrição
        </a>
    </div>

    <div class="text-center mt-4 pt-3 border-top">
        <p class="text-muted small mb-0">
            <strong>E. M. Dr. Leandro Franceschini</strong><br>
            Prefeitura Municipal de Sumaré
        </p>
    </div>

@endsection