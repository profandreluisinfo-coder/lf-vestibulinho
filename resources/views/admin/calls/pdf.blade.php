@php
    $processYear = (int) ($process?->year ?? $callList->process?->year ?? now()->year);
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
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            color: #000;
            font-family: Cambria, "Times New Roman", serif;
            font-size: 10pt;
            line-height: 1.12;
        }

        .folha {
            width: 197mm;
            margin: 0 auto;
            padding: 6mm 0;
        }

        .cabecalho {
            width: 100%;
            margin: 0 0 3mm;
            text-align: center;
        }

        .cabecalho img {
            display: block;
            width: 100%;
            height: auto;
        }

        .ficha {
            width: 100%;
            table-layout: fixed;
            border: 1.5pt solid #000;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .ficha + .ficha {
            margin-top: 3.2mm;
        }

        .ficha th,
        .ficha td {
            padding: 0 1.6mm;
            border: .5pt solid #000;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .ficha th {
            font-weight: 700;
            font-size: 10pt;
            text-align: left;
        }

        .ficha .dupla th {
            height: 11.58mm;
            padding-bottom: 10pt;
            vertical-align: top;
        }

        .ficha .c {
            text-align: center;
        }

        .ficha .fs65 { font-size: 6.5pt; }
        .ficha .fs7 { font-size: 7pt; }
        .ficha .fs75 { font-size: 7.5pt; }
        .ficha .fs10 { font-size: 10pt; }
        .birth-date { font-size: 6.5pt; white-space: nowrap; }
        .narrow-label { font-size: 5.5pt !important; word-wrap: normal !important; overflow-wrap: normal !important; }
        .opcoes { font-weight: 700; }
        .op { white-space: nowrap; }
        .op + .op { margin-left: 1em; }
        .check { font-family: Arial, sans-serif; font-weight: 700; }
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

        <main class="folha">
            @if ($headerImage)
                <div class="cabecalho">
                    <img src="{{ $headerImage }}" alt="Cabeçalho da ficha de matrícula">
                </div>
            @endif

            <table class="ficha" aria-label="Dados do estudante">
                <colgroup>
                    <col style="width:20%"><col style="width:8%"><col style="width:6%">
                    <col style="width:9%"><col style="width:6%"><col style="width:7%">
                    <col style="width:7%"><col style="width:7%"><col style="width:1%">
                    <col style="width:9%"><col style="width:8%"><col style="width:12%">
                </colgroup>
                <tbody>
                    <tr style="height:5.79mm">
                        <th colspan="2">NOME DO ESTUDANTE</th>
                        <td colspan="6">{{ $user?->name ?? '-' }}</td>
                        <th class="fs75" colspan="2">CONTATO</th>
                        <td colspan="2">{{ $user?->phone ?? '-' }}</td>
                    </tr>
                    <tr style="height:6.62mm">
                        <th colspan="2">NOME SOCIAL/AFETIVO<br>(DECR. 55588/10)</th>
                        <td colspan="10">{{ $lgbt?->status === 'accepted' ? $lgbt->name : '-' }}</td>
                    </tr>
                    <tr class="dupla">
                        <th colspan="3">RA: {{ $academic?->ra ?? '-' }}</th>
                        <th colspan="4">RG/CIN: {{ $document?->number ?? '-' }}</th>
                        <th colspan="5">CPF: {{ $user?->cpf ?? '-' }}</th>
                    </tr>
                    <tr style="height:6.62mm">
                        <th colspan="2">CERTIDÃO DE<br>NASCIMENTO</th>
                        <th class="fs65" colspan="2">NOVA</th>
                        <td colspan="6">{{ $certificateType === '1' ? ($certificate?->number ?? '-') : '-' }}</td>
                        <th class="fs75">DATA NASC.</th>
                        <td class="birth-date">{{ $birthDate ?: '-' }}</td>
                    </tr>
                    <tr style="height:6.62mm">
                        <th>ANTIGA</th>
                        <th>FLS.</th>
                        <td>{{ $certificateType === '2' ? ($certificate?->fls ?? '-') : '-' }}</td>
                        <th>LIVRO</th>
                        <td>{{ $certificateType === '2' ? ($certificate?->book ?? '-') : '-' }}</td>
                        <th>NÚMERO</th>
                        <td>{{ $certificateType === '2' ? ($certificate?->number ?? '-') : '-' }}</td>
                        <th class="fs75" colspan="2">DISTRITO</th>
                        <td colspan="3">-</td>
                    </tr>
                    <tr style="height:7.11mm">
                        <th class="narrow-label">SUBDISTRITO</th>
                        <td colspan="4">-</td>
                        <th colspan="2">MUNICÍPIO</th>
                        <td colspan="5">{{ $certificate?->city ?? '-' }}</td>
                    </tr>
                    <tr style="height:5.79mm">
                        <th colspan="2">NACIONALIDADE</th>
                        <td colspan="2">{{ $user?->nationality ?? '-' }}</td>
                        <th colspan="2">ESTRANGEIRO</th>
                        <td colspan="2" class="opcoes">[{{ $isForeign ? ' ' : 'X' }}] NÃO [{{ $isForeign ? 'X' : ' ' }}] SIM</td>
                        <th class="fs7" colspan="2">RNE/RNM</th>
                        <td colspan="2">{{ $isForeign ? ($document?->number ?? '-') : '-' }}</td>
                    </tr>
                    <tr style="height:5.79mm">
                        <th>GÊNERO</th>
                        <td colspan="2">{{ $user?->gender ?? '-' }}</td>
                        <th colspan="2">BOLSA FAMÍLIA</th>
                        <td colspan="2" class="opcoes">[{{ filled($user?->nis) ? 'X' : ' ' }}] SIM [{{ filled($user?->nis) ? ' ' : 'X' }}] NÃO</td>
                        <th colspan="2">NIS</th>
                        <td colspan="3">{{ $user?->nis ?? '-' }}</td>
                    </tr>
                    <tr style="height:7.32mm">
                        <th colspan="3">POSSUI ALGUMA DEFICIÊNCIA?</th>
                        <td class="opcoes" colspan="9">
                            <span class="op">[{{ $hasAcceptedPne ? ' ' : 'X' }}] NÃO</span>
                            <span class="op">[{{ $hasAcceptedPne ? 'X' : ' ' }}] SIM - ESPECIFIQUE: {{ $hasAcceptedPne ? ($pne?->description ?? '') : '' }}</span>
                        </td>
                    </tr>
                    <tr style="height:7.32mm">
                        <th colspan="3">NECESSITA DE ADAPTAÇÃO PEDAGÓGICA?</th>
                        <td class="opcoes" colspan="9">
                            <span class="op">[{{ $needsAdaptation ? ' ' : 'X' }}] NÃO</span>
                            <span class="op">[{{ $needsAdaptation ? 'X' : ' ' }}] SIM - ESPECIFIQUE: {{ $needsAdaptation ? ($pne?->support ?? '') : '' }}</span>
                        </td>
                    </tr>
                    <tr style="height:7.32mm">
                        <th colspan="3">TEM ALGUM PROBLEMA DE SAÚDE OU ALERGIA?</th>
                        <td class="opcoes" colspan="9">
                            <span class="op">[{{ $healthIssue ? ' ' : 'X' }}] NÃO</span>
                            <span class="op">[{{ $healthIssue ? 'X' : ' ' }}] SIM - ESPECIFIQUE: {{ $healthIssue ?? '' }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Filiação e responsável">
                <colgroup>
                    <col style="width:15.088%"><col style="width:55.390%"><col style="width:8.784%">
                    <col style="width:3.582%"><col style="width:17.156%">
                </colgroup>
                <tbody>
                    <tr style="height:5.79mm">
                        <th>FILIAÇÃO 1</th><td>{{ $mother?->name ?? '-' }}</td>
                        <th class="fs75">CONTATO</th><td colspan="2">{{ $mother?->phone ?? '-' }}</td>
                    </tr>
                    <tr style="height:5.79mm">
                        <th>FILIAÇÃO 2</th><td>{{ $father?->name ?? '-' }}</td>
                        <th class="fs75">CONTATO</th><td colspan="2">{{ $father?->phone ?? '-' }}</td>
                    </tr>
                    <tr style="height:5.79mm">
                        <th>RESPONSÁVEL PELO ESTUDANTE</th><td>{{ $guardian?->name ?? '-' }}</td>
                        <th class="fs75" colspan="2">PARENTESCO</th><td>{{ $guardian?->kinship ?: ($guardian?->degree ?: '-') }}</td>
                    </tr>
                    <tr style="height:5.79mm">
                        <th>E-MAIL</th><td>{{ $parentEmail?->address ?? '-' }}</td>
                        <th class="fs75">CONTATO</th><td colspan="2">{{ $guardian?->phone ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Endereço residencial">
                <colgroup>
                    <col style="width:14.953%"><col style="width:7.602%"><col style="width:35.566%">
                    <col style="width:6.519%"><col style="width:7.835%"><col style="width:6.465%"><col style="width:21.060%">
                </colgroup>
                <tbody>
                    <tr style="height:5.79mm">
                        <th>ENDEREÇO RESIDENCIAL</th><th>RUA/AV.</th><td colspan="5">{{ $user?->street }} {{ $user?->home }}</td>
                    </tr>
                    <tr style="height:5.79mm">
                        <th>BAIRRO</th><td colspan="2">{{ $user?->burgh ?? '-' }}</td>
                        <th colspan="2">COMPLEMENTO</th><td colspan="2">{{ $user?->complement ?: '-' }}</td>
                    </tr>
                    <tr style="height:5.79mm">
                        <th>CIDADE</th><td colspan="2">{{ $user?->city ?? '-' }}</td>
                        <th class="c">UF</th><td>{{ $user?->state ?? '-' }}</td>
                        <th class="c">CEP</th><td>{{ $user?->zip ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Escola de conclusão do Ensino Fundamental">
                <colgroup>
                    <col style="width:15.097%"><col style="width:28.447%"><col style="width:5.999%">
                    <col style="width:13.852%"><col style="width:19.431%"><col style="width:17.174%">
                </colgroup>
                <tbody>
                    <tr style="height:6.49mm">
                        <th>ESCOLA DE CONCLUSÃO E.F.</th><td colspan="5">{{ $academic?->school ?? '-' }}</td>
                    </tr>
                    <tr style="height:6.49mm">
                        <th>CIDADE</th><td>{{ $academic?->city ?? '-' }}</td>
                        <th class="c">UF</th><td>{{ $academic?->state ?? '-' }}</td>
                        <th class="c">ANO DE CONCLUSÃO</th><td>{{ $academic?->year ?? '-' }}</td>
                    </tr>
                    <tr style="height:6.49mm">
                        <td class="opcoes" colspan="6">ESTUDANTE JÁ CURSOU 1º ANO EM OUTRA INSTITUIÇÃO DE ENSINO? <span class="op">[&nbsp;&nbsp;] NÃO</span><span class="op">[&nbsp;&nbsp;] SIM</span></td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Matrícula">
                <colgroup>
                    <col style="width:21.365%"><col style="width:51.119%"><col style="width:8.838%"><col style="width:18.678%">
                </colgroup>
                <tbody>
                    <tr style="height:4.85mm">
                        <th class="c fs10" rowspan="2">MATRÍCULA</th>
                        <th colspan="3">SOLICITO MATRÍCULA NO 1º ANO DO ENSINO TÉCNICO DE NÍVEL MÉDIO INTEGRADO - TÉCNICO EM:</th>
                    </tr>
                    <tr style="height:7mm">
                        <td>{{ $courseName ?? '-' }}</td><th class="c">DATA</th><td>{{ $callDate }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="ficha" aria-label="Movimentação do aluno">
                <colgroup>
                    <col style="width:10.087%"><col style="width:5.066%"><col style="width:5.066%">
                    <col style="width:11.186%"><col style="width:31.868%"><col style="width:18.360%"><col style="width:18.369%">
                </colgroup>
                <tbody>
                    <tr style="height:5.59mm"><th class="c fs10" colspan="7">MOVIMENTAÇÃO DO ALUNO</th></tr>
                    <tr style="height:4.92mm">
                        <th class="c">ANO</th><th class="c" colspan="2">CLASSE</th><th class="c">IDADE</th><th class="c" colspan="3">ASSINATURAS</th>
                    </tr>
                    <tr style="height:4.74mm">
                        <td></td><th class="c">S</th><th class="c">T</th><td></td><th class="c">RESPONSÁVEL</th><th class="c">SECRETÁRIO</th><th class="c">DIRETOR</th>
                    </tr>
                    @for ($yearOffset = 0; $yearOffset < 6; $yearOffset++)
                        <tr style="height:6.51mm">
                            <th class="c">{{ $processYear + $yearOffset }}</th>
                            <td></td><td></td><td>{{ $user?->getAgeAttribute() ?? '-' }}</td>
                            <td></td><td></td><td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </main>

        @if (! $loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>

</html>
