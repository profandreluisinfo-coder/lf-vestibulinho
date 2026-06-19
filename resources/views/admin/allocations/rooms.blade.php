<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista para Salas</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .sala {
            page-break-after: always;
        }
        .header {
            margin-bottom: 10px;
        }
        .title {
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #444;
            padding: 3px 5px;
            text-align: center;
        }

        tr:nth-child(even){background-color: #f2f2f2;}

        tr:hover {background-color: #ddd;}

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    @foreach($allocations as $location => $rooms)
        @foreach($rooms as $room => $candidates)
            <div class="sala">
                <div class="title">{{ config('app.name') }} {{ $calendar->year }}</div>

                <div class="header">
                    <strong>LOCAL:</strong> {{ $location }} -
                    <strong>SALA:</strong> {{ $room }}
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Inscrição Nº</th>
                            <th>Nome</th>
                            <th>Data de Nascimento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidates as $candidate)
                            <tr>
                                <td>{{ $candidate->inscription_id }}</td>
                                <td>{{ $candidate->social_name ? $candidate->social_name : $candidate->name }} {{ $candidate->social_name ? '*' : '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($candidate->birth)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endforeach
</body>
</html>