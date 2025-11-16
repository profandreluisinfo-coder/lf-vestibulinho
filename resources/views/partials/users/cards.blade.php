<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mt-5 mt-md-0 mx-auto">
  
  <!-- Card: Inscrição -->
  <div class="col">
    <div class="card h-100 border-0 shadow-sm hover-card text-center p-3">
      <i class="bi bi-file-earmark-text card-icon mb-3"></i>
      <h5 class="card-title fw-semibold mb-2">Inscrição</h5>

      @if (auth()->user()->inscription()->exists())
        <p class="card-text mb-3">
          Status: <span class="badge bg-success">Completa</span>
        </p>
        <form action="{{ route('user.details') }}" method="post">
          @csrf
          <button type="submit" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-search"></i> Ver detalhes
          </button>
        </form>
      @endif
    </div>
  </div>

  <!-- Card: Prova -->
  <div class="col">
    <div class="card h-100 border-0 shadow-sm hover-card text-center p-3">
      <i class="bi bi-card-checklist card-icon mb-3"></i>
      <h5 class="card-title fw-semibold mb-2">Prova</h5>
      <p class="card-text mb-3">
        Local:
        <span class="badge {{ $configs->location ? 'bg-success' : 'bg-info' }}">
          {{ $configs->location ? 'Confirmado' : 'A definir' }}
        </span>
      </p>
      <a href="{{ $configs->location ? route('user.location') : 'javascript:void(0)' }}" 
          class="btn btn-outline-primary btn-sm">
        <i class="bi bi-search"></i> Cartão
      </a>
    </div>
  </div>

  <!-- Card: Resultados -->
  <div class="col">
    <div class="card h-100 border-0 shadow-sm hover-card text-center p-3">
      <i class="bi bi-list-ol card-icon mb-3"></i>
      <h5 class="card-title fw-semibold mb-2">Resultados</h5>
      <p class="card-text mb-3">
        Status:
        <span class="badge {{ $configs->result ? 'bg-success' : 'bg-info' }}">
          {{ $configs->result ? 'Confirmado' : 'A definir' }}
        </span>
      </p>
      <a href="{{ $configs->result ? route('user.results') : 'javascript:void(0)' }}"
          class="btn btn-outline-primary btn-sm">
        <i class="bi bi-search"></i> Visualizar
      </a>
    </div>
  </div>

  <!-- Card: Chamadas -->
  <div class="col">
    <div class="card h-100 border-0 shadow-sm hover-card text-center p-3">
      <i class="bi bi-file-pdf card-icon mb-3"></i>
      <h5 class="card-title fw-semibold mb-2">Chamadas</h5>
      <p class="card-text mb-3">
        Status:
        <span class="badge {{ auth()->user()->hasConfirmedCall() ? 'bg-success' : 'bg-info' }}">
          {{ auth()->user()->hasConfirmedCall() ? 'Convocado' : 'A definir' }}
        </span>
      </p>
      <a href="{{ auth()->user()->hasConfirmedCall() ? route('user.call') : 'javascript:void(0)' }}"
          class="btn btn-outline-primary btn-sm">
        <i class="bi bi-search"></i> Acompanhar
      </a>
    </div>
  </div>

</div>
