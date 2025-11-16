<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Informações sobre o Local de Prova</title>
</head>

<body>
  <p>Prezado(a) candidato(a), {{ $name }}!</p>

  <p>A Direção da E.M. Dr. Leandro Franceschini tem a satisfação de convocá-lo(a) para realizar a prova do Processo
    Seletivo {{ $calendar->year }}. Confira os dados abaixo:</p>

  <ul>
    <li><strong>Data:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</li>
    <li><strong>Hora:</strong> {{ $time }}</li>
    <li><strong>Local:</strong> {{ $location }}</li>
    <li><strong>Endereço:</strong> {{ $address }}</li>
    <li><strong>Sala:</strong> {{ $room_number }}</li>
  </ul>

  <p>Fique atento aos horários e ao local da prova: Chegue com antecedência (mínima de 30 minutos), pois em hipótese
    alguma será aceita a entrada do(a) candidato(a) após o fechamento do portão.</p>
  <ol>
    <li>Abertura dos portões: 7h30min;</li>
    <li>Fechamento dos portões: 8h00;</li>
    <li>Início previsto da prova: 8h00;</li>
    <li>Permanência mínima na sala: 1h30min;</li>
    <li>Permanência máxima na sala: 4h00;</li>
    <li>Previsão de término da prova: 12h00.</li>
    <li>Com acréscimo de 1h00 para os candidatos elegíveis aos serviços da educação especial.</li>
  </ol>

  <p>O que levar:</p>

  <ol>
    <li>Caneta azul ou preta com corpo transparente;</li>
    <li>Documento de identidade (RG/CIN) atualizado e com foto que identifique o portador;</li>
    <li>CPF;</li>
    <li><strong>Cartão de inscrição do candidato.</strong></li>
  </ol>

  <p>O que não é permitido:</p>

  <ol>
    <li>Relógios inteligentes;</li>
    <li>Fones de ouvido;</li>
    <li>Tablets e Notebooks;</li>
    <li>Calculadoras;</li>
    <li>Máquinas fotográficas;</li>
    <li>Gravadores e receptores ou</li>
    <li>Qualquer outro dispositivo que possibilite comunicação ou acesso à informação;</li>
    <li>O uso de bermudas, minissaias, minivestidos ou cropped.</li>
    <li>Boné, gorro, touca ou similares;</li>
  </ol>

  <p>Importante:<br>
    O celular deverá estar desligado, com todos os alarmes desativados e lacrado em embalagem própria fornecida pela
    escola.
  </p>

  <p>Atenciosamente,<br>
    Equipe de Seleção E.M. Dr. Leandro Franceschini<br>
    <strong>E. M. Dr. Leandro Franceschini - Prefeitura Muninicipal de Sumaré</strong>
  </p>
  @include('mail.footer')
</body>

</html>
