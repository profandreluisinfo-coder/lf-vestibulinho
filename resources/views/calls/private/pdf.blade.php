<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Cadastral do Estudante</title>
    <style>
        @page {
            /* margin: 5mm; */
            margin-top: 12mm;
            margin-bottom: 10mm;
            margin-left: 5mm;
            margin-right: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        img {
            width: 100%
        }

        .foto-box {
            float: right;
            width: 80px;
            height: 100px;
            border: 2px solid black;
            text-align: center;
            line-height: 100px;
            font-size: 10px;
            margin-top: -20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 2px solid black;
            margin-bottom: 15px;
        }

        td,
        th {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
            vertical-align: middle;
            font-size: 11px;
        }

        .header-cell {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 12px;
        }

        .clear {
            clear: both;
        }

        .imagem,
        .termo {
            border: 2px solid black;
            padding: 15px;
            margin-top: 20px;
            font-size: 13px;
            line-height: 1.4;
            text-align: justify;
        }

        .termo h3 {
            text-align: center;
            margin-top: 0;
            /* font-size: 12px; */
            font-weight: bold;
        }

        .assinatura-linha {
            margin-top: 20px;
            border-bottom: 1px solid black;
            width: 300px;
        }

        #page-break {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .tc {
            text-align: center;
        }

        .fs {
            font-size: 12px;
            text-transform: uppercase;
        }
    </style>
</head>

@php
    $path = public_path('assets/img/cab_ficha.png');
    $base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
@endphp

