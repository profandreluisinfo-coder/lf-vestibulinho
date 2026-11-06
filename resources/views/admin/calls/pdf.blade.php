@php
    $processYear = (int) ($process?->year ?? ($callList->process?->year ?? now()->year));
    $headerPath = public_path('assets/img/cab_ficha.png');
    $headerImage = is_file($headerPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($headerPath))
        : null;
@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Vestibulinho LF {{ $processYear }} - Ficha Cadastral do Estudante</title>
    <style>
        /* ===== Ficha cadastral — compatível com DomPDF ===== */

        /* Margens da folha A4 (área útil = 197 mm de largura) */
        @page { margin: 6mm 6.5mm 5mm 6.5mm; }

        @font-face {
            font-family: 'Caladea';
            font-style: normal;
            font-weight: normal;
            src: url('{{ str_replace('\\', '/', public_path('assets/fonts/Caladea-Regular.ttf')) }}') format('truetype');
        }
        @font-face {
            font-family: 'Caladea';
            font-style: normal;
            font-weight: bold;
            src: url('{{ str_replace('\\', '/', public_path('assets/fonts/Caladea-Bold.ttf')) }}') format('truetype');
        }

        body {
            margin: 0;
            font-family: 'Caladea', 'Times New Roman', serif;
            font-size: 8pt;
            line-height: 0.927;   /* no DomPDF, 0,927 equivale a 1,172 do Word (ele multiplica pela altura própria da fonte) */
            color: #000;
        }

        .quebra { page-break-after: always; }

        .cabecalho img { display: block; width: 197mm; }

        /* ---------- Tabelas ---------- */
        .ficha {
            width: 197mm;
            border-collapse: collapse;
            border: 1.5pt solid #000;
            page-break-inside: avoid;
        }

        /* Espaço entre tabelas = parágrafo de 12 pt do original */
        .ficha + .ficha { margin-top: 5.35mm; }

        .ficha th,
        .ficha td {
            padding: 0 1.9mm;
            border: .5pt solid #000;
            vertical-align: middle;
            text-align: left;
        }

        .ficha th { font-weight: bold; }

        /* Linha-régua: só define a largura das colunas (DomPDF ignora <colgroup>) */
        .ficha tr.regua td {
            height: 0;
            padding: 0;
            border: 0;
            font-size: 0;
            line-height: 0;
        }

        /* Alturas das linhas (DomPDF ignora height na <tr>, por isso na célula) */
        tr.h579 th, tr.h579 td { height: 5.61mm; }
        tr.h662 th, tr.h662 td { height: 6.44mm; }
        tr.h1158 th, tr.h1158 td { height: 11.40mm; }
        tr.h711 th, tr.h711 td { height: 6.93mm; }
        tr.h732 th, tr.h732 td { height: 7.14mm; }
        tr.h649 th, tr.h649 td { height: 6.31mm; }
        tr.h485 th, tr.h485 td { height: 4.67mm; }
        tr.h700 th, tr.h700 td { height: 6.82mm; }
        tr.h559 th, tr.h559 td { height: 5.41mm; }
        tr.h492 th, tr.h492 td { height: 4.74mm; }
        tr.h474 th, tr.h474 td { height: 4.56mm; }
        tr.h651 th, tr.h651 td { height: 6.33mm; }

        /* Linha com entrelinha dupla (RA / RG / CPF) */
        tr.dupla th, tr.dupla td { height: 10.6mm; vertical-align: top; padding-top: 0.8mm; }

        /* ---------- Variações ---------- */
        .ficha .c { text-align: center; }
        .fs65 { font-size: 6.5pt; }
        .fs7  { font-size: 7pt; }
        .fs75 { font-size: 7.5pt; }
        .fs10 { font-size: 10pt; }

        .opcoes { font-weight: bold; }
        .op { white-space: nowrap; }

        /* Valores preenchidos pelo sistema (rótulo em negrito, dado normal) */
        .v { font-weight: normal; }

    </style>
</head>

