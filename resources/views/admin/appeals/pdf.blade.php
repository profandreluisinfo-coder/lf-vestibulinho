<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Recurso {{ $appeal->protocol }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        h2 { font-size: 13px; margin: 20px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #999; color: #444; }
        .sub { color: #666; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px 0; vertical-align: top; }
        td.rotulo { width: 30%; font-weight: bold; color: #444; }
        .rodape { margin-top: 30px; font-size: 10px; color: #888; text-align: center; }
    </style>
</head>
<body>

    <h1>Recurso - {{ $appeal->typeLabel() }}</h1>
    <div class="sub">Protocolo {{ $appeal->protocol }}</div>

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

    <div class="rodape">Gerado em {{ now()->format('d/m/Y H:i') }}</div>

</body>
</html>