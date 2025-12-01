@extends('layouts.dash.user')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Meus Dados')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/user/perfil.css') }}">
@endpush

@section('dash-content')

    <div class="d-flex align-items-center mb-3">
        <i class="bi bi-clipboard-check text-primary fs-4 me-2 animate__animated animate__fadeIn"></i>
        <h5 class="m-0 fw-semibold">Resumo da sua inscrição</h5>
    </div>
    <p class="text-muted mb-4">
        Abaixo estão os dados principais da sua inscrição.
    </p>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th colspan="2">Dados do Candidato</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Inscrição Nº</td>
                    <td>{{ auth()->user()->inscription->id }}</td>
                </tr>
                <tr>
                    <th>Nome Completo</td>
                    <td>{{ auth()->user()->name }}</td>
                </tr>
                <tr>
                    <th>CPF</td>
                    <td>{{ auth()->user()->cpf }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @if (auth()->user()->user_detail?->accessibility)
        <div class="table-responsive mt-4 mt-lg-1">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Necessidade de acessibilidade indicada pelo candidato:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{ auth()->user()->user_detail?->accessibility }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="alert alert-warning border-0 mt-3 text-muted small text-break">
                <i class="bi bi-exclamation-triangle me-0 me-md-1"></i>
                <strong>Atenção!</strong>
                O(a) candidato(a) portador de necessidades especiais deverá informar no período
                de
                inscrição
                qual a
                sua necessidade específica,
                enviando e-mail com atestado médico anexo para
                <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br" class="text-decoration-none fw-semibold">
                    emdrleandrofranceschini@educacaosumare.com.br
                </a>,
                <strong>conforme o item 4.8 do edital</strong>.
            </div>
        </div>
    @endif
    @if (auth()->user()->hasConfirmedCall())
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th colspan="2">PARABÉNS, você foi convocado para efetuar sua matrícula!</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Chamada Nº:</td>
                        <td>{{ $call?->call_number }}</td>
                    </tr>
                    <tr>
                        <th>Data:</td>
                        <td>{{ Carbon\Carbon::parse($call?->date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Horário:</td>
                        <td>{{ Carbon\Carbon::parse($call?->time)->format('H:i') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="alert alert-warning">
                <span class="text-muted small">
                    <i class="bi bi-exclamation-triangle me-1 me-md-2"></i><strong>Atenção!</strong>
                    Compareça na data e horário informados para realizar sua matrícula.
                </span>
            </div>
        </div>
    @endif

    <div class="d-flex flex-column flex-sm-row gap-2">
        <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#fichaDeInscricao">
            <i class="bi bi-search"></i> Inscrição
        </a>

        @if ($settings->location)
            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#localDeProva">
                <i class="bi bi-search"></i> Local de Prova
            </a>
        @endif

        @if ($settings->result)
            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#resultadoDeProva">
                <i class="bi bi-search"></i> Classificação
            </a>
        @endif

        @if (auth()->user()->hasConfirmedCall())
            <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                data-bs-target="#callDetailModal">
                <i class="bi bi-search animate__animated animate__fadeIn"></i> Ver detalhes da convocação
            </a>
        @endif
    </div>

    <!-- Modal com todos os dados da inscrição do candidato -->
    <div class="modal fade" id="fichaDeInscricao">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-white">
                    <h5 class="modal-title"><i class="bi bi-person-vcard me-2"></i> Ficha de Inscrição do Candidato</h5>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {{-- Dados da Inscrição --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="fw-semibold">📄 Dados da Inscrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Inscrição Nº</th>
                                    <td>{{ $user->inscription->id }}</td>
                                </tr>
                                <tr>
                                    <th>Data</th>
                                    <td>{{ \Carbon\Carbon::parse($user->inscription->created_at)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Identificação do Candidato --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="fw-semibold">🧑‍💼 Identificação do Candidato</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>CPF</th>
                                    <td>{{ $user->cpf }}</td>
                                </tr>
                                <tr>
                                    <th>Nome Completo</th>
                                    <td>{{ $user->social_name ?? $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Gênero</th>
                                    <td>{{ $user->gender }}</td>
                                </tr>
                                <tr>
                                    <th>E-mail</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Telefone</th>
                                    <td>{{ $user->user_detail->phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Documentos Pessoais + Certidão --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="fw-semibold">📑 Documentos Pessoais e Certidão</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Nacionalidade</th>
                                    <td>{{ $user->user_detail->nationality }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo de Documento</th>
                                    <td>{{ $user->user_detail->doc_type }}</td>
                                </tr>
                                <tr>
                                    <th>Número</th>
                                    <td>{{ $user->user_detail->doc_number }}</td>
                                </tr>
                                <tr>
                                    <th>Data de Nascimento</th>
                                    <td>{{ \Carbon\Carbon::parse($user->birth)->format('d/m/Y') }}</td>
                                </tr>

                                @if (!empty($user->user_detail->new_number))
                                    <tr>
                                        <th>Nº Certidão</th>
                                        <td>{{ $user->user_detail->new_number }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <th>Folhas</th>
                                        <td>{{ $user->user_detail->fls }}</td>
                                    </tr>
                                    <tr>
                                        <th>Livro</th>
                                        <td>{{ $user->user_detail->book }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nº Certidão</th>
                                        <td>{{ $user->user_detail->old_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Município</th>
                                        <td>{{ $user->user_detail->municipality }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- Filiação / Responsável Legal --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="fw-semibold">👨‍👩‍👧‍👦 Filiação / Responsável Legal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Mãe</th>
                                    <td>{{ $user->user_detail->mother }}</td>
                                </tr>
                                @if ($user->user_detail->mother_phone)
                                    <tr>
                                        <th>Telefone da Mãe</th>
                                        <td>{{ $user->user_detail->mother_phone }}</td>
                                    </tr>
                                @endif

                                @if ($user->user_detail->father)
                                    <tr>
                                        <th>Pai</th>
                                        <td>{{ $user->user_detail->father }}</td>
                                    </tr>
                                    @if ($user->user_detail->father_phone)
                                        <tr>
                                            <th>Telefone do Pai</th>
                                            <td>{{ $user->user_detail->father_phone }}</td>
                                        </tr>
                                    @endif
                                @endif

                                @if ($user->user_detail->responsible)
                                    <tr>
                                        <th>Responsável Legal</th>
                                        <td>{{ $user->user_detail->responsible }}</td>
                                    </tr>
                                    <tr>
                                        <th>Parentesco</th>
                                        <td>{{ $user->user_detail->degree }}</td>
                                    </tr>
                                    @if ($user->user_detail->kinship)
                                        <tr>
                                            <th>Descrição</th>
                                            <td>{{ $user->user_detail->kinship }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Telefone do Responsável</th>
                                        <td>{{ $user->user_detail->responsible_phone }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <th>E-mail de Contato</th>
                                    <td>{{ $user->user_detail->parents_email }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Escolaridade --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="fw-semibold">🎓 Escolaridade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Escola</th>
                                    <td>{{ $user->user_detail->school_name }}</td>
                                </tr>
                                <tr>
                                    <th>RA</th>
                                    <td>{{ $user->user_detail->school_ra }}</td>
                                </tr>
                                <tr>
                                    <th>Cidade</th>
                                    <td>{{ $user->user_detail->school_city }}</td>
                                </tr>
                                <tr>
                                    <th>Estado</th>
                                    <td>{{ $user->user_detail->school_state }}</td>
                                </tr>
                                <tr>
                                    <th>Ano de Conclusão</th>
                                    <td>{{ $user->user_detail->school_year }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Endereço --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="fw-semibold">🏠 Endereço</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>CEP</th>
                                    <td>{{ $user->user_detail->zip }}</td>
                                </tr>
                                <tr>
                                    <th>Rua</th>
                                    <td>{{ $user->user_detail->street }}</td>
                                </tr>
                                <tr>
                                    <th>Número</th>
                                    <td>{{ $user->user_detail->number }}</td>
                                </tr>
                                @if ($user->user_detail->complement)
                                    <tr>
                                        <th>Complemento</th>
                                        <td>{{ $user->user_detail->complement }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Bairro</th>
                                    <td>{{ $user->user_detail->burgh }}</td>
                                </tr>
                                <tr>
                                    <th>Cidade</th>
                                    <td>{{ $user->user_detail->city }}</td>
                                </tr>
                                <tr>
                                    <th>Estado</th>
                                    <td>{{ $user->user_detail->state }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Educação Especial --}}
                    @if ($user->user_detail?->accessibility)
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-info">
                                    <tr>
                                        <th colspan="2" class="fw-semibold">♿ Educação Especial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Elegível</th>
                                        <td>{{ $user->user_detail?->accessibility ? 'SIM' : 'NÃO' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Necessidade</th>
                                        <td>{{ $user->user_detail->accessibility }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="alert alert-danger mt-2 text-muted small">
                                <span class="fw-bold">Atenção!</span> O(a) candidato(a) com necessidades especiais
                                deverá enviar
                                atestado médico durante o periodo de inscrição conforme o item 4.8 do edital.
                            </div>
                        </div>
                    @endif

                    {{-- Programas Sociais + Outras Informações --}}
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2" class="fw-semibold">🤝 Programas Sociais e Outras Informações
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Beneficiário Bolsa-Família</th>
                                    <td>{{ $user->user_detail?->nis ? 'SIM' : 'NÃO' }}</td>
                                </tr>
                                @if ($user->user_detail?->nis)
                                    <tr>
                                        <th>NIS</th>
                                        <td>{{ $user->user_detail->nis }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Problema de Saúde / Alergia</th>
                                    <td>{{ $user->user_detail?->health ? 'SIM' : 'NÃO' }}</td>
                                </tr>
                                @if ($user->user_detail->health)
                                    <tr>
                                        <th>Descrição</th>
                                        <td>{{ $user->user_detail->health }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <form action="{{ route('pdf') }}" method="post">
                            @csrf
                            @method('post')
                            <button type="submit" class="btn btn-danger btn-sm"><i
                                    class="bi bi-filetype-pdf me-2"></i>Gerar PDF</button>
                        </form>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Fechar</button>
                </div>

            </div>
        </div>
    </div>

    @if ($settings->location && $exam)
        <!-- Modal de definição de local de prova -->
        <div class="modal fade" id="localDeProva">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-geo-alt-fill"></i> Local de Prova</h5>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <!-- Candidato -->
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-person me-2"></i>Candidato:
                                        </td>
                                        <td class="w-75 fw-semibold">
                                            {{ auth()->user()->social_name ?: auth()->user()->name }}
                                        </td>
                                    </tr>
                                    <!-- Local de Prova -->
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-building me-2"></i>Local:
                                        </td>
                                        <td class="w-75">
                                            <div class="border-bottom mb-2 fw-semibold">
                                                {{ $exam->location?->name }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ $exam->location?->address }}
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Sala -->
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-door-open me-2"></i>Sala:
                                        </td>
                                        <td class="w-75 fw-semibold">
                                            {{ $exam->room_number }}

                                            @if ($exam->pne ?? false)
                                                <div class="alert alert-warning mt-3 p-2 fw-semibold">
                                                    <i class="bi bi-universal-access-circle"></i>
                                                    Sala de Atendimento Especializado.
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Data -->
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-calendar-event me-2"></i>Data:
                                        </td>
                                        <td class="w-75 fw-semibold">
                                            {{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                    <!-- Hora -->
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-clock me-2"></i>Hora:
                                        </td>
                                        <td class="w-75 fw-semibold">
                                            {{ \Carbon\Carbon::parse($exam->exam_time)->format('H:i') }}
                                        </td>
                                    </tr>
                                    <!-- Instruções -->
                                    <tr>
                                        <td class="w-25">
                                            <i class="bi bi-info-circle me-2"></i>Instruções:
                                        </td>
                                        <td class="w-75">
                                            <ul class="mb-0 ps-3 small">
                                                <li>Chegue com <strong>30 minutos de antecedência</strong>.</li>
                                                <li>Leve documento com foto e caneta azul ou preta.</li>
                                                <li class="text-danger fw-bold">Não é permitido usar dispositivos
                                                    eletrônicos durante a prova.</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <a href="{{ route('user.card.pdf') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-download"></i> Baixar PDF
                            </a>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    @endif

@if ($settings->result)
    <!-- Modal de exibição de classificação na prova-->
    <div class="modal fade" id="resultadoDeProva">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="card border-success shadow-sm" id="result-card">
                        <div
                            class="card-header bg-success d-flex justify-content-between align-items-center text-white">
                            <h5 class="mb-0"><i class="bi bi-list-ol me-2"></i> Resultado da Prova Objetiva</h5>
                            <span class="badge bg-light text-success">Ano {{ $calendar->year }}</span>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="text-muted">Candidato(a)</h5>
                            <h4 class="fw-bold">{{ $user->name }}</h4>
                            <p class="mb-2">
                                CPF <br><strong>{{ $user->cpf }}</strong><br>
                            </p>

                            <hr class="my-4">

                            <h1 class="display-4 fw-bold text-success">{{ $examResult?->score }}</h1>
                            <p class="text-muted mb-1">Nota obtida</p>

                            <h2 class="text-primary mt-4">{{ $examResult?->ranking }}º</h2>
                            <p class="mb-0">Classificação Geral</p>
                        </div>

                        <div class="card-footer text-muted small text-center">
                            Os critérios de desempate consideraram a idade do candidato (mais jovem tem prioridade).
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('user.result.pdf') }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-file-earmark-pdf"></i> Gerar PDF
                        </a>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                </div>

            </div>
        </div>
    </div>
@endif

@if ($call && auth()->user()->hasConfirmedCall())
    <!-- Modal de exibição de detalhes da convocação -->
    <div class="modal fade" id="callDetailModal" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="callDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-primary">
                <div class="modal-header">
                    <h5 class="modal-title" id="callDetailModalLabel"><i class="bi bi-megaphone me-2"></i> Detalhes
                        da Convocação</h5>
                </div>
                <div class="modal-body">
                    <p><strong>Nome:</strong> {{ auth()->user()->social_name ?? auth()->user()->name }}</p>
                    <p><strong>Chamada nº:</strong> {{ $call?->call_number }}</p>
                    <p><strong>Data:</strong> {{ Carbon\Carbon::parse($call?->date)->format('d/m/Y') }}</p>
                    <p><strong>Horário:</strong> {{ Carbon\Carbon::parse($call?->time)->format('H:i') }}</p>

                    <hr>

                    <h6 class="fw-bold text-primary">Local da Matrícula</h6>
                    <p class="mb-1">R. Geraldo de Souza, 221 - Jardim São Carlos</p>
                    <p class="mb-1">Sumaré - SP, 13170-232</p>
                    <p class="mb-1"><strong>Telefone:</strong> (19) 3873-2605</p>
                    <p class="mb-3"><strong>Horário de Funcionamento:</strong> 14:00 às 23:00</p>

                    <h6 class="fw-bold text-primary">INFORMAÇÕES IMPORTANTES!</h6>
                    <p>A falta de documentação ou não comparecimento na data e horário estabelecido acarretará na perda
                        da vaga,
                        portanto não se esqueça de comparecer no dia e horário indicado portando todos os documentos
                        previstos no
                        item <strong>7.4</strong> do edital. </p>
                    <ol class="docs-list">
                        <li>Declaração de Conclusão do Ensino Fundamental ou Histórico Escolar do Ensino Fundamental
                            (Original e
                            01 cópia);</li>
                        <li>01 foto 3x4;</li>
                        <li>Original e 01 cópia do documento de identidade (RG/CIN ou RNE para estrangeiro) atualizado e
                            com foto
                            que identifique o portador;</li>
                        <li>Original e 01 cópia do CPF;</li>
                        <li>Original e 01 cópia da certidão de nascimento;</li>
                        <li>Carteira de vacinação (Original e 01 cópia);</li>
                        <li>Comprovante de residência no município de Sumaré com menos de 60 dias de emissão, em nome
                            dos pais ou
                            do responsável legal pelo (a) candidato (a); (Original e 01 cópia)</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('user.call.pdf') }}" class="btn btn-outline-danger btn-sm me-2">
                        <i class="bi bi-file-earmark-pdf"></i> Gerar PDF
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection