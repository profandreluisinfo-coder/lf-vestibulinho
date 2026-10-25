<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Vestibulinho LF {{ $process->year }} - Lista para Mural e Salas</title>
    <style>
       body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 10px;
        }

        img {
            width: 45px
        }

        .header-top {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
            margin-bottom: 5px;
        }

        .header-top td {
            vertical-align: middle;
            padding: 0 0 6px 0;
            border: none;
        }

        .header-top .logo-cell {
            width: 55px;
        }

        .header-top .title {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .info {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #999;
            margin-bottom: 10px;
        }

        .info td {
            padding: 6px 8px;
            border: none;
            font-size: 12px;
        }

        thead th,
        .header {
            text-align: center;
            text-transform: uppercase
        }

        .room-header {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
        }

        .table td {
            padding: 1px;
            border-bottom: 1px solid #ddd;
            text-align: center
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    @foreach ($allocations as $location => $rooms)
        @foreach ($rooms as $room => $candidates)
            @php $first = $candidates->first(); @endphp

            <div class="header" style="page-break-before: {{ $loop->parent->first && $loop->first ? 'auto' : 'always' }}">
                <table class="header-top">
                    <tr>
                        <td class="logo-cell">
                            <img src="{{ public_path('assets/img/logo.webp') }}" class="logo" alt="Logo da Escola">
                        </td>
                        <td>
                            <h3 class="title">Vestibulinho LF {{ $process?->year }}</h3>
                        </td>
                    </tr>
                </table>

                <table class="info">
                    <tr>
                        <td style="width: 48%"><strong>LOCAL:</strong> {{ $location }}</td>
                        <td style="width: 12%"><strong>SALA:</strong> {{ $room }}</td>
                        <td style="width: 23%"><strong>DATA:</strong>
                            {{ \Carbon\Carbon::parse($first->exam_date)->format('d/m/Y') }}</td>
                        <td style="width: 17%"><strong>HORA:</strong>
                            {{ \Carbon\Carbon::parse($first->exam_time)->format('H:i') }}</td>
                    </tr>
                </table>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Inscrição</th>
                        <th>Nome Completo</th>
                        <th>Data de Nascimento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidates as $candidate)
                        <tr>
                            <td>{{ $candidate->inscription_id }}</td>
                            <td>{{ $candidate->social_name ?? $candidate->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($candidate->birth)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach
</body>

</html>
