  <p>Prezado(a) candidato(a), {{ $nome }}!</p>

  <p>Parabéns por sua classificação no Processo Seletivo {{ $process?->year }} da <strong>ESCOLA MUNICIPAL DR. LEANDRO
          FRANCESCHINI</strong>! É com satisfação que o(a) convocamos para a efetivação de sua matrícula no ENSINO
      TÉCNICO DE NÍVEL MÉDIO INTEGRADO para o ano letivo de {{ $process?->year }}.</p>

  <p>Nos termos do Edital do Processo Seletivo Público {{ $process?->year }}, os(as) candidatos(as) convocados(as) deverão comparecer
      para a matrícula na data, horário e local informados abaixo:</p>

  <p>
      <strong>Data:</strong> {{ $data }}<br>
      <strong>Horário:</strong> {{ $hora }} às 19:00h<br>
      <strong>Local:</strong> Escola Municipal Dr. Leandro Franceschini<br>
      <strong>Endereço:</strong> R. Geraldo de Souza, 157/221 - Jardim São Carlos, Sumaré/SP
  </p>

  <p>DOCUMENTAÇÃO OBRIGATÓRIA</p>

  <p>No ato da matrícula, o(a) candidato(a) deverá apresentar os seguintes documentos:</p>

  <ol>
      <li>Declaração de Conclusão ou Histórico Escolar do Ensino Fundamental - original e 1 cópia;</li>
      <li>01 foto 3x4 recente;</li>
      <li>Documento de identidade atualizado, com foto que identifique o portador (RG, CIN ou RNM/RNE para estrangeiros)
          - original e 1 cópia;</li>
      <li>Cadastro de Pessoa Física (CPF) - original e 1 cópia;</li>
      <li>Certidão de Nascimento - original e 1 cópia;</li>
      <li>Comprovante de residência no Município de Sumaré, emitido há no máximo 60 dias, em nome dos pais ou do
          responsável legal do(a) candidato(a) - original e 1 cópia;</li>
      <li>Carteira de vacinação atualizada - 1 cópia;</li>
  </ol>

  <p>
      <strong>ORIENTAÇÕES IMPORTANTES!</strong>
  </p>

  <ul>
      <li>A matrícula será realizada de acordo com a classificação geral dos(as) candidatos(as) aprovados(as) e a
          disponibilidade de vagas.</li>
      <li>O(a) candidato(a) convocado(a) que não comparecer para efetivar a matrícula no período estabelecido perderá o
          direito à vaga, que será destinada ao(à) próximo(a) candidato(a) classificado(a).</li>
      <li>No caso de candidato(a) menor de idade, é obrigatória a presença de seu representante legal (pai, mãe, tutor
          ou curador) no ato da matrícula.</li>
      <li>Quando acompanhado(a) pelos pais, será necessária apenas a apresentação de documento oficial de identificação.
      </li>
      <li>Na ausência dos pais, o representante deverá apresentar procuração simples com firma reconhecida em cartório.
      </li>
      <li>Nos casos de tutela, guarda ou curatela, deverá ser apresentado o respectivo documento judicial comprobatório.
      </li>
      <li>Para os casos em que o imóvel seja alugado e o comprovante de residência não esteja em nome dos pais ou do
          responsável legal do(a) candidato(a), serão aceitos:<br>
          I - Contrato de locação vigente acompanhado de comprovante de residência do imóvel. ou<br>
          II - Declaração de residência assinada pelo proprietário ou locatário do imóvel, acompanhada de cópia do
          documento de identificação do declarante e de comprovante de residência emitido há no máximo 60 (sessenta)
          dias.</li>
      <li>Havendo ausência de documentação obrigatória ou apresentação de documentos em desconformidade com o edital, a
          matrícula poderá ficar suspensa para regularização, conforme os procedimentos previstos no edital.</li>
  </ul>

  <p>Acompanhe as publicações oficiais através do site da escola: <a
          href="https://vestibulinho.leandrofranceschini.com.br">https://vestibulinho.leandrofranceschini.com.br</a> e na
      Área do Candidato.</p>

  <p>Atenciosamente,<br>
      <strong>Comissão Organizadora do Processo Seletivo Público 2027<br>
          E. M. Dr. Leandro Franceschini - Prefeitura Muninicipal de Sumaré</strong>
  </p>
  @include('partials.emails.footer')
