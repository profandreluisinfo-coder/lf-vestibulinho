<style>
    @media print {
        @page {
            size: A4;
            margin: 1.5cm 1.5cm !important;
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
    }

    body {
        background-color: #fff;
    }

    .section-main-title {
      text-align: center
    }

    .section-main-title,
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
        width: 175px;
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

    <!-- DADOS DO CANDIDATO -->
    <div class="no-break">
        <div class="section-main-title">Vestibulinho LF {{ $process?->year }} - Comprovante de Inscrição</div>

        <table>
          <thead>
                <tr>
                    <th colspan="2">Inscrição</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Número:</th>
                    <td>{{ $user->inscription->id }}</td>
                </tr>
                <tr>
                    <th>Data/Hora:</th>
                    <td>{{ $user?->inscription?->created_at?->format('d/m/Y H:i:s') ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Dados do Candidato</div>

        <table>
            <thead>
                <tr>
                    <th colspan="2">Identificação</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Nome completo:</th>
                    <td>{{ $displayName }}</td>
                </tr>
                <tr>
                    <th>CPF:</th>
                    <td>{{ $user->cpf }}</td>
                </tr>
                <tr>
                    <th>Nascimento:</th>
                    <td>{{ $user->birth }}</td>
                </tr>
                <tr>
                    <th>Gênero:</th>
                    <td>{{ $user->gender }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th colspan="2">Documentos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Nacionalidade:</th>
                    <td>{{ $user->nationality }}</td>
                </tr>
                <tr>
                    <th>Documento:</th>
                    <td>{{ $user->document->type }}<br>Nº: {{ $user->document->number }}</td>
                </tr>
                <tr>
                    <th>Certidão de Nascimento:</th>
                    <td>
                        Nº: {{ $user->certificate->number }}
                        @if ($user->certificate->type !== '1')
                            | Folha: {{ $user?->certificate?->fls }} | Livro: {{ $user?->certificate?->book }}
                            | Município: {{ $user?->certificate?->city }}
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th colspan="2">Contato</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>E-mail:</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Telefone:</th>
                    <td>{{ $user->phone }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section-title">Endereço</div>

    <table>
        <tbody>
            <tr>
                <th>CEP:</th>
                <td>{{ $user->zip }}</td>
            </tr>
            <tr>
                <th>Endereço:</th>
                <td>{{ $user->street }}, {{ $user->home }}
                    {{ $user?->complement ? ' - ' . $user?->complement : '' }}</td>
            </tr>
            <tr>
                <th>Bairro:</th>
                <td>{{ $user->burgh }}</td>
            </tr>
            <tr>
                <th>Cidade/Estado:</th>
                <td>{{ $user->city }}/{{ $user->state }}</td>
            </tr>
        </tbody>
    </table>

    <div class="no-break">
        <div class="section-title">Informações Acadêmicas</div>
        <table>
            <thead>
                <tr>
                    <th colspan="2">Escolaridade</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Escola de Origem:</th>
                    <td>{{ $user->academic->school }} - ({{ $user->academic->city }}/{{ $user->academic->state }})</td>
                </tr>
                <tr>
                    <th>RA:</th>
                    <td>{{ $user->academic->ra }}</td>
                </tr>
                <tr>
                    <th>Ano de conclusão:</th>
                    <td>{{ $user->academic->year }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- FILIAÇÃO -->
    <div class="no-break">
        <div class="section-title">Filiação</div>

        <table>
            <thead>
                <tr>
                    <th colspan="2">Filiação/Responsável</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Mãe:</th>
                    <td>{{ $user->mother->name }}
                        {{ $user?->mother?->phone ? ' | ' . $user?->mother?->phone : '' }}</td>
                </tr>

                @if ($user?->father)
                    <tr>
                        <th>Pai:</th>
                        <td>{{ $user?->father?->name }}
                            {{ $user?->father?->phone ? ' | ' . $user?->father?->phone : '' }}</td>
                    </tr>
                @endif

                @if ($user?->guardian)
                    <tr>
                        <th>Responsável legal:</th>
                        <td>{{ $user?->guardian?->name }}
                            {{ $user?->guardian?->phone ? ' | ' . $user?->guardian?->phone : '' }}</td>
                    </tr>
                    <tr>
                        <th>Parentesco:</th>
                        <td>{{ $user?->guardian?->degree }}
                            ({{ $user?->guardian?->kinship ? $user?->guardian?->kinship : '' }})
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
            <thead>
                <tr>
                    <th colspan="2">Educação Especial</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Portador de necessidades especiais?</th>
                    <td>{{ $user?->pne ? 'SIM' : 'NÃO' }}</td>
                </tr>
                @if ($user?->pne)
                    <tr>
                        <th>Descrição:</th>
                        <td>
                            {{ $user?->pne?->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>Necessita de recursos de acessibilidade para a realização da prova?</th>
                        <td>
                            {{ $user?->pne?->support ? 'SIM' : 'NÃO' }}
                        </td>
                    </tr>
                    @if ($user?->pne?->support)
                        <tr>
                            <th>Descrição dos recursos de acessibilidade:</th>
                            <td>
                                {{ $user?->pne?->support }}
                            </td>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th colspan="2">Programas Sociais</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Bolsa Família?</th>
                    <td>
                        {{ $user?->nis ? 'SIM' : 'NÃO' }}
                    </td>
                </tr>
                @if ($user?->nis)
                    <tr>
                        <th>NIS (Número de Identificação Social):</th>
                        <td>
                            {{ $user?->nis }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th colspan="2">Saúde</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Problemas de saúde/alergias?</th>
                    <td>{{ $user?->health_issue ? 'SIM' : 'NÃO' }}</td>
                </tr>
                @if ($user?->health_issue)
                    <tr>
                        <th>Descrição:</th>
                        <td>{{ $user?->health_issue }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
