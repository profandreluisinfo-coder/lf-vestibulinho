<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Relatório de Alocação</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
    }

    .page-break {
      page-break-after: always;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
    }

    h2 {
      margin-top: 40px;
    }
  </style>
</head>

<body>

  @foreach ($allocations as $location => $rooms)
    <h2>Local: {{ $location }}</h2>

    @foreach ($rooms as $room => $candidates)
      <h3>Sala {{ $room }}</h3>
      <table>
        <thead>
          <tr>
            <th>Candidato</th>
            <th>Inscrição Nº</th>
            {{-- <th>DT Nasc</th> --}}
            <th>PNE</th>
            <th>Local</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($candidates as $candidate)
            <tr>
              <td>{{ $candidate->name }}
                {{ $candidate->social_name ? ' - Nome Social: ' . $candidate->social_name : '' }}</td>
              <td>{{ $candidate->inscription_id }}</td>
              {{-- <td>{{ \Carbon\Carbon::parse($candidate->birth)->format('d/m/Y') }}</td> --}}
              <td>{{ $candidate?->pne == 1 ? 'Sim' : 'Não' }}</td>
              <td>{{ $location }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endforeach

    <div class="page-break"></div>
  @endforeach

</body>

</html>
