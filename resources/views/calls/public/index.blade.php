@extends('layouts.home.master')

@push('metas')
    <meta name="description" content="Área de perguntas frequentes sobre {{ config('app.name') }} {{ $calendar?->year }}">
@endpush

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Classificação Geral')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/calls/styles.css') }}">
@endpush

@section('body-class', 'bg-light')

@section('content')
  @include('home.navbar')
  <section id="faq" class="my-5 py-5">
    <div class="container">
      <h2 class="section-title text-center">{{ config('app.name') }} {{ $calendar?->year }} | Convocação para Matrícula
      </h2>
      <div class="row">
        <div class="col-lg-8 mx-auto">
          @if ($calls->isNotEmpty())
            @foreach ($calls as $callNumber => $convocados)
              <div class="mt-3">
                <h4 class="text-center">Chamada nº {{ $callNumber }}</h4>

                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text"><i class="bi bi-search"></i></span>
                  <input type="text" class="form-control search-input" placeholder="Pesquisar nesta chamada..."
                    data-table="calls-{{ $callNumber }}" autocomplete="off">
                </div>

                {{-- <div class="table-responsive"> --}}
                <div class="table-responsive mt-3 calls" style="max-height: 500px; overflow-y: auto;">
                  <table id="calls-{{ $callNumber }}" class="table-sm table-striped mb-0 table caption-top">
                    <caption class="bg-warning text-light px-4">
                      <strong>{{ config('app.name') }} {{ $calendar->year }} - Chamada nº {{ $callNumber }}</strong>
                    </caption>
                    <thead class="table-success">
                      <tr>
                        <th>Inscrição</th>
                        <th>Classificação</th>
                        <th>Nome</th>
                        <th>Data</th>
                        <th>Hora</th>
                      </tr>
                    </thead>
                    <tbody class="table-group-divider">
                      @forelse ($convocados as $call)
                        <tr>
                          <td>{{ $call->examResult->inscription->id }}</td>
                          </td>
                          <td>{{ $call->examResult->ranking }}º</td>
                          <td>
                            {{ $call->examResult->inscription->user->social_name ?? $call->examResult->inscription->user->name }}
                          </td>
                          <td>{{ \Carbon\Carbon::parse($call->date)->format('d/m/Y') }}</td>
                          <td>{{ \Carbon\Carbon::parse($call->time)->format('H:i') }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="5" class="text-center">Nenhum resultado encontrado.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            @endforeach
          @else
            <div class="alert alert-info text-center">Nenhuma chamada encontrada.</div>
          @endif
        </div>
      </div>
    </div>
  </section>

  @include('home.footer')

@endsection

@push('scripts')
  <script src="{{ asset('assets/filters/calls.js') }}"></script>
@endpush
