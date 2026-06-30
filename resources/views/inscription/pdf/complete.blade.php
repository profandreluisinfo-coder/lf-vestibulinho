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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1rem;
      font-size: 11pt;
    }

    table thead th {
      background: #e9f7ef;
      color: #146c43;
      font-weight: 600;
      text-align: left;
      padding: 6px 8px;
      border-bottom: 2px solid #c9e6d3;
    }

    table th {
      width: 250px;
      background-color: #f8f9fa;
      text-align: left;
      padding: 6px 8px;
      vertical-align: top;
      font-weight: 600;
      border: 1px solid #dee2e6;
      color: #333;
    }

    table td {
      text-align: left;
      padding: 6px 8px;
      vertical-align: top;
      border: 1px solid #dee2e6;
      word-break: break-word;
    }

    .highlight {
      background-color: #fff3cd;
      border-left: 4px solid #ffecb5;
      padding: 8px 10px;
      margin-top: 8px;
      font-size: 10.5pt;
    }

    .alert-danger {
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
  <div class="watermark">Vestibulinho LF {{ $process?->year }}</div>

  <div class="card">
    <div class="header-info" style="text-align: center;">
      <span style="font-size: 16px; text-transform:uppercase; font-weight: 600;">Vestibulinho LF {{ $process?->year }} - Comprovante de Inscrição
    </div>

    <div class="card-body">

      <!-- DADOS DO CANDIDATO -->
      <div class="no-break">
        <div class="section-title">Inscrição</div>
        
        <table>
          <tbody>
            <tr><th>Número:</th><td>{{ $user->inscription->id }}</td></tr>
            <tr><th>Data/Hora:</th><td>{{ $user?->inscription?->created_at?->format('d/m/Y H:i:s') ?? '-' }}</td></tr>
          </tbody>
        </table>

        <div class="section-title">Dados do Candidato</div>

        <table>
          <thead><tr><th colspan="2">Identificação</th></tr></thead>
          <tbody>
            <tr><th>Nome completo:</th><td>{{ $displayName }}</td></tr>
            <tr><th>CPF:</th><td>{{ $user->cpf }}</td></tr>
            <tr><th>Data de nascimento:</th><td>{{ $user->birth }}</td></tr>
            <tr><th>Gênero:</th><td>{{ $user->gender }}</td></tr>
          </tbody>
        </table>

        <table>
          <thead><tr><th colspan="2">Documentos</th></tr></thead>
          <tbody>
            <tr><th>Nacionalidade:</th><td>{{ $user->nationality }}</td></tr>
            <tr><th>Documento:</th><td>{{ $user->document->type }} / Nº {{ $user->document->number }}</td></tr>
            <tr>
              <th>Certidão:</th>
              <td>
                  Nº {{ $user->certificate->number }}
                  @if ($user->certificate->type !== "1")
                    Folha {{ $user?->certificate?->fls }} | Livro {{ $user?->certificate?->book }} | Município {{ $user?->certificate?->city }}
                  @endif
              </td>
            </tr>
          </tbody>
        </table>

        <table>
          <thead><tr><th colspan="2">Contato</th></tr></thead>
          <tbody>
            <tr><th>E-mail:</th><td>{{ $user->email }}</td></tr>
            <tr><th>Telefone:</th><td>{{ $user->phone }}</td></tr>
          </tbody>
        </table>

      </div>
      
      <div class="no-break">
        <div class="section-title">Endereço</div>
        <table>
        <tbody>
          <tr><th>CEP:</th><td>{{ $user->zip }}</td></tr>
          <tr><th>Endereço:</th>
            <td>{{ $user->street }}, {{ $user->home }} {{ $user?->complement }}</td>
          </tr>
          <tr><th>Bairro:</th><td>{{ $user->burgh }}</td></tr>
          <tr><th>Cidade/Estado:</th><td>{{ $user->city }}/{{ $user->state }}</td></tr>
        </tbody>
      </table>

        <div class="section-title">Informações Acadêmicas</div>

        <table>
          <thead><tr><th colspan="2">Escolaridade</th></tr></thead>
          <tbody>
            <tr><th>Escola de Origem:</th><td>{{ $user->academic->school }}</td></tr>
            <tr><th>RA:</th><td>{{ $user->academic->ra }}</td></tr>
            <tr><th>Localização:</th><td>{{ $user->academic->city }}/{{ $user->academic->state }}</td></tr>
            <tr><th>Ano de conclusão:</th><td>{{ $user->academic->year }}</td></tr>
          </tbody>
        </table>
      </div>

      <!-- FILIAÇÃO -->
      <div class="no-break">
        <div class="section-title">Filiação</div>

        <table>
          <thead><tr><th colspan="2">Filiação/Responsável</th></tr></thead>
          <tbody>
            <tr>
              <th>Mãe:</th>
              <td>{{ $user->mother->name }}@if($user?->mother?->phone) | Telefone: {{ $user?->mother?->phone }}@endif</td>
            </tr>

            @if ($user?->father)
            <tr>
              <th>Pai:</th>
              <td>{{ $user?->father?->name }}
                @if($user?->father?->phone) 
                  | Telefone: {{ $user?->father?->phone }}
                @endif
              </td>
            </tr>
            @endif

            @if ($user?->guardian)
              <tr>
                <th>Responsável legal:</th>
                <td>{{ $user?->guardian?->name }}</td>
              </tr>
              <tr>
                <th>Parentesco:</th>
                <td>{{ $user?->guardian?->degree }}
                  @if($user?->guardian?->kinship) 
                    ({{ $user?->guardian?->kinship }})
                  @endif
                  @if($user?->guardian?->phone) 
                    | Telefone: {{ $user?->guardian?->phone }}
                  @endif
                </td>
              </tr>
            @endif
            <tr>
              <th>E-mail dos responsáveis:</th>
              <td>{{ $user->parent_email->address }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- INFORMAÇÕES COMPLEMENTARES -->
      <div class="no-break">
        <div class="section-title">Informações Complementares</div>

        <table>
          <thead><tr><th colspan="2">Educação Especial</th></tr></thead>
          <tbody>
            <tr>
              <th>Necessita de atendimento especial?</th>
                <td>
                    @if ($user?->pne)
                        SIM - {{ $user?->pne?->description }}

                        @if ($user?->pne?->support)
                            <br>
                            Necessita de recursos de acessibilidade para a realização da prova?
                            SIM - {{ $user?->pne?->support }}
                        @endif
                    @else
                        NÃO
                    @endif
                </td>
            </tr>
          </tbody>
        </table>

        <table>
          <thead><tr><th colspan="2">Programas Sociais</th></tr></thead>
          <tbody>
            <tr>
                <th>Bolsa Família?</th>
                <td>
                    {{ $user?->nis
                        ? 'SIM - NIS: ' . $user?->nis
                        : 'NÃO'
                    }}
                </td>
            </tr>
          </tbody>
        </table>

        <table>
          <thead><tr><th colspan="2">Saúde</th></tr></thead>
          <tbody>
            <tr><th>Problemas de saúde/alergias?</th><td>{{ $user?->health_issue ? 'SIM - ' . $user?->health_issue : 'NÃO' }}</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      <p>E. M. Dr. Leandro Franceschini | Vestibulinho LF {{ $process?->year }}</p>
    </div>
  </div>
</body>