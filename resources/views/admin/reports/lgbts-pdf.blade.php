<!DOCTYPE html>
<html>
<head>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; font-size: 12px; text-align: center; }
        th { background-color: #d1e7dd; }
        .line { border-bottom: 1px solid black; }
    </style>
</head>
<body>
    <h4 class="line">Vestibulinho LF {{ $process?->year}}</h4>
    <h4>Candidatos com uso de Nome Social Aprovado</h4>
    <table>
        <thead>
            <tr>
                <th>Inscrição</th>
                <th>Candidato</th>
                <th>Nome Social</th>
                <th>CPF</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lgbts as $lgbt)
                <tr>
                    <td>{{ $lgbt->user->inscription->id }}</td>
                    <td>{{ $lgbt->user->name }}</td>
                    <td>{{ $lgbt->name }}</td>
                    <td>{{ $lgbt->user->cpf }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>