<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de Inscritos</title>

    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>{{ config('app.name') }} {{ $calendar?->year }} - Lista Geral de Inscritos</h2>

@if($search)
    <p><strong>Filtro aplicado:</strong> "{{ $search }}"</p>
@endif

<table>
    <thead>
        <tr>
            <th>Inscrição</th>
            <th>Nome</th>
            <th>CPF</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td style="text-align: center;">{{ $user->inscription?->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->cpf }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>