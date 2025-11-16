@extends('layouts.dash.admin')

@section('page-title', 'Convocados da Chamada ' . $call_number)

@section('dash-content')

  <div class="container my-4">
    <h3>Convocados da Chamada {{ $call_number }}</h3>

    @if ($convocados->isEmpty())
      <div class="alert alert-info">Nenhum candidato convocado para essa chamada.</div>
    @else
      <table class="table-bordered table-hover table align-middle">
        <thead>
          <tr>
            <th>CPF</th>
            <th>Nome</th>
            <th>PCD</th>
            <th>Classificação</th>
            <th>Nota</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($convocados as $call)
            <tr>
              <td>{{ $call->examResult->inscription->user->cpf }}</td>
              <td>{{ $call->examResult->inscription->user->name }}</td>
              <td>{{ $call->examResult->inscription->user->pne ? 'Sim' : 'Não' }}</td>
              <td>{{ $call->examResult->ranking }}</td>
              <td>{{ $call->examResult->score }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif

    <a href="{{ admin . callings . create }}" class="btn btn-secondary mt-3">Voltar para Chamadas</a>
  </div>

@endsection
