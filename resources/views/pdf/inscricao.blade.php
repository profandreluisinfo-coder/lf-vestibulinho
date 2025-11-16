<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>
    {{ config('app.name') . ' ' . $calendar->year . ' | Ficha de Inscrição - ' . ($user->social_name ?? $user->name) . ' - ' . $user->cpf }}
  </title>

  <style>
    @media print {
      @page {
        size: A4;
        margin: 2.5cm 2cm !important;
      }

      body {
        font-family: 'Georgia', serif;
        font-size: 11pt;
        line-height: 1.5;
        color: #333;
      }

      .no-break {
        page-break-inside: avoid !important;
        break-inside: avoid-page !important;
      }

      .section-title {
        page-break-after: avoid !important;
      }

      .card-footer {
        page-break-before: avoid !important;
      }
    }

    body {
      background-color: #fff;
    }

    .card {
      max-width: 800px;
      margin: 0 auto;
      border: 1px solid #e0e0e0;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .card-header {
      background: linear-gradient(90deg, #198754, #157347);
      color: #fff;
      padding: 20px;
      text-align: center;
      font-size: 18px;
      font-weight: 600;
      border-bottom: 4px solid #82c49b;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .header-info {
      display: flex;
      justify-content: space-between;
      padding: 10px 25px;
      font-size: 14px;
      color: #555;
      background-color: #f8f9fa;
      border-bottom: 1px solid #e0e0e0;
    }

    .card-body {
      padding: 20px 25px;
    }

    .section-title {
      font-size: 15px;
      color: #198754;
      margin: 20px 0 10px 0;
      font-weight: 600;
      border-bottom: 2px solid #198754;
      padding-bottom: 4px;
      text-transform: uppercase;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1rem;
      font-size: 11pt;
    }

    .table thead th {
      background: #e9f7ef;
      color: #146c43;
      font-weight: 600;
      text-align: left;
      padding: 6px 8px;
      border-bottom: 2px solid #c9e6d3;
    }

    .table th {
      width: 250px;
      background-color: #f8f9fa;
      text-align: left;
      padding: 6px 8px;
      vertical-align: top;
      font-weight: 600;
      border: 1px solid #dee2e6;
      color: #333;
    }

    .table td {
      text-align: left;
      padding: 6px 8px;
      vertical-align: top;
      border: 1px solid #dee2e6;
      word-break: break-word;
    }

    .alert {
      background-color: #ffe6e6;
      border: 1px solid #f5c2c7;
      padding: 10px;
      font-size: 10.5pt;
      color: #842029;
      border-radius: 4px;
      margin-top: 8px;
    }

    .card-footer {
      background-color: #f8f9fa;
      padding: 10px;
      text-align: center;
      font-size: 10.5pt;
      color: #6c757d;
      border-top: 1px solid #e0e0e0;
    }

    .watermark {
      position: fixed;
      opacity: 0.07;
      font-size: 90px;
      color: #198754;
      transform: translate(-50%, -50%) rotate(-45deg);
      z-index: -1;
      top: 50%;
      left: 50%;
      font-weight: bold;
      pointer-events: none;
    }
  </style>
</head>

<body>
  <div class="watermark">{{ config('app.name') }} {{ $calendar->year }}</div>

  <div class="card">
    <div class="header-info">
      <span style="font-size: 16px; text-transform:uppercase; font-weight: 600">{{ config('app.name') }} {{ $calendar->year }} - Ficha de Inscrição
    </div>

    <div class="header-info">
      <div><strong>Nº da Inscrição:</strong> {{ $inscription->id }}</div>
      <div><strong>Data:</strong> {{ \Carbon\Carbon::parse($inscription->created_at)->format('d/m/Y') }}</div>
    </div>

    <div class="card-body">

      <!-- IDENTIFICAÇÃO -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Identificação do Candidato</th></tr></thead>
        <tbody>
          <tr><th>CPF</th><td>{{ $user->cpf }}</td></tr>
          <tr><th>Nome completo</th><td>{{ $user->social_name ?? $user->name }}</td></tr>
          <tr><th>Data de nascimento</th><td>{{ $user->user_detail->birth }}</td></tr>
          <tr><th>Gênero</th><td>{{ $user->gender }}</td></tr>
        </tbody>
      </table>

      <!-- DOCUMENTOS -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Documentos Pessoais</th></tr></thead>
        <tbody>
          <tr><th>Nacionalidade</th><td>{{ $user->user_detail->nationality }}</td></tr>
          <tr><th>Documento</th><td>{{ $user->user_detail->doc_type }} - Nº {{ $user->user_detail->doc_number }}</td></tr>
        </tbody>
      </table>

      <!-- CERTIDÃO -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Certidão de Nascimento</th></tr></thead>
        <tbody>
          @if ($user->user_detail->new_number)
            <tr><th>Nº Certidão</th><td>{{ $user->user_detail->new_number }}</td></tr>
          @else
            <tr><th>Detalhes</th>
              <td>Folha {{ $user->user_detail->fls }}, Livro {{ $user->user_detail->book }}, Nº {{ $user->user_detail->old_number }}, {{ $user->user_detail->municipality }}</td></tr>
          @endif
        </tbody>
      </table>

      <!-- CONTATO -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Contato</th></tr></thead>
        <tbody>
          <tr><th>E-mail</th><td>{{ $user->email }}</td></tr>
          <tr><th>Telefone</th><td>{{ $user->user_detail->phone }}</td></tr>
        </tbody>
      </table>

      <!-- ENDEREÇO -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Endereço</th></tr></thead>
        <tbody>
          <tr><th>CEP</th><td>{{ $user->user_detail->zip }}</td></tr>
          <tr><th>Endereço</th>
            <td>{{ $user->user_detail->street }}, {{ $user->user_detail->number }}
              @if ($user->user_detail->complement)
                ({{ $user->user_detail->complement }})
              @endif
            </td></tr>
          <tr><th>Bairro</th><td>{{ $user->user_detail->burgh }}</td></tr>
          <tr><th>Cidade/Estado</th><td>{{ $user->user_detail->city }}/{{ $user->user_detail->state }}</td></tr>
        </tbody>
      </table>

      <!-- ESCOLARIDADE -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Escolaridade</th></tr></thead>
        <tbody>
          <tr><th>Escola de Origem</th><td>{{ $user->user_detail->school_name }}</td></tr>
          <tr><th>RA</th><td>{{ $user->user_detail->school_ra }}</td></tr>
          <tr><th>Localização</th><td>{{ $user->user_detail->school_city }}/{{ $user->user_detail->school_state }}</td></tr>
          <tr><th>Ano de conclusão</th><td>{{ $user->user_detail->school_year }}</td></tr>
        </tbody>
      </table>

      <!-- FILIAÇÃO -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Filiação / Responsável</th></tr></thead>
        <tbody>
          <tr><th>Mãe</th>
              <td>{{ $user->user_detail->mother }}
                @if ($user->user_detail->mother_phone) | Telefone: {{ $user->user_detail->mother_phone }} @endif
              </td></tr>
          @if ($user->user_detail->father)
            <tr><th>Pai</th>
              <td>{{ $user->user_detail->father }}
                @if ($user->user_detail->father_phone) | Telefone: {{ $user->user_detail->father_phone }} @endif
              </td></tr>
          @endif
          @if ($user->user_detail->responsible)
            <tr><th>Responsável legal</th><td>{{ $user->user_detail->responsible }}</td></tr>
            <tr><th>Parentesco</th>
              <td>{{ $user->user_detail->degree }}
                @if ($user->user_detail->kinship) ({{ $user->user_detail->kinship }}) @endif
                | Telefone: {{ $user->user_detail->responsible_phone }}</td></tr>
          @endif
          <tr><th>E-mail dos responsáveis</th><td>{{ $user->user_detail->parents_email }}</td></tr>
        </tbody>
      </table>

      <!-- EDUCAÇÃO ESPECIAL -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Educação Especial</th></tr></thead>
        <tbody>
          <tr>
            <th>Necessita de atendimento especial?</th>
            <td>
              {{ $user->user_detail->accessibility ? 'Sim - ' . $user->user_detail->accessibility : 'Não' }}
              @if ($user->user_detail->accessibility)
              <div class="alert">
                <strong>Atenção:</strong> O(a) candidato(a) portador(a) de necessidades especiais deverá informar, no período de inscrição, qual a sua necessidade específica, enviando e-mail com atestado médico para <strong>emdrleandrofranceschini@educacaosumare.com.br</strong>, conforme o item 4.8 do edital.
              </div>
              @endif
            </td>
          </tr>
        </tbody>
      </table>

      <!-- PROGRAMAS SOCIAIS -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Programas Sociais</th></tr></thead>
        <tbody>
          <tr><th>Bolsa Família?</th><td>{{ $user->user_detail->nis ? 'Sim - NIS: ' . $user->user_detail->nis : 'Não' }}</td></tr>
        </tbody>
      </table>

      <!-- SAÚDE -->
      <table class="table no-break">
        <thead><tr><th colspan="2">Saúde</th></tr></thead>
        <tbody>
          <tr><th>Problemas de saúde/alergias?</th><td>{{ $user->user_detail->health ? 'Sim - ' . $user->user_detail->health : 'Não' }}</td></tr>
        </tbody>
      </table>

    </div>

    <div class="card-footer">
      <p>E. M. Dr. Leandro Franceschini | {{ config('app.name') }} {{ $calendar->year }}</p>
    </div>
  </div>
</body>
</html>