<body>

    @foreach ($callListMembers as $call)
        @php
            $user = $call->examResult->inscription->user;
            $detail = $user->user_detail;
            $call_date = $call->date->format('d/m/Y');
            $doc = trim(explode(' ', $detail?->doc_type)[0]); // pega a primeira palavra do tipo de documento
        @endphp

        <div class="header">
            <img src="{{ $base64 }}" alt="Cabecalho">
        </div>

        <div class="clear"></div>

        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <tr>
                <td class="header-cell" style="width: 20%;">NOME DO ESTUDANTE</td>
                <td colspan="4" class="tc fs">{{ $user->name }}</td>
                <td class="header-cell" style="width: auto; text-align: center">CONTATO</td>
                <td colspan="3" class="tc fs">{{ $detail->phone }}</td>
            </tr>

            <tr>
                <td class="header-cell">NOME SOCIAL/AFETIVO<br>(DECR. 55588/10)<br>(LEI N° 16.785/18)</td>
                <td colspan="8" class="tc">{{ $user->social_name ? $user->social_name : "---" }}</td>
            </tr>

            <tr>
                <td class="header-cell">RA</td>
                <td colspan="2" class="tc fs">{{ $detail->school_ra }}</td>
                <td class="header-cell tc">{{ $doc }}</td>
                <td colspan="2" class="tc fs">{{ $detail->nationality === "ESTRANGEIRA" ? "" : $detail->doc_number }}</td>
                <td class="header-cell tc">CPF</td>
                <td colspan="2" class="tc fs">{{ $user->cpf }}</td>
            </tr>

        </table>

        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <!-- CERTIDÃO DE NASCIMENTO -->
            <tr>
                <td class="header-cell" rowspan="3" style="width: 20%">CERTIDÃO DE NASCIMENTO</td>
                <td class="header-cell tc">NOVA</td>
                <td colspan="4" class="tc fs">{{ $detail?->new_number }}</td>
                <td class="header-cell tc">DATA NASC.</td>
                <td colspan="2" class="tc fs">
                    {{ $user->birth ? \Carbon\Carbon::parse($user->birth)->format('d/m/Y') : '' }}
                </td>
            </tr>

            <tr>
                <td class="header-cell tc">ANTIGA</td>
                <td class="header-cell tc">FLS.</td>
                <td class="tc fs">{{ $detail?->fls ? $detail?->fls : "---" }}</td>
                <td class="header-cell tc">LIVRO</td>
                <td class="tc fs">{{ $detail?->book ? $detail?->book : "---" }}</td>
                <td class="header-cell tc">NÚMERO</td>
                <td colspan="2" class="tc fs">{{ $detail?->old_number ? $detail?->old_number : "---" }}</td>
            </tr>

            <tr>
                <td class="header-cell tc">DISTRITO</td>
                <td></td>
                <td class="header-cell tc" style="font-size: 10px">SUBDISTRITO</td>
                <td></td>
                <td class="header-cell tc">MUNICÍPIO</td>
                <td colspan="3" class="tc fs">{{ $detail?->municipality ? $detail?->municipality : "---" }}</td>
            </tr>

            <!-- NACIONALIDADE / RNE -->
            <tr>
                <td class="header-cell">NACIONALIDADE</td>
                <td colspan="{{ $detail->nationality === "BRASILEIRA" ? "8" : "2" }}" class="fs">{{ $detail?->nationality }}</td>
                @if ($detail->nationality === "ESTRANGEIRA")
                <td class="header-cell tc">RNE</td>
                <td colspan="5" class="tc fs">
                    {{ $detail->doc_number }}
                  </td>
                @endif
            </tr>

            <!-- BOLSA-FAMÍLIA -->
            <tr>
                <td class="header-cell">GÊNERO</td>
                <td class="tc fs">{{ $user->gender }}</td>
                <td class="header-cell tc">BOLSA FAMÍLIA</td>
                <td class="tc fs">{{ $detail?->nis ? 'Sim' : 'Não' }}</td>
                <td class="header-cell tc">NIS</td>
                <td colspan="4" class="tc fs">{{ $detail?->nis ? $detail?->nis : '---' }}</td>
            </tr>

        </table>

        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">

            <!-- EDUCAÇÃO ESPECIAL -->
            <tr>
                <td class="header-cell" style="width: 32%">ELEGÍVEL AOS SERVIÇOS DA EDUCAÇÃO ESPECIAL</td>
                <td class="tc fs">{{ $detail?->accessibility ? 'Sim' : 'Não' }}</td>
                <td class="header-cell tc">QUAL?</td>
                <td colspan="5" class="tc fs">{{ $detail?->accessibility }}</td>
            </tr>

            <!-- SAÚDE / ALERGIA -->
            <tr>
                <td class="header-cell">TEM ALGUM PROBLEMA DE SAÚDE OU ALERGIA</td>
                <td class="tc fs">{{ $detail?->health ? 'Sim' : 'Não' }}</td>
                <td class="header-cell tc">QUAL?</td>
                <td colspan="5" class="tc fs">{{ $detail?->health }}</td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="header-cell" style="width: 19%;">FILIAÇÃO 1</td>
                <td class="tc fs">{{ $detail->mother }}</td>
                <td class="header-cell" style="width: 15%; text-align: center">CONTATO</td>
                <td class="tc fs">{{ $detail->mother_phone }}</td>
            </tr>
            <tr>
                <td class="header-cell">FILIAÇÃO 2</td>
                <td class="tc fs">{{ $detail?->father }}</td>
                <td class="header-cell" style="text-align: center">CONTATO</td>
                <td class="tc fs">{{ $detail?->father_phone }}</td>
            </tr>
            <tr>
                <td class="header-cell">RESPONSÁVEL PELO ESTUDANTE</td>
                <td class="tc fs">{{ $detail?->responsible }}</td>
                <td class="header-cell" style="text-align: center">PARENTESCO</td>
                <td class="tc fs">{{ $detail?->getDegreeAttribute($detail?->degree) }}</td>
            </tr>
            <tr>
                <td class="header-cell">E-MAIL</td>
                <td class="tc fs">{{ $detail->parents_email }}</td>
                <td class="header-cell" style="text-align: center">CONTATO</td>
                <td class="tc fs">{{ $detail?->responsible_phone }}</td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="header-cell" rowspan="3">ENDEREÇO<br>RESIDENCIAL</td>
                <td class="header-cell" style="width: 15%; text-align: center">RUA/AV.</td>
                <td colspan="6" class="tc fs">{{ $detail->street }} {{ $detail?->number }}</td>
            </tr>
            <tr>
                <td class="header-cell" style="width: 10%; text-align: center">BAIRRO</td>
                <td colspan="3" class="tc fs">{{ $detail->burgh }}</td>
                </td>
                <td class="header-cell">COMPLEMENTO</td>
                <td colspan="2" class="tc fs">{{ $detail?->complement }}</td>
            </tr>
            <tr>
                <td class="header-cell" style="width: 10%; text-align: center">CIDADE</td>
                <td class="tc fs">{{ $detail->city }}</td>
                <td class="header-cell" style="width: 8%; text-align: center;">UF</td>
                <td class="tc fs">{{ $detail->state }}</td>
                <td class="header-cell" style="width: 10%; text-align: center;">CEP</td>
                <td colspan="2" class="tc fs">{{ $detail->zip }}</td>
            </tr>
            <tr>
                <th rowspan="2" class="header-cell">TRANSPORTE<br>ESCOLAR GRATUITO</td>
                <td></td>
                <td class="header-cell" style="text-align: center;">VAN<br>ESCOLAR</td>
                <td></td>
                <td class="header-cell" style="text-align: center;">OUTRO MEIO</td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="header-cell" style="text-align: center;">OBS.</td>
                <td colspan="6"></td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th rowspan="3" class="header-cell"
                    style="writing-mode: vertical-lr;  text-orientation: sideways; width: 30px; text-align: center;">
                    <strong>ALTERAÇÕES</strong>
                </th>
                <td style="height: 15px"></td>
            </tr>
            <tr>
                <td style="height: 15px"></td>
            </tr>
            <tr>
                <td style="height: 15px"></td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <tr>
                <td class="header-cell" style="width: 25%;">ESCOLA DE CONCLUSÃO E.F.</td>
                <td colspan="6" style="width: 70%;" class="tc fs">{{ $detail->school_name }}</td>
            </tr>
            <tr>
                <td class="header-cell" style="width: 15%;">CIDADE</td>
                <td colspan="2" class="tc fs">{{ $detail->school_city }}</td>
                <td class="header-cell" style="width: 5%; text-align: center">UF</td>
                <td class="tc fs">{{ $detail->school_state }}</td> <!-- célula para valor de UF -->
                <td class="header-cell" style="width: 20%;">ANO DE CONCLUSÃO</td>
                <td class="tc fs">{{ $detail->school_year }}</td> <!-- célula para valor do ano -->
            </tr>
        </table>
        <!-- Mover a tabela para a próxima página na impressão -->
        <table id="page-break">
            <tr>
                <th rowspan="2" class="header-cell" style="width: 15%; text-align: center">MATRÍCULA</th>
                <td colspan="5" class="header-cell" style="text-align: center;">SOLICITO MATRÍCULA NO 1º ANO DO
                    ENSINO
                    TÉCNICO DE NÍVEL MÉDIO INTEGRADO - TÉCNICO EM:</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="header-cell tc" style="width: 10%;">DATA</td>
                <td class="tc fs">{{ $call_date }}</td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <tr>
                <td class="header-cell" colspan="7" style="text-align: center;">MOVIMENTAÇÃO DO ALUNO</td>
            </tr>
            <tr>
                <td class="header-cell" style="text-align: center;">ANO</td>
                <td colspan="2" class="header-cell" style="text-align: center;">CLASSE</td>
                <td class="header-cell" style="text-align: center;">IDADE</td>
                <td colspan="3" class="header-cell" style="text-align: center;">ASSINATURAS</td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td class="header-cell" style="width: 5%; text-align: center;">S</td>
                <td class="header-cell" style="width: 5%; text-align: center;">T</td>
                <td style="width: 10%;"></td>
                <td class="header-cell" style="width: 25%; text-align: center;">RESPONSÁVEL</td>
                <td class="header-cell" style="width: 15%; text-align: center;">SECRETÁRIO</td>
                <td class="header-cell" style="width: 15%; text-align: center;">DIRETOR</td>
            </tr>
            <tr>
                <td>2026</td>
                <td></td>
                <td></td>
                <td class="tc fs">{{ $user->getAgeAttribute() }}</td>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>2027</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>2028</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>2029</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <table>
            <tr>
                <th rowspan="4" class="header-cell"
                    style="writing-mode: vertical-rl; text-orientation: mixed; width: 120px; text-align: center; height: 100px;">
                    <strong>TRANSFERÊNCIA</strong>
                </th>
                <td colspan="5" style="text-align: center;font-size: 13px"><strong>SOLICITO TRANSFERÊNCIA DE
                        MATRÍCULA PARA OUTRO ESTABELECIMENTO DE ENSINO</strong>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="tc fs"><strong>Obs:</strong> a assinatura neste campo torna sem efeito as
                    rematrículas realizadas para
                    os anos subsequentes.</td>
            </tr>
            <tr>
                <td colspan="2" class="header-cell"
                    style="writing-mode: vertical-rl; text-orientation: mixed; width: 80px; text-align: center;">
                    <strong>INSTITUIÇÃO DE ENSINO:</strong>
                </td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="header-cell"
                    style="writing-mode: vertical-rl; text-orientation: mixed; width: 80px; text-align: center;">
                    <strong>DATA</strong>
                </td>
                <td style="width: 150px;"></td>
                <td class="header-cell"
                    style="writing-mode: vertical-rl; text-orientation: mixed; width: 80px; text-align: center;">
                    <strong>ASSINATURA</strong><br><strong>RESPONSÁVEL</strong>
                </td>
                <td colspan="2"></td>
            </tr>
        </table>

        <div class="imagem">
            <h3 style="text-align: center; margin-top: -3px;">AUTORIZAÇÃO PARA USO DE IMAGEM</h3>
            <p>Autorizo, de forma gratuita e definitiva, o uso da imagem do(a) estudante acima identificado(a) pela
                instituição de ensino.</p>
            <p>A presente autorização abrange o uso da imagem em fotografias, vídeos e demais registros audiovisuais
                realizados em atividades escolares, eventos, projetos pedagógicos, publicações institucionais, redes
                sociais, mídias digitais e impressas, com finalidade exclusivamente educativa, institucional e
                informativa, respeitando a legislação vigente: Lei nº 8.069, de 13 de julho de 1990, art. 17, que
                garante o direito ao respeito, incluindo a preservação da imagem da criança e do adolescente.</p>
            <p>Declaro estar ciente de que esta autorização não tem caráter comercial e que a imagem será utilizada de
                forma ética, sem causar prejuízo à honra, reputação ou dignidade do(a) estudante.</p>
            <p>Sim <span style="margin-left: 8px; margin-right: 8px">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span> Não <span
                    style="margin-left: 8px; margin-right: 8px">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span></p>
        </div>

        <div class="termo">
            <h3>TERMO DE CIÊNCIA</h3>

            <p>A Direção da ESCOLA MUNICIPAL DR. LEANDRO FRANCESCHINI, em cumprimento às atuais legislações, informa e
                dá
                ciência aos alunos e seus pais ou responsáveis, que os cursos oferecidos por esta escola, de Técnico em
                Administração, Técnico em Contabilidade, Técnico em Informática e Técnico em Segurança do Trabalho, tem
                duração
                de 4 (quatro) anos e por ser a articulação do Ensino Profissional com o Ensino Médio, conforme forma
                integrada,
                prevista no decreto 5154/04, somente possibilitará prosseguimento de estudos em nível superior após a
                conclusão
                do curso.</p>

            <p>Portanto o aluno receberá o Histórico Escolar e o Certificado de Conclusão após sua aprovação no quarto
                ano do
                curso.</p>

            <p><strong>DECLARO ACATAR AS NORMAS REGIMENTAIS DESSE ESTABELECIMENTO DE ENSINO.</strong></p>

            <p><strong>Sumaré, <span class="fs">{{ $call_date }}.</strong></p>

            <p><strong>Assinatura do pai ou responsável:</strong></p>
            <div class="assinatura-linha"></div>
        </div>

        <div style="page-break-after: always;"></div>
    @endforeach

</body>

</html>
