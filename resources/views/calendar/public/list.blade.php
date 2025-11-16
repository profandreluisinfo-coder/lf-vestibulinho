@extends('layouts.home.master')

@section('page-title', 'Vestibulinho LF ' . $calendar->year )

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/calendar/styles.css') }}">
@endpush

@section('body-class', 'py-5')

@section('content')
  <!-- Navbar -->
  @include('home.navbar')

  <!-- Calendário -->
  <section id="calendario" class="bg-light py-5">

    <div class="container">
      <h2 class="section-title text-center">Calendário do Processo Seletivo {{ $calendar->year }}</h2>
      @if ($calendar?->hasInscriptionStarted())
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <div class="calendar-container">

              {{-- Período de Inscrições --}}
              <div class="calendar-event {{ !$calendar->hasInscriptionStarted() ? 'event-inactive' : ($calendar->isInscriptionOpen() ? 'event-active' : 'event-completed') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Período de Inscrições</h5>
                    <p class="mb-0">Inscrições abertas para todos os cursos</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isInscriptionOpen() ? 'bg-success' : 'bg-secondary' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->inscription_start)->format('d/m') }} -
                      {{ Carbon\Carbon::parse($calendar->inscription_end)->format('d/m') }}
                    </span>
                    @if ($calendar->isInscriptionOpen())
                      <span class="event-status-badge event-status-active">Em Andamento</span>
                    @elseif ($calendar->hasInscriptionEnded())
                      <span class="event-status-badge event-status-completed">Concluído</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Necessidades Especiais --}}
              <div class="calendar-event {{ !$calendar->hasInscriptionStarted() ? 'event-inactive' : ($calendar->isInscriptionOpen() ? 'event-active' : 'event-completed') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Período para especificação de Necessidades Especiais</h5>
                    <p class="mb-0">Período para o(a)s candidato(a)s Portadores de Necessidades Especiais enviarem e-mail com atestado médico.</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isInscriptionOpen() ? 'bg-success' : 'bg-secondary' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->inscription_start)->format('d/m') }} -
                      {{ Carbon\Carbon::parse($calendar->inscription_end)->format('d/m') }}
                    </span>
                    @if ($calendar->isInscriptionOpen())
                      <span class="event-status-badge event-status-active">Em Andamento</span>
                    @elseif ($calendar->hasInscriptionEnded())
                      <span class="event-status-badge event-status-completed">Concluído</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Prova Ampliada --}}
              <div class="calendar-event {{ !$calendar->hasInscriptionStarted() ? 'event-inactive' : ($calendar->isInscriptionOpen() ? 'event-active' : 'event-completed') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Período para solicitação de prova ampliada</h5>
                    <p class="mb-0">Período para o(a) candidato(a) portador de baixa visão informar via e-mail que necessita de prova ampliada e qual o tamanho da letra que precisa para realizar a prova.</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isInscriptionOpen() ? 'bg-success' : 'bg-secondary' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->inscription_start)->format('d/m') }} -
                      {{ Carbon\Carbon::parse($calendar->inscription_end)->format('d/m') }}
                    </span>
                    @if ($calendar->isInscriptionOpen())
                      <span class="event-status-badge event-status-active">Em Andamento</span>
                    @elseif ($calendar->hasInscriptionEnded())
                      <span class="event-status-badge event-status-completed">Concluído</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Nome Social --}}
              <div class="calendar-event {{ !$calendar->hasInscriptionStarted() ? 'event-inactive' : ($calendar->isInscriptionOpen() ? 'event-active' : 'event-completed') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Período para solicitação de autorização de uso do Nome Social</h5>
                    <p class="mb-0">Período para o(a)s candidatos(a)s que desejam enviar autorização dos responsáveis para utilização do Nome Social</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isInscriptionOpen() ? 'bg-success' : 'bg-secondary' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->inscription_start)->format('d/m') }} -
                      {{ Carbon\Carbon::parse($calendar->inscription_end)->format('d/m') }}
                    </span>
                    @if ($calendar->isInscriptionOpen())
                      <span class="event-status-badge event-status-active">Em Andamento</span>
                    @elseif ($calendar->hasInscriptionEnded())
                      <span class="event-status-badge event-status-completed">Concluído</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Divulgação dos Locais de Prova --}}
              <div class="calendar-event {{ !$calendar->hasInscriptionEnded() ? 'event-inactive' : ($calendar->isExamLocationPublished() ? 'event-completed' : 'event-active') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Divulgação dos Locais de Prova</h5>
                    <p class="mb-0">Consulte onde será realizada sua prova</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isExamLocationPublished() ? 'bg-success' : 'bg-warning' }} text-dark mb-2">
                      {{ Carbon\Carbon::parse($calendar->exam_location_publish)->format('d/m') }}
                    </span>
                    @if (!$calendar->hasInscriptionEnded())
                      <span class="event-status-badge">Em Breve</span>
                    @elseif ($calendar->isExamLocationPublished())
                      <span class="event-status-badge event-status-completed">Publicado</span>
                    @else
                      <span class="event-status-badge event-status-active">Aguardando</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Aplicação das Provas --}}
              <div class="calendar-event {{ !$calendar->hasInscriptionEnded() ? 'event-inactive' : ($calendar->hasExamDatePassed() ? 'event-completed' : 'event-active') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Aplicação das Provas</h5>
                    <p class="mb-0">Prova objetiva para todos os candidatos</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->hasExamDatePassed() ? 'bg-secondary' : 'bg-danger' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->exam_date)->format('d/m') }}
                    </span>
                    @if (!$calendar->hasInscriptionEnded())
                      <span class="event-status-badge">Em Breve</span>
                    @elseif ($calendar->hasExamDatePassed())
                      <span class="event-status-badge event-status-completed">Realizado</span>
                    @else
                      <span class="event-status-badge event-status-active">Próximo</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Divulgação do Gabarito --}}
              <div class="calendar-event {{ !$calendar->hasExamDatePassed() ? 'event-inactive' : ($calendar->isAnswerKeyPublished() ? 'event-completed' : 'event-active') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Divulgação do Gabarito</h5>
                    <p class="mb-0">Divulgação do gabarito e da prova</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isAnswerKeyPublished() ? 'bg-success' : 'bg-info' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->answer_key_publish)->format('d/m') }}
                    </span>
                    @if (!$calendar->hasExamDatePassed())
                      <span class="event-status-badge">Em Breve</span>
                    @elseif ($calendar->isAnswerKeyPublished())
                      <span class="event-status-badge event-status-completed">Publicado</span>
                    @else
                      <span class="event-status-badge event-status-active">Aguardando</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Revisão das Questões --}}
              <div class="calendar-event {{ !$calendar->isAnswerKeyPublished() ? 'event-inactive' : ($calendar->isRevisionPeriodOpen() ? 'event-active' : ($calendar->hasRevisionPeriodEnded() ? 'event-completed' : 'event-active')) }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Revisão das Questões</h5>
                    <p class="mb-0">Prazo para protocolar a revisão das questões da prova</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isRevisionPeriodOpen() ? 'bg-warning' : 'bg-secondary' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->exam_revision_start)->format('d/m') }} -
                      {{ Carbon\Carbon::parse($calendar->exam_revision_end)->format('d/m') }}
                    </span>
                    @if (!$calendar->isAnswerKeyPublished())
                      <span class="event-status-badge">Em Breve</span>
                    @elseif ($calendar->isRevisionPeriodOpen())
                      <span class="event-status-badge event-status-active">Aberto</span>
                    @elseif ($calendar->hasRevisionPeriodEnded())
                      <span class="event-status-badge event-status-completed">Encerrado</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Resultado Final --}}
              <div class="calendar-event {{ !$calendar->hasRevisionPeriodEnded() ? 'event-inactive' : ($calendar->isFinalResultPublished() ? 'event-completed' : 'event-active') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Resultado Final</h5>
                    <p class="mb-0">Divulgação da lista geral com a classificação do(a)s candidatos(a)s</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isFinalResultPublished() ? 'bg-success' : 'bg-secondary' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->final_result_publish)->format('d/m') }}
                    </span>
                    @if (!$calendar->hasRevisionPeriodEnded())
                      <span class="event-status-badge">Em Breve</span>
                    @elseif ($calendar->isFinalResultPublished())
                      <span class="event-status-badge event-status-completed">Publicado</span>
                    @else
                      <span class="event-status-badge event-status-active">Aguardando</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Divulgação do Chamamento (1ª Chamada) --}}
              <div class="calendar-event {{ !$calendar->isFinalResultPublished() ? 'event-inactive' : ($calendar->isEnrollmentPeriodOpen() ? 'event-active' : 'event-completed') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Divulgação do Chamamento (1ª Chamada)</h5>
                    <p class="mb-0">Divulgação do cronograma da convocação para matrícula dos classificados em primeira chamada</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->isEnrollmentPeriodOpen() ? 'bg-primary' : 'bg-secondary' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->enrollment_start)->format('d/m') }}
                    </span>
                    @if (!$calendar->isFinalResultPublished())
                      <span class="event-status-badge">Em Breve</span>
                    @elseif ($calendar->isEnrollmentPeriodOpen())
                      <span class="event-status-badge event-status-active">Aberto</span>
                    @else
                      <span class="event-status-badge event-status-completed">Publicado</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Divulgação do Chamamento (Vagas Remanescentes) --}}
              <div class="calendar-event {{ !$calendar->isEnrollmentPeriodOpen() ? 'event-inactive' : ($calendar->hasEnrollmentPeriodEnded() ? 'event-completed' : 'event-active') }}">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1">
                    <h5 class="mb-1">Divulgação do Chamamento (Vagas Remanescentes)</h5>
                    <p class="mb-0">Divulgação do cronograma da convocação para matrícula em vagas remanescentes</p>
                  </div>
                  <div class="d-flex flex-column align-items-end">
                    <span class="badge {{ $calendar->hasEnrollmentPeriodEnded() ? 'bg-secondary' : 'bg-primary' }} mb-2">
                      {{ Carbon\Carbon::parse($calendar->enrollment_end)->format('d/m') }}
                    </span>
                    @if (!$calendar->isEnrollmentPeriodOpen())
                      <span class="event-status-badge">Em Breve</span>
                    @elseif ($calendar->hasEnrollmentPeriodEnded())
                      <span class="event-status-badge event-status-completed">Encerrado</span>
                    @else
                      <span class="event-status-badge event-status-active">Em Andamento</span>
                    @endif
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      @endif
    </div>
  </section>

  @include('home.footer')

@endsection

@push('scripts')
    <script src="{{ asset('assets/home/scrolling.js') }}"></script>
    <script src="{{ asset('assets/components/countdown.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 20) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });
        });
    </script>
@endpush