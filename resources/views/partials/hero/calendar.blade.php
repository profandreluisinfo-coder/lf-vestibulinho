    <section class="cal-hero">
        <div class="hero-circle hero-circle-1"></div>
        <div class="hero-circle hero-circle-2"></div>
        <div class="hero-circle hero-circle-3"></div>
        
        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb cal-breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Calendário</li>
                        </ol>
                    </nav>
                    <div class="hero-badge mb-3" style="animation:fadeDown .8s ease both;">
                        <span class="live-dot"></span>
                        Datas Importantes · Processo Seletivo
                    </div>
                    <h1 class="cal-hero-title mb-3">
                        Calendário do<br><em>Processo Seletivo</em><br>
                        <span class="year-chip">{{ $calendar?->reference }}</span>
                    </h1>
                    <p class="hero-sub mb-0">
                        Todas as datas e prazos do Vestibulinho em um único lugar.<br class="d-none d-md-block">
                        Salve as datas e não perca nenhum prazo.
                    </p>
                </div>
                <div class="col-lg-5">
                    @if($calendar?->isInscriptionOpen())
                        <div class="status-card status-open">
                            <div class="status-icon"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições Abertas</div>
                                <div class="status-detail">
                                    Encerram em <strong>{{ $calendar->formatDate($calendar->inscription_end) }}</strong>
                                </div>
                            </div>
                        </div>
                    @elseif($calendar?->hasInscriptionEnded())
                        <div class="status-card status-closed">
                            <div class="status-icon"><i class="bi bi-x-circle-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições Encerradas</div>
                                <div class="status-detail">O período de inscrições foi concluído.</div>
                            </div>
                        </div>
                    @elseif($calendar?->hasInscriptionStarted() === false && $calendar?->inscription_start)
                        <div class="status-card status-soon">
                            <div class="status-icon"><i class="bi bi-clock-fill"></i></div>
                            <div>
                                <div class="status-label">Inscrições em Breve</div>
                                <div class="status-detail">
                                    Abertura em <strong>{{ $calendar->formatDate($calendar->inscription_start) }}</strong>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="status-card status-pending">
                            <div class="status-icon"><i class="bi bi-calendar3"></i></div>
                            <div>
                                <div class="status-label">Calendário</div>
                                <div class="status-detail">Confira todas as datas abaixo.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>  