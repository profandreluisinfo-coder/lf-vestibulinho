<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Inscrições por dia</title>
    <style>
        @page {
            margin: 28px 32px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1e293b;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #1e293b;
            margin-bottom: 16px;
        }

        .header td {
            border: none;
            padding: 0 0 10px;
            vertical-align: middle;
        }

        .header .logo-cell {
            width: 54px;
        }

        .header img {
            width: 42px;
            height: auto;
        }

        .header .school-name {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        h1 {
            font-size: 16px;
            margin: 0 0 4px;
        }

        .sub {
            margin: 0 0 4px;
            color: #64748b;
        }

        .period {
            margin: 0 0 14px;
            color: #64748b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1e293b;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 5px 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .num {
            text-align: right;
        }

        tr.total td {
            font-weight: bold;
            border-top: 2px solid #1e293b;
            border-bottom: 0;
        }

        .foot {
            margin-top: 14px;
            color: #64748b;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <table class="header">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('assets/img/logo.webp') }}" alt="Logo da Escola">
            </td>
            <td class="school-name">
                EM Dr. Leandro Franceschini
            </td>
        </tr>
    </table>
    <h1>Inscrições por dia</h1>
    <p class="sub">Vestibulinho {{ $process->year }}</p>
    <p class="period">
        Período das inscrições: {{ $process->formatPeriod($process->start, $process->end) }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Dia da semana</th>
                <th class="num">Inscrições</th>
                <th class="num">Acumulado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['weekday'] }}</td>
                    <td class="num">{{ $row['total'] }}</td>
                    <td class="num">{{ $row['accumulated'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhuma inscrição no período.</td>
                </tr>
            @endforelse

            @if ($rows->isNotEmpty())
                <tr class="total">
                    <td colspan="2">Total</td>
                    <td class="num">{{ $total }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <p class="foot">Gerado em {{ $generatedAt->format('d/m/Y \à\s H:i') }}</p>
</body>

</html>
