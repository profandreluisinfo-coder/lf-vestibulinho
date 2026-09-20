<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 6px 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        td.numero { text-align: center; }
    </style>
</head>
<body>
    <h2>Vestibulinho LF {{ $process?->year }} - Candidatos por Curso e Sexo</h2>
    <table>
        <thead>
            <tr>
                <th>Curso</th>
                <th>Masculino</th>
                <th>Feminino</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($genderPerCourse as $item)
                <tr>
                    <td>{{ $item->course }}</td>
                    <td class="numero">{{ $item->masculino }}</td>
                    <td class="numero">{{ $item->feminino }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>