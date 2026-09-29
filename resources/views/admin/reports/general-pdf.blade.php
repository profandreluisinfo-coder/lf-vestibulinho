<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório Geral</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        .data {
            color: #666;
            margin-bottom: 12px;
        }

        .line {
            border-bottom: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 4px 6px;
            text-align: left;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

    <h1 class="line">Vestibulinho LF {{ $selectedProcess?->year ?? $process?->year }} - Relatório Geral -
        {{ $type === 'summary' ? 'Resumo' : 'Lista de candidatos' }}</h1>
    <div class="data">Gerado em {{ now()->format('d/m/Y H:i') }}</div>

    @if ($type === 'summary')

        @if ($groupBy === 'course_gender')

            <table>
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Masculino</th>
                        <th>Feminino</th>
                        <th>Outro</th>
                        <th>Prefiro não informar</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($summary as $row)
                        <tr>
                            <td>{{ $row->grupo }}</td>
                            <td>{{ $row->masculino }}</td>
                            <td>{{ $row->feminino }}</td>
                            <td>{{ $row->outro }}</td>
                            <td>{{ $row->nao_informado }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total geral</th>
                        <th>{{ $summary->sum('masculino') }}</th>
                        <th>{{ $summary->sum('feminino') }}</th>
                        <th>{{ $summary->sum('outro') }}</th>
                        <th>{{ $summary->sum('nao_informado') }}</th>
                        <th>{{ $summary->sum('total') }}</th>
                    </tr>
                </tfoot>
            </table>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Grupo</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($summary as $row)
                        <tr>
                            <td>{{ $row->grupo }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total geral</th>
                        <th>{{ $summary->sum('total') }}</th>
                    </tr>
                </tfoot>
            </table>
        @endif
    @else
        @php
            $statusLabels = [
                'pending' => 'PENDENTE',
                'accepted' => 'ACEITO',
                'rejected' => 'REJEITADO',
            ];
        @endphp

        <table>
            <thead>
                <tr>
                    <th>Candidato</th>
                    <th>Curso</th>
                    <th>Escola</th>
                    <th>Gênero</th>
                    <th>PCD</th>
                    <th>Nome Social</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inscriptions as $inscription)
                    <tr>
                        <td>{{ $inscription->user->name }}</td>
                        <td>{{ $inscription->course->name }}</td>
                        <td>{{ $inscription->user->academic?->school ?? '—' }}</td>
                        <td>{{ $inscription->user->gender }}</td>
                        <td>{{ $statusLabels[$inscription->user->pne?->status] ?? '—' }}</td>
                        <td>{{ $statusLabels[$inscription->user->lgbt?->status] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif

</body>

</html>
