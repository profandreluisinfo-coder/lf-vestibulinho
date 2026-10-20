<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Recurso {{ $appeal->protocol }}</title>
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
            margin: 0 0 4px;
        }

        h2 {
            font-size: 14px;
            border-bottom: 1px solid #999;
            padding-bottom: 4px;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .sub {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        td.rotulo {
            width: 30%;
            font-weight: bold;
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

    <h1>Vestibulinho LF {{ $process->year }} - Recurso - {{ $appeal->typeLabel() }}</h1>
    <div class="sub">Protocolo {{ $appeal->protocol }}</div>
    @if ($appeal->type === 'pne')
        <p><b>Ref.:</b> Interposição de recurso contra o indeferimento da solicitação de atendimento especializado para
            realização da prova, na condição de pessoa com deficiência (PcD).</p>
    @else
    <p><b>Ref.:</b> Interposição de recurso contra o indeferimento da solicitação de uso de nome social/afetivo.</p>
    @endif
    <h2>Dados do recurso</h2>
    <table>
        <tr>
            <td class="rotulo">Candidato</td>
            <td>{{ $appeal->user->name }}</td>
        </tr>
        <tr>
            <td class="rotulo">Inscrição</td>
            <td>{{ $appeal->user->inscription?->id }}</td>
        </tr>
        <tr>
            <td class="rotulo">Registrado em</td>
            <td>{{ $appeal->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="rotulo">Situação</td>
            <td>{{ $appeal->statusLabel() }}</td>
        </tr>
        <tr>
            <td class="rotulo">Alegações</td>
            <td>{{ $appeal->allegations ?? '-' }}</td>
        </tr>
        <tr>
            <td class="rotulo">Observações do recurso</td>
            <td>{{ $appeal->observations ?? '-' }}</td>
        </tr>
        @unless ($appeal->isPending())
            <tr>
                <td class="rotulo">Decidido em</td>
                <td>
                    {{ $appeal->decided_at?->format('d/m/Y H:i') }}
                    @if ($appeal->decider)
                        por {{ $appeal->decider->name }}
                    @endif
                </td>
            </tr>
        @endunless
    </table>

    <h2>Pedido original</h2>
    <table>
        @if ($appeal->type === 'pne')
            <tr>
                <td class="rotulo">Condição</td>
                <td>{{ $original?->description ?? '-' }}</td>
            </tr>
            <tr>
                <td class="rotulo">Apoio solicitado</td>
                <td>{{ $original?->support ?? 'Não Informado' }}</td>
            </tr>
        @else
            <tr>
                <td class="rotulo">Nome Social</td>
                <td>{{ $original?->name ?? '-' }}</td>
            </tr>
        @endif
        <tr>
            <td class="rotulo">Observações do pedido</td>
            <td>{{ $original?->observations ?? '-' }}</td>
        </tr>
    </table>

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

    <div class="rodape">Gerado em {{ now()->format('d/m/Y H:i') }}</div>

</body>

</html>
