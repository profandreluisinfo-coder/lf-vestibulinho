@extends('layouts.dash.user')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Área do Candidato')

@section('dash-content')

  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">

      @if ($calendar?->isInscriptionOpen())
      <div class="mb-4">
        <h5 class="text-primary mb-3">
          <i class="bi bi-list-check me-2"></i>
          Procedimentos para Inscrição
        </h5>

        <div class="row g-3">
          <div class="col-12">
            <div class="d-flex align-items-start bg-light rounded-3 p-3">
              <div class="badge bg-primary rounded-pill me-3 mt-1"
                style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">1
              </div>
              <div>
                <h6 class="mb-1">Leia o Edital</h6>
                <p class="text-muted small mb-0">
                  Consulte todas as instruções no
                  @if ($notice && $notice->file && file_exists(public_path('storage/' . $notice->file)))
                    <a href="{{ asset('storage/' . $notice->file) }}" target="_blank" class="text-decoration-none">Edital
                      {{ config('app.name') }}
                      {{ config('app.year') }}</a>
                    em nosso site oficial.
                  @else
                    <a href="javascript:void(0);" target="_blank" class="text-decoration-none">Edital
                      {{ config('app.name') }}
                      {{ config('app.year') }}
                    </a>
                    em nosso site oficial.
                  @endif
                </p>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex align-items-start bg-light rounded-3 p-3">
              <div class="badge bg-primary rounded-pill me-3 mt-1"
                style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">2
              </div>
              <div>
                <h6 class="mb-1">Preencha o Formulário</h6>
                <p class="text-muted small mb-0">Complete cuidadosamente todos os campos obrigatórios.</p>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex align-items-start bg-light rounded-3 p-3">
              <div class="badge bg-primary rounded-pill me-3 mt-1"
                style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">3
              </div>
              <div>
                <h6 class="mb-1">Informe seu CPF</h6>
                <p class="text-muted small mb-0">Utilize seu próprio número de CPF. Não possui? Consulte o <a
                    href="https://www.gov.br/pt-br/servicos/inscrever-no-cpf" target="_blank"
                    class="text-decoration-none">site da Receita
                    Federal</a>. Nunca utilize um CPF de terceiros.</p>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex align-items-start bg-light rounded-3 p-3">
              <div class="badge bg-primary rounded-pill me-3 mt-1"
                style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">4
              </div>
              <div>
                <h6 class="mb-1">Revise seus Dados</h6>
                <p class="text-muted small mb-0">Confira se todas as informações estão corretas - elas serão usadas
                  em todo o processo seletivo e não poderão ser alteradas posteriormente.</p>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex align-items-start bg-light rounded-3 p-3">
              <div class="badge bg-primary rounded-pill me-3 mt-1"
                style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">5
              </div>
              <div>
                <h6 class="mb-1">Guarde sua Ficha</h6>
                <p class="text-muted small mb-0">Imprima e guarde uma cópia da sua ficha de inscrição para
                  apresentar no dia da prova, caso seja necessário.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="alert alert-warning mb-4 border-0" style="background-color: #fff8e1;">
        <div class="d-flex align-items-start">
          <div>
            <h6 class="mb-2">Informações Importantes</h6>
            <ul class="small mb-0">
              <li class="mb-1"><strong>Nome Social:</strong> Candidatos transgêneros podem informar o nome
                social/afetivo no campo específico durante a inscrição desde que tenha autorização dos pais ou
                responsáveis.</li>
              <li class="mb-1">Este Processo Seletivo é <strong>exclusivo</strong> para candidatos residentes no
                Município de Sumaré - SP.</li>
              <li>É de inteira responsabilidade do candidato e de seu responsável legal <strong>acompanhar</strong>
                o andamento do processo seletivo através do site oficial da EM Dr. Leandro Franceschini e da Área do
                Candidato.</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="mb-4 text-center">
        <p class="text-muted mb-2">
          <i class="bi bi-question-circle me-1"></i>
          Dúvidas? Entre em contato através dos nossos canais de atendimento.
        </p>
        <p class="text-muted small mb-0">
          É uma honra contar com seu interesse no <strong>{{ config('app.name') }}
            {{ config('app.year') }}</strong>!
        </p>
      </div>

      <div class="text-center">
        <a href="{{ route('step.personal') }}" class="btn btn-primary btn-lg px-5 py-3 shadow-sm">
          <i class="bi bi-person-plus me-2"></i>
          Iniciar Inscrição
        </a>
      </div>

      @else

      <div class="alert alert-danger">
          <p class="fw-bold">Prezado(a) Candidato(a),</p>
          <p>
          <i class="bi bi-exclamation-circle me-1"></i>
          O período de inscrições para o {{ config('app.name') }} {{ config('app.year') }} foi <u>encerrado</u> em <strong>{{ $calendar?->inscription_end->format('d/m/Y') }}</strong>.
          </p>
      </div>

      @endif
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-12 text-center">
      <p class="text-muted small mb-0">
        <strong>E. M. Dr. Leandro Franceschini</strong><br>
        Prefeitura Municipal de Sumaré
      </p>
    </div>
  </div>

@endsection
