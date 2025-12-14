<section class="hero-banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="hero-content">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
                        <img src="{{ asset('assets/img/logo.webp') }}" class="img-fluid" alt="Avatar Logo" width="75">

                        <h1 class="display-4 fw-bold mb-3 mb-md-0">
                            Vestibulinho LF {{ $calendar->year ?? '' }}
                        </h1>
                    </div>
                    <h2 class="h3 mb-4">
                        <div>Totalmente Gratuito!</div>
                        @if ($calendar)
                            @if (!$calendar?->hasInscriptionStarted())
                                <div>Em breve.</div>
                            @elseif ($calendar?->isInscriptionOpen())
                                <div class="text-success-alt">Inscrições Abertas</div>
                            @else
                                <div class="text-danger-alt">Inscrições Encerradas</div>
                            @endif
                        @endif
                    </h2>
                    <p class="lead mb-4">
                        Garanta sua vaga em um dos nossos cursos técnicos de qualidade.
                        Processo seletivo sem taxa de inscrição com vagas para 4 cursos diferentes.
                    </p>
                    <div class="d-flex hero-buttons">
                        @if ($calendar->isInscriptionOpen())
                            <a href="{{ route('register') }}" class="btn btn-success btn-lg" title="Inscrever-se Agora">
                                <i class="bi bi-pencil-square me-2"></i>Inscrever-se Agora
                            </a>
                        @endif
                        <a href="#courses" class="btn btn-outline-light btn-lg" title="Conhecer Cursos">
                            <i class="bi bi-book me-2"></i>Conhecer Cursos
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @if ($calendar?->isInscriptionOpen())
                    <div class="highlight-box">
                        <h3 class="h4 mb-3">
                            <i class="bi bi-calendar-check me-2"></i>Prazo Final
                        </h3>
                        <h4 class="fw-bold">
                            {{ $calendar?->inscription_end?->translatedFormat('d \d\e F') ?? 'Aguardando Informações' }}
                        </h4>
                        <p class="mb-0">Não perca esta oportunidade!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>