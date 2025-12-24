<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Lista de Assinaturas</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      margin: 30px;
    }

    .header h3::first-line {
      text-transform: uppercase
    }

    thead th,
    .header {
      text-align: left;
    }

    .room-header {
      font-weight: bold;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .table td {
      padding: 4px;
      border-bottom: 1px solid #ddd;
    }

    /* tr:nth-child(even){background-color: #f2f2f2;}

    tr:hover {background-color: #ddd;} */

    .legal {
      font-size: 11px;
      margin-bottom: 10px;
    }

    .note-lines {
      border: 1px dashed #999;
      height: 240px;
      padding: 10px;
    }

    .page-break {
      page-break-after: always;
    }
  </style>
</head>

<body>
  @foreach ($allocations as $location => $rooms)
    @foreach ($rooms as $room => $candidates)
      <div class="header">
        {{-- <img src="{{ public_path('assets/img/logo.webp') }}" class="logo" alt="Logo da Escola"> --}}
        <h3>{{ config('app.name') }} {{ $calendar->year }}</h3>
        <h3>LOCAL: {{ $location }}</h3>
        <h4>SALA: {{ $room }}</h4>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th style="width: 15%;text-align: center">Inscrição Nº</th>
            <th style="width: 50%;">Nome</th>
            <th>Assinatura</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($candidates as $candidate)
            <tr>
              <th>{{ $candidate->inscription_id }}</td>
              <td style="border: none;">
                {{ $candidate->social_name ?: $candidate->name }} {{ $candidate->social_name ? '*' : '' }}
              </td>
              <td style="border-bottom: 1px solid #000"></td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="observations">
        <div class="note">
          <strong>Observações da sala:</strong>
          <div class="note-lines"></div>
        </div>
      </div>

      <div class="page-break"></div>
    @endforeach
  @endforeach
</body>

</html>
