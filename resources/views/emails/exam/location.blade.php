  <p>Prezado(a) candidato(a), {{ $name }}!</p>

  <p>A Direção da E.M. Dr. Leandro Franceschini tem a satisfação de convocá-lo(a) para realizar a prova do Processo
      Seletivo {{ $process?->year }}. Confira o checklist oficial com tudo o que você precisa saber:</p>

  <ul>
      <li><strong>Data:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</li>
      <li><strong>Horário:</strong> {{ \Carbon\Carbon::parse($time)->format('H\hi') }}</li>
      <li><strong>Local:</strong> {{ $location }}</li>
      <li><strong>Endereço:</strong> {{ $address }}</li>
      <li><strong>Sala:</strong> {{ $room_number }}</li>
  </ul>

  <p>Fique atento aos horários e ao local da prova. Chegue com antecedência (mínima de 30 minutos), pois em hipótese
      alguma será aceita a entrada do(a) candidato(a) após o fechamento do portão.</p>
  <ol>
      <li>Abertura dos portões: 7h30min:<br>
          <strong>Portão 1 – Candidatos PcDs (entrada permitida com pai, mãe ou responsável)<br>
          Portão 2 – Demais candidatos (entrada exclusiva dos candidatos, os responsáveis poderão acompanhar os estudantes até o portão de acesso e aguardar na área externa da escola).</strong>
      </li>
      <li>Fechamento dos portões: 8h00;</li>
      <li>Início previsto da prova: 8h00;</li>
      <li>Permanência mínima na sala: 1h30min;</li>
      <li>Permanência máxima na sala: 4h00;</li>
      <li>Previsão de término da prova: 12h00.</li>
      <li>PcD com inscrição deferida: 1 hora de acréscimo para a realização da prova.</li>
  </ol>

  <p>O que levar:</p>

  <ol>
      <li><strong>Documento oficial com foto que permita sua identificação e contenha o número do CPF, tais como:</strong> Carteira de Identidade Nacional (CIN), Registro Geral (RG), ou, no caso de candidatos estrangeiros, o Registro Nacional Migratório (RNM), Registro Nacional de Estrangeiro (RNE).<br>Não são aceitas cópias ou fotos do documento no celular.
      </li>
      <li>Caneta esferográfica: Tinta azul ou preta, de corpo transparente;</li>
      <li>Altamente recomendável: Protocolo de inscrição impresso, emitido no ato da inscrição.</li>
  </ol>

  <p>O que não é permitido:</p>

  <ol>
      <li>Relógios inteligentes;</li>
      <li>Fones de ouvido;</li>
      <li>Calculadoras, Tablets e Notebooks;</li>
      <li>Gravadores e receptores ou qualquer outro dispositivo que possibilite comunicação ou acesso à informação;</li>
      <li>Boné, gorro, touca ou similares;</li>
  </ol>

  <p>Importante:<br>
      Celulares e aparelhos eletrônicos devem ficar desligados e guardados em embalagem própria fornecida pelos fiscais.
  </p>

  <p>Atenciosamente,<br>
      Comissão Organizadora<br>
      <strong>E. M. Dr. Leandro Franceschini - Prefeitura Muninicipal de Sumaré</strong>
  </p>
  @include('partials.emails.footer')