<body>
    @foreach ($callListMembers as $call)
        @php
            $user = $call->result?->inscription?->user;
            $inscription = $call->result?->inscription;
            $lgbt = $user?->lgbt;
            $pne = $user?->pne;
            $academic = $user?->academic;
            $document = $user?->document;
            $certificate = $user?->certificate;
            $mother = $user?->mother;
            $father = $user?->father;
            $guardian = $user?->guardian;
            $parentEmail = $user?->parent_email;

            $certificateType = (string) $certificate?->getRawOriginal('type');
            $isForeign = (string) $user?->typeOfNationality() === '3';
            $hasAcceptedPne = $pne?->status === 'accepted';
            $needsAdaptation = $hasAcceptedPne && filled($pne?->support);
            $healthIssue = filled($user?->health_issue) ? $user->health_issue : null;
            $courseName = $call->course?->name ?: $inscription?->course?->name;
            $callDate = $call->date?->format('d/m/Y') ?? '';
            $birthDate = $user?->birth?->format('d/m/Y') ?? '';
        @endphp

        <div class="folha{{ $loop->last ? '' : ' quebra' }}">
            @if ($headerImage)
                <div class="cabecalho">
                    <img src="{{ $headerImage }}" alt="Cabeçalho da ficha de matrícula">
                </div>
            @endif

            <table class="ficha" aria-label="Dados do estudante">
                <tbody>
                    <tr class="regua">
                        <td style="width:82.57pt"></td>
                        <td style="width:30.04pt"></td>
                        <td style="width:42.48pt"></td>
                        <td style="width:58.78pt"></td>
                        <td style="width:42.94pt"></td>
                        <td style="width:46.38pt"></td>
                        <td style="width:50.29pt"></td>
                        <td style="width:45.09pt"></td>
                        <td style="width:1.90pt"></td>
                        <td style="width:45.34pt"></td>
                        <td style="width:35.44pt"></td>
                        <td style="width:77.17pt"></td>
                    </tr>
                    <tr class="h579">
                        <th colspan="2">NOME DO ESTUDANTE</th>
                        <td colspan="6">{{ $user?->name ?? '-' }}</td>
                        <th class="fs75" colspan="2">CONTATO</th>
                        <td colspan="2">{{ $user?->phone ?? '-' }}</td>
                    </tr>
                    <tr class="h662">
                        <th colspan="2">NOME SOCIAL/AFETIVO<br>(DECR. 55588/10)</th>
                        <td colspan="10">{{ $lgbt?->status === 'accepted' ? $lgbt->name : '-' }}</td>
                    </tr>
                    <tr class="h1158 dupla">
                        <th colspan="3">RA: <span class="v">{{ $academic?->ra ?? '-' }}</span></th>
                        <th colspan="4">RG/CIN: <span class="v">{{ $document?->number ?? '-' }}</span></th>
                        <th colspan="5">CPF: <span class="v">{{ $user?->cpf ?? '-' }}</span></th>
                    </tr>
                    <tr class="h662">
                        <th>CERTIDÃO DE<br>NASCIMENTO</th>
                        <th class="fs65">NOVA</th>
                        <td colspan="8">{{ $certificateType === '1' ? $certificate?->number ?? '-' : '-' }}</td>
                        <th class="fs75">DATA NASC.</th>
                        <td>{{ $birthDate ?: '-' }}</td>
                    </tr>
                    <tr class="h662">
                        <th>ANTIGA</th>
                        <th>FLS.</th>
                        <td>{{ $certificateType === '2' ? $certificate?->fls ?? '-' : '-' }}</td>
                        <th>LIVRO</th>
                        <td>{{ $certificateType === '2' ? $certificate?->book ?? '-' : '-' }}</td>
                        <th>NÚMERO</th>
                        <td>{{ $certificateType === '2' ? $certificate?->number ?? '-' : '-' }}</td>
                        <th class="fs75" colspan="2">DISTRITO</th>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="h711">
                        <th>SUBDISTRITO</th>
                        <td colspan="4"></td>
                        <th colspan="2">MUNICÍPIO</th>
                        <td colspan="5">{{ $certificateType === '2' ? $certificate?->city ?? '-' : '-' }}</td>
                    </tr>
                    <tr class="h579">
                        <th>NACIONALIDADE</th>
                        <td colspan="2">{{ $user?->nationality ?? '-' }}</td>
                        <th colspan="2">ESTRANGEIRO</th>
                        <td colspan="2">
                            @if ($isForeign)
                                <span>SIM</span>
                            @else
                                <span>NÃO</span>
                            @endif
                        </td>
                        <th class="fs7" colspan="2">RNE/RNM</th>
                        <td colspan="3">{{ $isForeign ? $document?->number ?? '-' : '-' }}</td>
                    </tr>
                    <tr class="h579">
                        <th>GÊNERO</th>
                        <td colspan="2">{{ $user?->gender ?? '-' }}</td>
                        <th colspan="2">BOLSA FAMÍLIA</th>
                        <td colspan="2">{{ filled($user?->nis) ? 'SIM' : ' NÃO'}}</td>
                        <th colspan="2">NIS</th>
                        <td colspan="3">{{ $user?->nis ? $user?->nis : '-' }}</td>
                    </tr>
                    <tr class="h732">
                        <th colspan="3">POSSUI ALGUMA DEFICIÊNCIA?</th>
                        <td class="opcoes" colspan="9"><span class="op">(&nbsp;&nbsp;&nbsp;) NÃO</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="op">(&nbsp;&nbsp;&nbsp;) SIM – ESPECIFIQUE:</span></td>
                    </tr>
                    <tr class="h732">
                        <th colspan="3">NECESSITA DE ADAPTAÇÃO PEDAGÓGICA?</th>
                        <td class="opcoes" colspan="9"><span class="op">(&nbsp;&nbsp;&nbsp;) NÃO</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="op">(&nbsp;&nbsp;&nbsp;) SIM – ESPECIFIQUE:</span></td>
                    </tr>
                    <tr class="h732">
                        <th colspan="3">TEM ALGUM PROBLEMA DE SAÚDE OU ALERGIA?</th>
                        <td class="opcoes" colspan="9"><span class="op">(&nbsp;&nbsp;&nbsp;) NÃO</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="op">(&nbsp;&nbsp;&nbsp;) SIM – ESPECIFIQUE:</span></td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Filiação e responsável">
                <tbody>
                    <tr class="regua">
                        <td style="width:84.26pt"></td>
                        <td style="width:309.31pt"></td>
                        <td style="width:49.05pt"></td>
                        <td style="width:20.00pt"></td>
                        <td style="width:95.80pt"></td>
                    </tr>
                    <tr class="h579">
                        <th>FILIAÇÃO 1</th>
                        <td></td>
                        <th class="fs75">CONTATO</th>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="h579">
                        <th>FILIAÇÃO 2</th>
                        <td></td>
                        <th class="fs75">CONTATO</th>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="h662">
                        <th>RESPONSÁVEL PELO ESTUDANTE</th>
                        <td></td>
                        <th class="fs75" colspan="2">PARENTESCO</th>
                        <td></td>
                    </tr>
                    <tr class="h579">
                        <th>E-MAIL</th>
                        <td></td>
                        <th class="fs75">CONTATO</th>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Endereço residencial">
                <tbody>
                    <tr class="regua">
                        <td style="width:83.50pt"></td>
                        <td style="width:42.45pt"></td>
                        <td style="width:198.61pt"></td>
                        <td style="width:36.40pt"></td>
                        <td style="width:43.75pt"></td>
                        <td style="width:36.10pt"></td>
                        <td style="width:117.60pt"></td>
                    </tr>
                    <tr class="h662">
                        <th>ENDEREÇO RESIDENCIAL</th>
                        <th>RUA/AV.</th>
                        <td colspan="5"></td>
                    </tr>
                    <tr class="h579">
                        <th>BAIRRO</th>
                        <td colspan="2"></td>
                        <th colspan="2">COMPLEMENTO</th>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="h579">
                        <th>CIDADE</th>
                        <td colspan="2"></td>
                        <th class="c">UF</th>
                        <td></td>
                        <th class="c">CEP</th>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Escola de conclusão do Ensino Fundamental">
                <tbody>
                    <tr class="regua">
                        <td style="width:84.31pt"></td>
                        <td style="width:158.86pt"></td>
                        <td style="width:33.50pt"></td>
                        <td style="width:77.35pt"></td>
                        <td style="width:108.51pt"></td>
                        <td style="width:95.90pt"></td>
                    </tr>
                    <tr class="h649">
                        <th>ESCOLA DE CONCLUSÃO E.F.</th>
                        <td colspan="5"></td>
                    </tr>
                    <tr class="h649">
                        <th>CIDADE</th>
                        <td></td>
                        <th class="c">UF</th>
                        <td></td>
                        <th class="c">ANO DE CONCLUSÃO</th>
                        <td></td>
                    </tr>
                    <tr class="h649">
                        <td class="opcoes" colspan="6">ESTUDANTE JÁ CURSOU 1º ANO EM OUTRA INSTITUIÇÃO DE ENSINO? <span class="op">(&nbsp;&nbsp;&nbsp;) NÃO</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="op">(&nbsp;&nbsp;&nbsp;) SIM</span></td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Matrícula">
                <tbody>
                    <tr class="regua">
                        <td style="width:119.31pt"></td>
                        <td style="width:285.46pt"></td>
                        <td style="width:49.35pt"></td>
                        <td style="width:104.30pt"></td>
                    </tr>
                    <tr class="h485">
                        <th class="c fs10" rowspan="2">MATRÍCULA</th>
                        <th colspan="3">SOLICITO MATRÍCULA NO 1º ANO DO ENSINO TÉCNICO DE NÍVEL MÉDIO INTEGRADO – TÉCNICO EM:</th>
                    </tr>
                    <tr class="h700">
                        <td></td>
                        <th class="c">DATA</th>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Movimentação do aluno">
                <tbody>
                    <tr class="regua">
                        <td style="width:56.33pt"></td>
                        <td style="width:28.29pt"></td>
                        <td style="width:28.29pt"></td>
                        <td style="width:62.46pt"></td>
                        <td style="width:177.96pt"></td>
                        <td style="width:102.52pt"></td>
                        <td style="width:102.58pt"></td>
                    </tr>
                    <tr class="h559">
                        <th class="c fs10" colspan="7">MOVIMENTAÇÃO DO ALUNO</th>
                    </tr>
                    <tr class="h492">
                        <th class="c">ANO</th>
                        <th class="c" colspan="2">CLASSE</th>
                        <th class="c">IDADE</th>
                        <th class="c" colspan="3">ASSINATURAS</th>
                    </tr>
                    <tr class="h474">
                        <td></td>
                        <th class="c">S</th>
                        <th class="c">T</th>
                        <td></td>
                        <th class="c">RESPONSÁVEL</th>
                        <th class="c">SECRETÁRIO</th>
                        <th class="c">DIRETOR</th>
                    </tr>
                    <tr class="h651">
                        <th class="c">{{ $processYear + 0 }}</th>
                        <td></td>
                        <td></td>
                        <td class="c">{{ $user?->getAgeAttribute() + 0 ?? '-' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="h662">
                        <th class="c">{{ $processYear + 1 }}</th>
                        <td></td>
                        <td></td>
                        <td class="c">{{ $user?->getAgeAttribute() + 1 ?? '-' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="h651">
                        <th class="c">{{ $processYear + 2 }}</th>
                        <td></td>
                        <td></td>
                        <td class="c">{{ $user?->getAgeAttribute() + 2 ?? '-' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="h651">
                        <th class="c">{{ $processYear + 3 }}</th>
                        <td></td>
                        <td></td>
                        <td class="c">{{ $user?->getAgeAttribute() + 3 ?? '-' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="h662">
                        <th class="c">{{ $processYear + 4 }}</th>
                        <td></td>
                        <td></td>
                        <td class="c">{{ $user?->getAgeAttribute() + 4 ?? '-' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="h651">
                        <th class="c">{{ $processYear + 5 }}</th>
                        <td></td>
                        <td></td>
                        <td class="c">{{ $user?->getAgeAttribute() + 5 ?? '-' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

        </div>

        @php
            die();
        @endphp
    @endforeach
</body>

</html>