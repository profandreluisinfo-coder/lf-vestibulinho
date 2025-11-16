<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Convocação para Matrícula</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 13px;
      color: #000;
      line-height: 1.6;
    }

    h1,
    h2,
    h3 {
      text-align: center;
      margin-bottom: 10px;
    }

    .info {
      margin-bottom: 20px;
    }

    .info p {
      margin: 2px 0;
    }

    .section-title {
      background-color: #eee;
      padding: 5px;
      font-weight: bold;
      margin-top: 20px;
    }

    .docs-list {
      margin-top: 10px;
    }

    .docs-list li {
      margin-bottom: 5px;
    }
  </style>
</head>

<body>

  <h1>Processo Seletivo {{ $calendar?->year }} - Convocação para Matrícula</h1>

  <div class="info">
    <p><strong>Nome:</strong> {{ $user->social_name ?? $user->name }}</p>
    <p><strong>CPF:</strong> {{ $user->cpf }}</p>
    {{-- <p><strong>Curso Pretendido:</strong> {{ $user->inscription->course->description }}</p> --}}
  </div>

  <div class="section-title">Detalhes da Chamada</div>
  <div class="info">
    <p><strong>Data da Convocação:</strong> {{ \Carbon\Carbon::parse($call->date)->format('d/m/Y') }}</p>
    <p><strong>Hora:</strong> {{ \Carbon\Carbon::parse($call->time)->format('H:i') }}</p>
    <p><strong>Número da Chamada:</strong> {{ $call->call_number }}</p>
  </div>

  <div class="section-title">Local de Matrícula</div>
  <div class="info">
    <p><strong>Endereço:</strong> R. Geraldo de Souza, 221 - Jardim São Carlos, Sumaré - SP, 13170-232</p>
    <p><strong>Telefone:</strong> (19) 3873-2605</p>
    <p><strong>Horário de Funcionamento:</strong> 14:00 às 23:00</p>
  </div>

  <div class="section-title">INFORMAÇÕES IMPORTANTES!</div>
  <p>A falta de documentação ou não comparecimento na data e horário estabelecido acarretará na perda da vaga, portanto não se esqueça de comparecer no dia e horário indicado portando todos os documentos previstos no item <strong>7.4</strong> do edital. </p>
  <ol class="docs-list">
    <li>Declaração de Conclusão do Ensino Fundamental ou Histórico Escolar do Ensino Fundamental (Original e 01 cópia);</li>
    <li>01 foto 3x4;</li>
    <li>Original e 01 cópia do documento de identidade (RG/CIN ou RNE para estrangeiro) atualizado e com foto que identifique o portador;</li>
    <li>Original e 01 cópia do CPF;</li>
    <li>Original e 01 cópia da certidão de nascimento;</li>
    <li>Carteira de vacinação (Original e 01 cópia);</li>
    <li>Comprovante de residência no município de Sumaré com menos de 60 dias de emissão, em nome dos pais ou do responsável legal pelo (a) candidato (a); (Original e 01 cópia)</li>
  </ol>

  <!-- AVISO IMPORTANTE -->
  <div style="page-break-inside: avoid;">
    <div
      style="border: 1px solid #ffc107; background-color: #fff3cd; padding: 15px; margin-top: 30px; border-radius: 5px;">
      <div style="display: flex; align-items: flex-start;">
        <div style="font-size: 24px; color: #856404; margin-right: 15px;">&#9888;</div>
        <div style="color: #856404; font-size: 13px; text-align: justify;">
          <strong>Atenção:</strong> a escolha do curso realizada no momento da inscrição tem caráter indicativo, sendo utilizada apenas para fins estatísticos e de planejamento interno. A matrícula dos candidatos aprovados será realizada conforme a disponibilidade de vagas, podendo o candidato optar por qualquer curso que ainda possua vagas disponíveis, independentemente da opção indicada na inscrição.
        </div>
      </div>
    </div>
  </div>
  <!-- FIM DO AVISO -->

  <p>Qualquer dúvida, estamos à sua disposição.<br>
    <strong>Atendimento:</strong> (19) 3873-2605</p>

  <p class="signature">EM Dr Leandro Franceschini</p>

</body>

</html>
