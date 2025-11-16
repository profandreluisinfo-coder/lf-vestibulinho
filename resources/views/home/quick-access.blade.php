@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/quick-access/styles.css') }}">
@endpush

<section id="acesso-rapido" class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center">Acesso Rápido</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center text-center">
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm {{ !$notice->status ? 'card-inactive' : '' }}">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-file-text quick-access-icon fs-1 text-{{ $notice->status ? 'success' : 'primary' }}"></i>
                        <h5 class="card-title mt-3">Edital</h5>
                        <p class="card-text">Baixe o edital completo</p>
                        <div class="mt-auto">
                        @if ($notice->status)
                            <a href="{{ asset('storage/' . $notice->file) }}" class="btn btn-sm btn-success" title="Leia o edital na íntegra" target="_blank">Ler Edital</a>
                        @else
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="Baixe o edital e manual completo">Ler Edital</a>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($calendar?->isInscriptionOpen())
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-person-plus quick-access-icon fs-1 text-success"></i>
                        <h5 class="card-title mt-3">Fazer Inscrição</h5>
                        <p class="card-text">Inscreva-se gratuitamente</p>
                        <div class="mt-auto">
                            <a href="{{ route('register') }}" class="btn btn-sm btn-success">Inscreva-se</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm {{ !$calendar->hasInscriptionStarted() ? 'card-inactive' : '' }}">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-person quick-access-icon fs-1 text-{{ $calendar->hasInscriptionStarted() ? 'success' : 'secondary' }}"></i>
                        <h5 class="card-title mt-3">Área do Candidato</h5>
                        <p class="card-text">Verifique o status da sua inscrição</p>
                        <div class="mt-auto">
                        @if ($calendar->hasInscriptionStarted())
                            <a href="{{ route('login') }}" class="btn btn-sm btn-success">Acessar</a>
                        @else
                            <a href="javascript:void(0);" class="btn btn-sm btn-secondary" disabled>Em Breve</a>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm {{ !$settings->result ? 'card-inactive' : '' }}">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-trophy quick-access-icon fs-1 text-{{ $settings->result ? 'success' : 'secondary' }}"></i>
                        <h5 class="card-title mt-3">Resultados</h5>
                        <p class="card-text">Confira os resultados publicados</p>
                        <div class="mt-auto">
                          @if ($settings->result)
                            <a href="{{ route('results') }}" class="btn btn-sm btn-success" title="Confira os resultados publicados">Visualizar</a>
                          @else
                            <a href="javascript:void(0);" class="btn btn-sm btn-secondary" disabled title="Resultados ainda não disponíveis">Em Breve</a>
                          @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card quick-access-card h-100 d-flex flex-column border-0 shadow-sm {{ !$calls ? 'card-inactive' : '' }}">
                    <div class="card-body d-flex flex-column">
                        <i class="bi bi-megaphone quick-access-icon fs-1 text-{{ $calls ? 'success' : 'secondary' }}"></i>
                        <h5 class="card-title mt-3">Matrículas</h5>
                        <p class="card-text">Acompanhe as convocações para realização das matrículas</p>
                        <div class="mt-auto">
                          @if ($calls)
                            <a href="{{ route('calls') }}" class="btn btn-sm btn-success" title="Acompanhe as convocações para realização das matrículas">Convocações</a>
                          @else
                            <a href="javascript:void(0);" class="btn btn-sm btn-secondary" disabled title="Convocações ainda não disponíveis">Em Breve</a>
                          @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script src="{{ asset('assets/js/quick-access.js') }}"></script>
@endpush