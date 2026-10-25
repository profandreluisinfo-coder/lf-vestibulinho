<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Vestibulinho LF {{ $process->year }} - Lista de Assinaturas</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        img {
            width: 45px
        }

        .header-top {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
            margin-bottom: 8px;
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

        .header-top .subtitle {
            font-size: 11px;
            color: #555;
        }

        .info {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #999;
            margin-bottom: 15px;
        }

        .info td {
            padding: 6px 8px;
            border: none;
            font-size: 12px;
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

        .legal {
            font-size: 11px;
            margin-bottom: 10px;
        }

        .note-lines {
            border: 1px dashed #999;
            height: 240px;
            padding: 10px;
        }

        .assinaturas {
            width: 100%;
            margin-top: 80px;
            page-break-inside: avoid;
        }

        .assinaturas td {
            border: none;
            width: 50%;
            text-align: center;
            padding: 0 25px;
        }

        .linha {
            border-top: 1px solid #222;
            padding-top: 4px;
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
                            <div class="subtitle">Lista de Assinaturas</div>
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
                                {{ $candidate->social_name ?? $candidate->name }}
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

            <table class="assinaturas">
                <tr>
                    <td>
                        <div class="linha">Fiscal</div>
                    </td>
                </tr>
            </table>

            <table class="assinaturas">
                <tr>
                    <td>
                        <div class="linha">Diretor(a)</div>
                    </td>
                    <td>
                        <div class="linha">Coord. Pedagógico(a)</div>
                    </td>
                </tr>
            </table>
        @endforeach
    @endforeach
</body>

</html>
