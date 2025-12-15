@php
    $status_edital  = $notice->status;
    $edital         = $notice->file;
@endphp

<section id="quick-access">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">

                <div class="card h-100 shadow border border-0">
                    <div class="card-header"><i class="bi bi-list-check"></i> Acesso Rápido</div>
                    <div class="card-body pt-3 pb-5">

                        <div class="links">

                            <a href="{{ route('faq.public.index') }}">
                                <i class="bi bi-question-circle fs-1"></i> Dúvidas Frequentes
                            </a>

                            <a href="{{ route('archives.public.index') }}">
                                <i class="bi bi-file-text fs-1"></i> Provas e Gabaritos
                            </a>

                            @if ($status_edital)
                                <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdropNotice">
                                    <i class="bi bi-file-earmark-pdf fs-1"></i> Edital
                                </a>
                            @endif

                            @if ($calendar->isInscriptionOpen())
                                <a href="{{ route('register') }}">
                                    <i class="bi bi-person-plus fs-1"></i> Registrar-se
                                </a>
                            @endif

                            @if ($calendar->hasInscriptionStarted())
                                <a href="{{ route('login') }}">
                                    <i class="bi bi-person-lock fs-1"></i> Área do Candidato
                                </a>
                            @endif

                            @if ($settings->result)
                                <a href="{{ route('results') }}">
                                    <i class="bi bi-trophy fs-1"></i> Classificação
                                </a>
                            @endif

                            @if ($calls)
                                <a href="{{ route('calls') }}">
                                    <i class="bi bi-megaphone fs-1"></i> Convocação para Matrícula
                                </a>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">

                <div class="card h-100 shadow border border-0">
                    <div class="card-header">
                        <i class="bi bi-exclamation-circle"></i> Informações do {{ config('app.name') }}
                        {{ $calendar?->year }}
                    </div>
                    <div class="card-body events pt-3 pb-5 overflow-y-scroll hide-scrollbar" style="max-height: 320px;" id="autoScrollEvents">
                        
                        <ul class="list-group list-group-flush">

                            @foreach ($calendar->events() as $event)
                                <li class="list-group-item">
                                    <strong>{!! $event['icon'] !!} {{ $event['label'] }}</strong><br>

                                    @if ($event['type'] === 'period')
                                        {{ $calendar->formatPeriod($event['start'], $event['end']) }}
                                    @else
                                        {{ $calendar->formatDate($event['date']) }}
                                    @endif
                                </li>
                            @endforeach

                        </ul>

                    </div>
                </div>
            </div>

        </div>
    </div>

    </div>
</section>

@push('scripts')
<script src="{{ asset('assets/home/autoScrollEvents.js') }}"></script>
@endpush