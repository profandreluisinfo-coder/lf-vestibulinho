@if ($calendar->exists())

<section id="calendary" class="bg-light py-5">

    <div class="container">

        <h2 class="section-title text-center mb-5">
            Calendário do {{ config('app.name') }} {{ $calendar->year }}
        </h2>

        <h5 class="pb-2 mb-4 text-center text-muted text-uppercase">Principais Etapas</h5>

        <div class="row">
            <div class="col-lg-10 mx-auto">

                <div class="calendar-horizontal-wrapper">

                    <div class="calendar-horizontal">

                        {{-- Período de Inscrições --}}
                        <div class="cal-item {{ !$calendar->hasInscriptionStarted() ? 'cal-item-inactive' : ($calendar->isInscriptionOpen() ? 'cal-item-active' : 'cal-item-completed') }}">
                            <div class="cal-icon bg-primary">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <div class="cal-card">
                                <h5 class="fw-bold mb-1">Período de Inscrições</h5>
                                <p class="small text-muted mb-2">Inscrições abertas para todos os cursos</p>
                                <span class="badge {{ $calendar->isInscriptionOpen() ? 'bg-success' : ($calendar->hasInscriptionStarted() ? 'bg-secondary' : 'bg-warning') }} p-2">
                                    {{ Carbon\Carbon::parse($calendar->inscription_start)->format('d/m/Y') }}
                                    até
                                    {{ Carbon\Carbon::parse($calendar->inscription_end)->format('d/m/Y') }}
                                </span>
                                @if (!$calendar->hasInscriptionStarted())
                                    <span class="cal-status-badge">Em Breve</span>
                                @elseif ($calendar->isInscriptionOpen())
                                    <span class="cal-status-badge cal-status-active">Em Andamento</span>
                                @else
                                    <span class="cal-status-badge cal-status-completed">Concluído</span>
                                @endif
                            </div>
                        </div>

                        {{-- Aplicação das Provas --}}
                        <div class="cal-item {{ !$calendar->hasInscriptionEnded() ? 'cal-item-inactive' : ($calendar->hasExamDatePassed() ? 'cal-item-completed' : 'cal-item-active') }}">
                            <div class="cal-icon bg-danger">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="cal-card">
                                <h5 class="fw-bold mb-1">Aplicação das Provas</h5>
                                <p class="small text-muted mb-2">Prova objetiva para todos os candidatos</p>
                                <span class="badge {{ $calendar->hasExamDatePassed() ? 'bg-secondary' : ($calendar->hasInscriptionEnded() ? 'bg-danger' : 'bg-warning') }} p-2">
                                    {{ Carbon\Carbon::parse($calendar->exam_date)->format('d/m/Y') }}
                                </span>
                                @if (!$calendar->hasInscriptionEnded())
                                    <span class="cal-status-badge">Em Breve</span>
                                @elseif (!$calendar->hasExamDatePassed())
                                    <span class="cal-status-badge cal-status-active">Próximo</span>
                                @else
                                    <span class="cal-status-badge cal-status-completed">Concluído</span>
                                @endif
                            </div>
                        </div>

                        {{-- Resultado Final --}}
                        <div class="cal-item {{ !$calendar->hasExamDatePassed() ? 'cal-item-inactive' : ($calendar->isFinalResultPublished() ? 'cal-item-completed' : 'cal-item-active') }}">
                            <div class="cal-icon bg-success">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="cal-card">
                                <h5 class="fw-bold mb-1">Resultado Final</h5>
                                <p class="small text-muted mb-2">Classificação geral dos candidatos</p>
                                <span class="badge {{ $calendar->isFinalResultPublished() ? 'bg-success' : ($calendar->hasExamDatePassed() ? 'bg-warning' : 'bg-secondary') }} p-2">
                                    {{ Carbon\Carbon::parse($calendar->final_result_publish)->format('d/m/Y') }}
                                </span>
                                @if (!$calendar->hasExamDatePassed())
                                    <span class="cal-status-badge">Em Breve</span>
                                @elseif (!$calendar->isFinalResultPublished())
                                    <span class="cal-status-badge cal-status-active">Aguardando</span>
                                @else
                                    <span class="cal-status-badge cal-status-completed">Publicado</span>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('calendary') }}" class="btn btn-sm btn-primary">
                        Ver Calendário Completo
                    </a>
                </div>

            </div>
        </div>

    </div>

</section>

@endif