<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            margin: 0 auto;
            text-align: center
        }

        img {
            width: 45px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        th {
            text-align: center;
            background: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('assets/img/logo.webp') }}" class="logo" alt="Logo da Escola">

        <h2>Vestibulinho LF {{ $process?->year }} - Lista Geral de Inscritos</h2>
    </div>

    @if ($search)
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
            @foreach ($users as $user)
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
