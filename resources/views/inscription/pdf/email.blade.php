<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>
        Vestibulinho LF {{ $process->year }} | Ficha de Inscrição
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
    <div class="watermark">Vestibulinho LF {{ $process->year }}</div>

    <div class="card">
        <div class="header-info">
            <span style="font-size: 16px; text-transform:uppercase; font-weight: 600">Vestibulinho LF
                {{ $process->year }}
        </div>

        <div class="header-info">
            <div><strong>Nº da Inscrição:</strong> {{ $inscription->id }}</div>
            <div><strong>Data:</strong> {{ $inscription->created_at->format('d/m/Y') }}</div>
        </div>

        <div class="card-body">

            <!-- IDENTIFICAÇÃO -->
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Identificação do Candidato</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>CPF</th>
                        <td>{{ $user->cpf }}</td>
                    </tr>
                    <tr>
                        <th>Nome completo</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Data de nascimento</th>
                        <td>{{ $user->birth }}</td>
                    </tr>
                    <tr>
                        <th>Gênero</th>
                        <td>{{ $user->gender }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- DOCUMENTOS -->
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Documentos Pessoais</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Nacionalidade</th>
                        <td>{{ $user->nationality}}</td>
                    </tr>
                    <tr>
                        <th>Documento</th>
                        <td>{{ $user->document->type }} - Nº {{ $user->document->number }} - Expedição: {{ $user->document->expedition->format('d/m/Y') }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- CERTIDÃO -->
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Certidão de Nascimento</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Nº Certidão</th>
                        <td>{{ $user->certificate->number }}</td>
                    </tr>

                    @if ($user->certificate->type != 1)
                    
                    <tr>
                        <th>Detalhes</th>
                        <td>
                            Folha: {{ $user->certificate->fls }},
                            Livro: {{ $user->certificate->book }},
                            Município: {{ $user->certificate->municipality }}
                        </td>
                    </tr>
                    
                    @endif

                </tbody>
            </table>

            <!-- CONTATO -->
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Contato</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>E-mail</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Telefone</th>
                        <td>{{ $user->phone }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- ENDEREÇO -->
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Endereço</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>CEP</th>
                        <td>{{ $user->zip }}</td>
                    </tr>
                    <tr>
                        <th>Endereço</th>
                        <td>{{ $user->street }}, {{ $user->home }}
                            @if ($user->complement)
                                ({{ $user->complement }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Bairro</th>
                        <td>{{ $user->burgh }}</td>
                    </tr>
                    <tr>
                        <th>Cidade/Estado</th>
                        <td>{{ $user->city }}/{{ $user->state }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- ESCOLARIDADE -->
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Escolaridade</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Escola de Origem</th>
                        <td>{{ $user->academic->name }}</td>
                    </tr>
                    <tr>
                        <th>RA</th>
                        <td>{{ $user->academic->ra }}</td>
                    </tr>
                    <tr>
                        <th>Localização</th>
                        <td>{{ $user->academic->city }}/{{ $user->academic->state }}</td>
                    </tr>
                    <tr>
                        <th>Ano de conclusão</th>
                        <td>{{ $user->academic->year }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- FILIAÇÃO -->
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Filiação / Responsável</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Mãe</th>
                        <td>{{ $user->mother->name }}
                            @if ($user->mother->phone)
                                | Telefone: {{ $user->mother->phone }}
                            @endif
                        </td>
                    </tr>
                    @if ($user->father)
                        <tr>
                            <th>Pai</th>
                            <td>{{ $user->father->name }}
                                @if ($user->father->phone)
                                    | Telefone: {{ $user->father->phone }}
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if ($user->guardian)
                        <tr>
                            <th>Responsável legal</th>
                            <td>{{ $user->guardian->name }}</td>
                        </tr>
                        <tr>
                            <th>Parentesco</th>
                            <td>{{ $user->guardian->degree }}
                                @if ($user->guardian->kinship)
                                    ({{ $user->guardian->kinship }})
                                @endif
                                | Telefone: {{ $user->guardian->phone }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th>E-mail dos responsáveis</th>
                        <td>{{ $user->parent_email->email }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- EDUCAÇÃO ESPECIAL -->
            @if ($user->pne)
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Educação Especial</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Necessita de atendimento especial</th>
                        <td>
                            SIM - {{ $user->pne->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>Necessita de recursos especiais para realização da prova</th>
                        <td>
                            @if ($user->pne->support)
                                SIM - {{ $user->pne->support }}
                            @else
                                NÃO
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif

            <!-- PROGRAMAS SOCIAIS -->
            @if ($user->social_program)
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Programas Sociais</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Bolsa Família</th>
                        <td>SIM - NIS: {{ $user->nis }}</td>
                    </tr>
                </tbody>
            </table>
            @endif

            <!-- SAÚDE -->
            @if ($user->health)
            <table class="table no-break">
                <thead>
                    <tr>
                        <th colspan="2">Saúde</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Problemas de saúde/alergias</th>
                        <td>SIM - {{ $user->health_issue }}</td>
                    </tr>
                </tbody>
            </table>
            @endif

        </div>

        <div class="card-footer">
            <p>E. M. Dr. Leandro Franceschini | Vestibulinho LF {{ $process->year }}</p>
        </div>
    </div>
</body>

</html>