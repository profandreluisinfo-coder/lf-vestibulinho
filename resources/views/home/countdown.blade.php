@if ($calendar->isInscriptionOpen())
    <section id="contagem" class="py-3">
        <div class="container">
            <h2 class="section-title text-center">
                <i class="bi bi-clock-history me-2"></i> Contagem Regressiva para o Fim das Inscrições
            </h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="countdown-container" id="countdown" data-deadline="{{ $deadline }}">
                        <div id="countdown-alert" class="countdown-alert d-none">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Últimas horas!</strong> Não perca o prazo para se inscrever!
                        </div>

                        <div id="countdown" class="row g-3 text-center">
                            <div class="col-6 col-md-3">
                                <div class="countdown-item">
                                    <span id="dias" class="countdown-number">00</span>
                                    <span class="countdown-label">Dias</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="countdown-item">
                                    <span id="horas" class="countdown-number">00</span>
                                    <span class="countdown-label">Horas</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="countdown-item">
                                    <span id="minutos" class="countdown-number">00</span>
                                    <span class="countdown-label">Minutos</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="countdown-item">
                                    <span id="segundos" class="countdown-number">00</span>
                                    <span class="countdown-label">Segundos</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="text-muted mb-3">
                                <i class="bi bi-calendar-event me-2"></i>
                                Prazo final: <strong>
                                    {{ \Carbon\Carbon::parse($calendar?->inscription_end)->translatedFormat('d \d\e F \d\e Y') }}
                                    às 23:59</strong>
                            </p>
                            {{-- <a href="{{ route('register') }}" class="btn btn-success btn-lg px-4"
                                title="Inscrever-se Agora">
                                <i class="bi bi-pencil-square me-2"></i> Inscrever-se Agora
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif