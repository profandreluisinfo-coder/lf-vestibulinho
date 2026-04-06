@extends('dash.master')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Área do Candidato')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/user/perfil.css') }}">
@endpush

@section('dash-content')

    {{-- CONVOCADO PARA MATRÍCULA --}}
    @if (auth()->user()->hasConfirmedCall())

        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-clipboard-check text-primary fs-4 me-2 animate__animated animate__fadeIn"></i>
            <h5 class="m-0 fw-semibold">Convocação para Matrícula</h5>
        </div>
        <div class="table-responsive mb-5">
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
                    Compareça na data e horário informados para realizar sua matrícula. O não comparecimento acarretará na perda
                    da vaga.
                </span>
            </div>
            <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                data-bs-target="#callDetailModal">
                <i class="bi bi-search animate__animated animate__fadeIn"></i> Detalhes da convocação
            </a>
        </div>

    @endif
    
    {{-- RESUMO DA INSCRIÇÃO --}}
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
                @if (auth()->user()->social_name_option)
                    <tr>
                        <th>Nome Social/Afetivo</td>
                        <td>{{ auth()->user()->social_name }}</td>
                    </tr>
                    <tr>
                        <th style="width: 35%;">Apresentou autorização dos pais/responsáveis para uso do nome social/afetivo?</td>
                        <td>
                            {{ auth()->user()->authorization ? 'Sim' : 'Não' }}
                            <a class="btn btn-link text-decoration-none p-0 ms-3"
                            href="{{ asset('storage/' . auth()->user()->authorization) }}"
                            target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Visualizar
                            </a>
                        </td>
                    </tr>
                    <tr>
                    <th>Situação</th>
                        <td>
                            @if (auth()->user()->authorization_accepted == 1)
                                <span class="text-success fw-semibold"><i class="bi bi-info-circle me-1"></i>Deferido</span>
                            @elseif (auth()->user()->authorization_accepted == 2)
                                <span class="text-danger fw-semibold"><i class="bi bi-info-circle me-1"></i>Indeferido.</span>
                                @if (auth()->user()->authorization_rejection_reason)
                                    <span class="text-danger">{{ auth()->user()->authorization_rejection_reason }}</span>
                                @endif
                            @else
                                <span class="text-danger fw-semibold"><i class="bi bi-info-circle me-1"></i>Em análise</span>
                            @endif
                        </td>
                    </tr>
                @endif
                <tr>
                    <th>Sexo</td>
                    <td>{{ auth()->user()->gender }}</td>
                </tr>
                <tr>
                    <th>CPF</td>
                    <td>{{ auth()->user()->cpf }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if (auth()->user()->user_detail?->pne)

        <div class="table-responsive mt-4 mt-lg-1">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th colspan="2">
                            Necessidade de acessibilidade indicada pelo candidato
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th style="width: 35%;">Descrição</th>
                        <td>
                            {{ auth()->user()->user_detail?->accessibility }}
                        </td>
                    </tr>

                    <tr>
                        <th>Anexou laudo/relatório médico?</th>
                        <td>
                            <div class="d-flex">
                            {{ !empty(auth()->user()->user_detail?->pne_report) ? 'Sim' : 'Não' }}

                            @if(!empty(auth()->user()->user_detail?->pne_report))
                                
                                <a class="btn btn-link text-decoration-none p-0 ms-3"
                                href="{{ asset('storage/' . auth()->user()->user_detail?->pne_report) }}"
                                target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> Visualizar
                                </a>

                            @endif
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>Situação</th>
                        <td>
                            @if (auth()->user()->user_detail?->pne_report_accepted == 1)
                                <span class="text-success fw-semibold"><i class="bi bi-info-circle me-1"></i>Deferido</span>
                            @elseif (auth()->user()->user_detail?->pne_report_accepted == 2)
                                <span class="text-danger fw-semibold"><i class="bi bi-info-circle me-1"></i>Indeferido.</span>
                                @if (auth()->user()->user_detail?->pne_report_rejection_reason)
                                    <span class="text-danger">{{ auth()->user()->user_detail?->pne_report_rejection_reason }}</span>
                                @endif
                            @else
                                <span class="text-danger fw-semibold"><i class="bi bi-info-circle me-1"></i>Em análise</span>
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    @endif

    <div class="d-flex flex-column flex-sm-row gap-2">
        
        <form action="{{ route('receipt.inscription') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm"><i
                    class="bi bi-filetype-pdf me-2"></i>Gerar PDF</button>
        </form>

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
    </div> 

    <!-- Modal de exibição de local de prova -->
    @if ($settings->location && $exam)

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
                            <a href="{{ route('card.exam') }}" class="btn btn-outline-primary btn-sm">
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

    <!-- Modal de exibição de classificação na prova-->
    @if ($settings->result)

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
                            <a href="{{ route('card.result') }}" class="btn btn-outline-primary me-2">
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

    <!-- Modal de exibição de detalhes da convocação -->
    @if ($call && auth()->user()->hasConfirmedCall())

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
                        <a href="{{ route('card.call') }}" class="btn btn-outline-danger btn-sm me-2">
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