@extends('layouts.dash.admin')

@section('page-title', 'Erro na Inscrição')

@section('dash-content')

  <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">

      <!-- Error Card -->
      <div class="card border-0 shadow-sm">
        <div class="card-body p-5 text-center">

          <!-- Error Icon -->
          <div class="mb-4">
            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center mx-auto bg-opacity-10"
              style="width: 80px; height: 80px;">
              <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2.5rem;"></i>
            </div>
          </div>

          <!-- Error Title -->
          <h3 class="text-danger mb-3">Ops! Algo deu errado</h3>

          <!-- Error Message -->
          <div class="mb-4">
            @if (session('error'))
              <div class="alert alert-danger border-0 shadow-sm">
                <div class="d-flex align-items-start">
                  <i class="bi bi-info-circle text-danger me-2 mt-1"></i>
                  <div class="text-start">
                    <strong>Detalhes do erro:</strong><br>
                    {{ session('error') }}
                  </div>
                </div>
              </div>
            @endif

            @if (session('danger'))
              <div class="alert alert-danger border-0 shadow-sm">
                <div class="d-flex align-items-start">
                  <i class="bi bi-info-circle text-danger me-2 mt-1"></i>
                  <div class="text-start">
                    <strong>Problema identificado:</strong><br>
                    {{ session('danger') }}
                  </div>
                </div>
              </div>
            @endif

            @if (session('status'))
              <div class="alert alert-{{ session('status.alert-type', 'danger') }} border-0 shadow-sm">
                <div class="d-flex align-items-start">
                  <i class="bi bi-info-circle me-2 mt-1"></i>
                  <div class="text-start">
                    {{ session('status.message') }}
                  </div>
                </div>
              </div>
            @endif

            @if (!session('error') && !session('danger') && !session('status'))
              <div class="alert alert-warning border-0 shadow-sm">
                <div class="d-flex align-items-start">
                  <i class="bi bi-exclamation-triangle text-warning me-2 mt-1"></i>
                  <div class="text-start">
                    <strong>Erro não identificado</strong><br>
                    Ocorreu um problema inesperado durante o processamento da sua inscrição.
                  </div>
                </div>
              </div>
            @endif
          </div>

          <!-- Help Section -->
          <div class="bg-light rounded-3 mb-4 p-4">
            <h6 class="text-primary mb-3">
              <i class="bi bi-lightbulb me-2"></i>
              O que fazer agora?
            </h6>
            <div class="row g-3 text-start">
              <div class="col-12">
                <div class="d-flex align-items-start">
                  <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                  <small class="text-muted">Verifique se todos os campos foram preenchidos corretamente</small>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex align-items-start">
                  <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                  <small class="text-muted">Certifique-se de que os dados não excedem o limite de caracteres</small>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex align-items-start">
                  <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                  <small class="text-muted">Tente novamente em alguns minutos</small>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex align-items-start">
                  <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                  <small class="text-muted">Se o problema persistir, entre em contato conosco</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="d-grid d-md-flex justify-content-md-center gap-2">
            <a href="{{ route('dashboard.index') }}" class="btn btn-primary px-4">
              <i class="bi bi-arrow-left me-2"></i>
              Voltar ao Início
            </a>
            <button type="button" class="btn btn-outline-secondary px-4" onclick="window.history.back()">
              <i class="bi bi-arrow-counterclockwise me-2"></i>
              Tentar Novamente
            </button>
          </div>

        </div>
      </div>

      <!-- Contact Support -->
      <div class="card mt-4 border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h6 class="mb-1">Precisa de ajuda?</h6>
              <p class="text-muted small mb-md-0">
                Nossa equipe está pronta para ajudá-lo com qualquer dificuldade.
              </p>
            </div>
            <div class="col-md-4 text-md-end">
              <a href="https://leandrofranceschini.com.br/#form-contato" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="bi bi-headset me-1"></i>
                Contatar Suporte
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Info -->
      <div class="mt-4 text-center">
        <p class="text-muted small mb-0">
          <i class="bi bi-shield-check me-1"></i>
          Seus dados estão seguros. Este erro não afetou suas informações pessoais.
        </p>
      </div>

    </div>
  </div>

@endsection