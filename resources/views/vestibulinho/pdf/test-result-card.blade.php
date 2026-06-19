<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      text-align: center;
      padding: 40px;
    }

    h1,
    h2 {
      margin: 0;
    }

    .nota {
      font-size: 48px;
      color: green;
      font-weight: bold;
    }

    .classificacao {
      font-size: 36px;
      color: navy;
      font-weight: bold;
      margin-top: 20px;
    }

    .footer {
      margin-top: 50px;
      font-size: 12px;
      color: #666;
    }
  </style>
</head>

<body>

  <div style="text-align: center; padding: 30px; font-family: DejaVu Sans, sans-serif;">
    <h2>{{ config('app.name') }} {{ $calendar?->year }}</h2>
    <h1>Resultado da Prova</h1>
    <p>Ano {{ $calendar?->year }}</p>

    <hr style="margin: 20px 0;">

    <h3 style="margin-bottom: 5px;">{{ $user->name }}</h3>
    <p style="margin: 0;">CPF: {{ $user->cpf }}</p>

    <div style="margin: 30px 0;">
      <div style="font-size: 40px; color: green; font-weight: bold;">{{ $examResult->score }} pontos</div>
      <div style="font-size: 32px; color: navy; font-weight: bold;">{{ $examResult->ranking }}º colocado</div>
    </div>

    <hr style="margin: 30px 0;">

    <div style="font-size: 12px; color: #555;">
      Critério de desempate: candidato mais jovem tem prioridade.
    </div>
  </div>

</body>

</html>