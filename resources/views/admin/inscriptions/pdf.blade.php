<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 30px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 14px;
            border-bottom: 1px solid #999;
            padding-bottom: 4px;
            margin-top: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            width: 30%;
            background: #f0f0f0;
        }

        .parecer td {
            border: none;
            padding: 6px 8px;
        }

        .caixa {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #222;
            margin-right: 6px;
            vertical-align: middle;
        }

        .motivo {
            border: 1px solid #ccc;
            height: 110px;
            margin-top: 6px;
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

        .rodape {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>

    @php $user = $inscription->user; @endphp

    <h1>Vestibulinho LF {{ $process->year }} - {{ $title }}</h1>

    <p><strong>Ref.:</strong><br>

        @if ($type === 'pcd')
            Solicitação de atendimento especializado para realização da prova, na condição de
            pessoa com deficiência (PcD).
        @else
            Solicitação de uso de nome social (Lei nº 16.785/2018 / Decreto nº 8.727/2016)
        @endif
    </p>

    <h2>Dados do candidato</h2>
    <table>
        <tr>
            <th>Inscrição</th>
            <td>{{ $user->inscription->id }}</td>
        </tr>
        <tr>
            <th>Nome de Registro</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>CPF</th>
            <td>{{ $user->cpf }}</td>
        </tr>
        <tr>
            <th>Data de nascimento</th>
            <td>
                {{ $user->birth ? \Carbon\Carbon::parse($user->birth)->format('d/m/Y') : '-' }}
            </td>
        </tr>
        <tr>
            <th>Gênero</th>
            <td>{{ $user->gender }}</td>
        </tr>
    </table>

    @if ($type === 'pcd')
        <h2>Dados de Pessoa com Deficiência</h2>
        <table>
            <tr>
                <th>Condição apresentada</th>
                <td>{{ $user->pne->description ?? '-' }}</td>
            </tr>
            <tr>
                <th>Suporte solicitado</th>
                <td>{{ $user->pne->support ?? '-' }}</td>
            </tr>
        </table>
    @else
        <h2>Dados de Nome Social</h2>
        <table>
            <tr>
                <th>Nome Social</th>
                <td>{{ $user->lgbt->name ?? '-' }}</td>
            </tr>
        </table>
    @endif

    <h2>Parecer</h2>
    <table class="parecer">
        <tr>
            <td>
                <span class="caixa"></span> Deferido
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="caixa"></span> Indeferido
            </td>
        </tr>
    </table>

    <p style="margin-bottom: 0;"><strong>Motivo:</strong></p>
    <div class="motivo"></div>

    <table class="assinaturas">
        <tr>
            <td>
                <div class="linha">Diretor(a)</div>
            </td>
            <td>
                <div class="linha">Coordenador(a) Pedagógico(a)</div>
            </td>
        </tr>
    </table>

    <div class="rodape">
        Gerado em {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>
