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
    <h4>Candidatos com Deficiência e Laudo/Relatório Médico Aprovado</h4>
    <table>
        <thead>
            <tr>
                <th>Inscrição</th>
                <th>Candidato</th>
                <th>CPF</th>
                <th>Condição</th>
                <th>Requer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pcds as $pcd)
                <tr>
                    <td>{{ $pcd->user->inscription->id ?? '-' }}</td>
                    <td>{{ $pcd->user->lgbt?->status === 'accepted' ? $pcd->user->lgbt->name : $pcd->user->name }}</td>
                    <td>{{ $pcd->user->cpf ?? '-' }}</td>
                    <td>{{ $pcd->description }}</td>
                    <td>{{ $pcd->support }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>