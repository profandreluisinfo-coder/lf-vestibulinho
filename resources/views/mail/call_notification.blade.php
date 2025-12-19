<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Informações sobre o Local de Prova</title>
</head>

<body>

  <p>Prezado(a) candidato(a), {{ $nome }}!</p>

  <p>Parabéns pela sua aprovação no Processo Seletivo {{ $calendar->year }} da ESCOLA MUNICIPAL DR. LEANDRO
    FRANCESCHINI! Estamos felizes em recebê-lo(a) em nossa Unidade Escolar.</p>

  <p>É com grande satisfação que convocamos seus responsáveis legais para realizar sua matrícula no ENSINO TÉCNICO DE
    NÍVEL MÉDIO INTEGRADO, <strong>{{ $numero_chamada }}ª chamada</strong>,imprescindivelmente no:</p>

  <p>
    <strong>Dia:</strong> {{ $data }}<br>
    <strong>Horário:</strong> {{ $hora }}<br>
    <strong>Local:</strong> R. Geraldo de Souza, 157/221 - Jardim São Carlos, Sumaré - SP, 13170-232
  </p>

  <p>
    <strong>INFORMAÇÕES IMPORTANTES!</strong>
  </p>

  <p>A falta de documentação ou não comparecimento na data e horário estabelecido acarretará na perda da vaga, portanto
    não se esqueça de comparecer no dia e horário indicado portando todos os documentos previstos no item
    <strong>7.4</strong> do edital.
  </p>

  <p>Quais sejam:</p>

  <ol>
    <li>Declaração de Conclusão do Ensino Fundamental ou Histórico Escolar do Ensino Fundamental (Original e 01 cópia);
    </li>
    <li>01 foto 3x4;</li>
    <li>Original e 01 cópia do documento de identidade (RG/CIN ou RNE para estrangeiro) atualizado e com foto que
      identifique o portador;</li>
    <li>Original e 01 cópia do CPF;</li>
    <li>Original e 01 cópia da certidão de nascimento;</li>
    <li>Carteira de vacinação (Original e 01 cópia);</li>
    <li>Comprovante de residência no município de Sumaré com menos de 60 dias de emissão, em nome dos pais ou do
      responsável legal pelo (a) candidato (a); (Original e 01 cópia)</li>
  </ol>

  <p>O(a) candidato(a) elegível aos serviços da educação especial deverá apresentar laudo médico no ato da matrícula.
  </p>
  <p>Àqueles que possuem responsáveis legais que não sejam os pais biológicos, devem trazer o documento que comprove a
    guarda ou a tutela.</p>
  <p>Agradecemos sua atenção e os aguardamos na E.M. Dr. Leandro Franceschini para a realização da matrícula.</p>

  <p>Atenciosamente,<br>
    Equipe de Seleção E.M. Dr. Leandro Franceschini<br>
    <strong>E. M. Dr. Leandro Franceschini - Prefeitura Muninicipal de Sumaré</strong>
  </p>
  @include('partials.mail.footer')
</body>

</html>
