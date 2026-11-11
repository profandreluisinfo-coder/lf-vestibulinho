@php
    $headerPath = public_path('assets/img/logo.webp');
    $headerImage = is_file($headerPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($headerPath))
        : null;
@endphp

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Vestibulinho LF {{ $process?->year }} - Relatório Geral de Alocação</title>
    <style>
        /* Margens da folha A4 (área útil = 197 mm de largura) */
        @page { margin: 10mm; }

        @font-face {
            font-family: 'Times New Roman', serif;
            font-style: normal;
            font-weight: normal;
        }
        @font-face {
            font-family: 'Times New Roman', serif;
            font-style: normal;
            font-weight: bold;
        }

        body {
            margin: 0;
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 0.927;   /* no DomPDF, 0,927 equivale a 1,172 do Word (ele multiplica pela altura própria da fonte) */
            color: #000;
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
            margin: 10px 0 10px 0;
        }

        .info td {
            padding: 6px 8px;
            border: none;
            font-size: 12px;
        }

        thead th {
            border-bottom: 2px solid #999;
            padding-bottom: 5px;
        }

        .room-header {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            padding: 1.5mm;
            border-bottom: 1px solid #ddd;
            text-align: center
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
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
                            @if ($headerImage)
                                <img src="{{ $headerImage }}" class="logo" alt="Logo da Escola">
                            @endif
                        </td>
                        <td>
                            <h3 class="title">Vestibulinho LF {{ $process?->year }}</h3>
                            <div class="subtitle">Relatório Geral de Alocação</div>
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
                        <th>INSCRIÇÃO</th>
                        <th>CANDIDATO</th>
                        <th>PcD</th>
                        <th>LOCAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidates as $candidate)
                        <tr>
                            <td>{{ $candidate->inscription_id }}</td>
                            <td>
                                @if ($candidate->social_name)
                                    {{ $candidate->social_name }}
                                @else
                                    {{ $candidate->name }}
                                @endif
                            </td>
                            <td>{{ $candidate?->pne == 1 ? 'Sim' : 'Não' }}</td>
                            <td>{{ $location }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach

</body>

</html>
