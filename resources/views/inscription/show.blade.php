@extends('layouts.inscription')

@section('page-title', 'Área do Candidato')

@section('content')

    @include('partials.inscriptions.navbar')

    <div class="wrapper">

        <div class="alerts-container animate__animated animate__fadeInUp mb-4">

            {{-- ╔══════════════════════════════════════════╗ --}}
            {{-- ║           RESUMO DA INSCRIÇÃO            ║ --}}
            {{-- ╚══════════════════════════════════════════╝ --}}
            <div class="section-header">
                <div class="section-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>
                <h5 class="section-title">Resumo da inscrição</h5>
            </div>

            <div class="card-modern animate__animated animate__fadeIn">

                <div class="candidate-header">
                    <div class="candidate-avatar">{{ $initials }}</div>
                    <div>
                        <p class="candidate-name">{{ $displayName }} <i
                                class="bi bi-check-circle-fill text-success me-2 fs-5" title="Inscrição confirmada"></i></p>
                    </div>
                </div>

                {{-- Dados principais --}}
                <div class="data-grid">
                    <div class="data-item">
                        <div class="data-label">Inscrição</div>
                        <div class="data-value">#{{ $user->inscription->id }}</div>
                    </div>

                    <div class="data-item data-item-name">
                        <div class="data-label">Nome Completo</div>
                        <div class="data-value">
                            {{ $user?->lgbt?->status === 'accepted' ? $user?->lgbt?->name : $user->name }}</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label">CPF</div>
                        <div class="data-value">{{ $user->cpf }}</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label">E-mail</div>
                        <div class="data-value">{{ $user->email }}</div>
                    </div>
                </div>

                {{-- Nome Social --}}
                @if ($user?->lgbt && !($user?->lgbt?->status === 'accepted'))

                    <div class="info-block">
                        <div class="info-block-label">
                            <i class="bi bi-person-badge"></i> Nome Social / Afetivo
                        </div>
                        <div class="info-block-row">
                            <div>
                                <div style="font-size:0.9rem; font-weight:500;">
                                    {{ $user?->lgbt?->name }}
                                </div>

                                @if ($user?->lgbt?->authorization && Storage::disk('public')->exists($user?->lgbt?->authorization))
                                    <a class="file-link" href="{{ Storage::url($user?->lgbt?->authorization) }}"
                                        target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Visualizar autorização
                                    </a>
                                @endif
                            </div>

                            @if ($user?->lgbt?->status === 'pending')
                                <span class="status-badge analise">
                                    <i class="bi bi-hourglass-split"></i> Em análise
                                </span>
                            @elseif ($user?->lgbt?->status === 'accepted')
                                <span class="status-badge deferido">
                                    <i class="bi bi-check-circle-fill"></i> Deferido
                                </span>
                            @else
                                <span class="status-badge indeferido">
                                    <i class="bi bi-x-circle-fill"></i> Indeferido
                                </span>
                            @endif

                        </div>

                        @if ($user?->lgbt?->status === 'rejected')
                            <div class="modality-note modality-ac d-flex align-items-start gap-2">
                                <i class="bi bi-flag-fill mt-1"></i>
                                <div>
                                    <div>Pedido indeferido.</div>
                                    @if ($user?->lgbt?->observations)
                                        <div class="mt-1"><strong>Motivo:</strong> {{ $user?->lgbt?->observations }}.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @include('partials.inscriptions.appeal', ['type' => 'lgbt'])
                        @endif
                    </div>

                @endif

                {{-- Acessibilidade / PNE --}}
                @if ($user?->pne)

                    <div class="info-block">
                        <div class="info-block-label">
                            <i class="bi bi-universal-access-circle"></i> Pessoa com Necessidades Especiais
                        </div>
                        <div class="info-block-row">
                            <div>
                                <div style="font-size:0.9rem; font-weight:500;">
                                    Condição: {{ $user?->pne?->description }} -
                                    Requer: {{ $user?->pne?->support }}
                                </div>

                                @if ($user?->pne?->report && Storage::disk('public')->exists($user?->pne?->report))
                                    <a class="file-link" href="{{ Storage::url($user?->pne?->report) }}" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Visualizar laudo médico
                                    </a>
                                @else
                                    <span style="font-size:0.78rem; color:#6c757d; display:block; margin-top:4px;">
                                        <i class="bi bi-dash-circle me-1"></i>Laudo não anexado
                                    </span>
                                @endif
                            </div>

                            @if ($user?->pne?->status === 'pending')
                                <span class="status-badge analise">
                                    <i class="bi bi-hourglass-split"></i> Em análise
                                </span>
                            @elseif ($user?->pne?->status === 'accepted')
                                <span class="status-badge deferido">
                                    <i class="bi bi-check-circle-fill"></i> Deferido
                                </span>
                            @else
                                <span class="status-badge indeferido">
                                    <i class="bi bi-x-circle-fill"></i> Indeferido
                                </span>
                            @endif
                        </div>

                        {{-- Modalidade de concorrência (AC x PNE) --}}
                        @if ($user?->pne?->status === 'accepted')
                            <div class="modality-note modality-pne d-flex align-items-start gap-2">
                                <div><i class="bi bi-flag-fill mt-1"></i>
                                    Você concorre às vagas destinadas a
                                    <strong>Pessoas com Necessidades Especiais (PNE)</strong>.
                                    @if ($user->appealOf('pne')?->isAccepted())
                                        <div class="mt-1"><i class="bi bi-check-circle-fill"></i> Deferido após recurso.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif ($user?->pne?->status === 'pending')
                            <div class="modality-note modality-pending d-flex align-items-start gap-2">

                                <div><i class="bi bi-info-circle mt-1"></i>
                                    Enquanto seu pedido está em análise, você concorre provisoriamente às vagas de
                                    <strong>Ampla Concorrência (AC)</strong>.
                                </div>
                            </div>
                        @else
                            <div class="modality-note modality-ac d-flex align-items-start gap-2">
                                <i class="bi bi-flag-fill mt-1"></i>
                                <div>
                                    <div>Pedido indeferido.</div>
                                    @if ($user?->pne?->observations)
                                        <div class="mt-1"><strong>Motivo:</strong> {{ $user?->pne?->observations }}.
                                        </div>
                                    @endif
                                    <div class="mt-1">Você concorre às vagas de
                                        <strong>Ampla Concorrência (AC)</strong>.
                                    </div>
                                </div>
                            </div>

                            @include('partials.inscriptions.appeal', ['type' => 'pne'])
                        @endif
                    </div>

                @endif

                @if ($user->document->isExpeditionOlderThanFiveYears())
                    <div class="info-block info-block-warning">

                        <div class="info-block-label">
                            Importante
                        </div>
                        <div class="info-block-row">
                            <div class="avisos-list">

                                Seu documento de identidade foi expedido há mais de 5 anos
                                (<strong>{{ $user->document->expedition->format('d/m/Y') }}</strong>). Para
                                realização da prova será
                                <strong>obrigatória</strong> a apresentação de documento oficial de identificação
                                com fotografia atual. Caso o documento informado não possua fotografia que permita
                                sua
                                adequada identificação, providencie um novo documento antes da data da prova.
                            </div>
                        </div>
                    </div>
                @endif

            </div>{{-- /card-modern --}}

        </div>

        {{-- Botões de ação --}}
        <div class="actions-row">
            <form action="{{ route('inscription.receipt.to.pdf') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-filetype-pdf me-1"></i> Protocolo de Inscrição
                </button>
            </form>

            @if ($locationReleased)
                @if ($exam)
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#localDeProva">
                        <i class="bi bi-geo-alt me-1"></i> Local de Prova
                    </button>
                @else
                    <div class="alert alert-warning">
                        Não encontramos a sua alocação de prova. Entre em contato com a escola.
                    </div>
                @endif
            @endif

            {{-- ╔══════════════════════════════════════════╗ --}}
            {{-- ║       MODAL — RESULTADO DA PROVA         ║ --}}
            {{-- ╚══════════════════════════════════════════╝ --}}
            @if ($resultReleased)
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#resultadoFinal">
                    <i class="bi bi-list-ol me-1"></i> Ver resultado
                </button>
            @endif

        </div>

        {{-- ╔══════════════════════════════════════════╗ --}}
        {{-- ║          MODAL — LOCAL DE PROVA          ║ --}}
        {{-- ╚══════════════════════════════════════════╝ --}}
        @if ($locationReleased && $exam)
            <div class="modal fade" id="localDeProva" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content" style="border-radius:12px; border:1px solid #dee2e6;">

                        <div class="modal-header" style="border-bottom:1px solid #f1f3f5;">
                            <h5 class="modal-title fw-semibold">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>Local de Prova
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">

                            {{-- Local --}}
                            <div class="info-block mb-3">
                                <div class="info-block-label"><i class="bi bi-building"></i> Local</div>
                                <div style="font-size:0.95rem; font-weight:600; margin-bottom:4px;">
                                    {{ $exam->location?->name }}
                                </div>
                                <div style="font-size:0.82rem; color:#6c757d;">
                                    {{ $exam->location?->address }}
                                </div>
                            </div>

                            {{-- Sala / Data / Hora --}}
                            <div class="call-data-grid"
                                style="grid-template-columns: repeat(auto-fill, minmax(130px,1fr));">
                                <div class="call-data-item" style="border-color:#bee3f8; background:#ebf8ff;">
                                    <div class="call-data-label" style="color:#2b6cb0;">Sala</div>
                                    <div class="call-data-value" style="color:#1a365d;">{{ $exam->room_number }}
                                    </div>
                                </div>
                                <div class="call-data-item" style="border-color:#bee3f8; background:#ebf8ff;">
                                    <div class="call-data-label" style="color:#2b6cb0;">Data</div>
                                    <div class="call-data-value" style="color:#1a365d;">
                                        {{ $process->exam_date?->format('d/m/Y') ?? '—' }}
                                    </div>
                                </div>
                                <div class="call-data-item" style="border-color:#bee3f8; background:#ebf8ff;">
                                    <div class="call-data-label" style="color:#2b6cb0;">Horário</div>
                                    <div class="call-data-value" style="color:#1a365d;">
                                        {{ $process->exam_time ? \Carbon\Carbon::parse($process->exam_time)->format('H:i') : '—' }}
                                    </div>
                                </div>
                            </div>

                            @if ($isPneRoom)
                                <div class="alert alert-warning py-2 px-3 mt-2"
                                    style="border-radius:8px; font-size:0.85rem;">
                                    <i class="bi bi-universal-access-circle me-1"></i>
                                    <strong>Sala de Atendimento Especializado</strong>
                                </div>
                            @endif

                            {{-- Instruções --}}
                            <div class="mt-3">
                                <div class="modal-section-title">Instruções</div>
                                <ul class="docs-list-modern">
                                    <li>Chegue com <strong>30 minutos de antecedência</strong>.</li>
                                    <li>Leve documento com foto e caneta azul ou preta.</li>
                                    <li style="color:#991b1b; font-weight:600;">
                                        <i class="bi bi-ban me-1"></i>
                                        Não é permitido usar dispositivos eletrônicos durante a prova.
                                    </li>
                                </ul>
                            </div>

                        </div>

                        <div class="modal-footer" style="border-top:1px solid #f1f3f5; gap:8px;">
                            <a href="{{ route('inscription.card.exam') }}" class="btn btn-primary btn-sm"
                                target="_blank">
                                <i class="bi bi-download me-1"></i> Baixar PDF
                            </a>
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fechar</button>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        {{-- ╔══════════════════════════════════════════╗ --}}
        {{-- ║          MODAL: RESULTADO DA PROVA       ║ --}}
        {{-- ╚══════════════════════════════════════════╝ --}}
        @if ($resultReleased)
            <div class="modal fade" id="resultadoFinal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content" style="border-radius:12px; border:1px solid #dee2e6;">

                        <div class="modal-header" style="border-bottom:1px solid #f1f3f5;">
                            <h5 class="modal-title fw-semibold">
                                <i class="bi bi-list-ol text-primary me-2"></i>Resultado final
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">

                            @if ($result && $result->score !== null && $result->ranking !== null)

                                {{-- Posição e pontuação: todo candidato com nota vê as suas --}}
                                <div class="call-data-grid"
                                    style="grid-template-columns: repeat(auto-fill, minmax(130px,1fr));">
                                    <div class="call-data-item" style="border-color:#bee3f8; background:#ebf8ff;">
                                        <div class="call-data-label" style="color:#2b6cb0;">Posição</div>
                                        <div class="call-data-value" style="color:#1a365d;">{{ $result->ranking }}º</div>
                                    </div>
                                    <div class="call-data-item" style="border-color:#bee3f8; background:#ebf8ff;">
                                        <div class="call-data-label" style="color:#2b6cb0;">Pontuação</div>
                                        <div class="call-data-value" style="color:#1a365d;">{{ $result->score }}</div>
                                    </div>
                                </div>

                                {{-- Contexto: evita a leitura de "classificado = vaga garantida" --}}
                                @if ($resultSummary)
                                    <p class="text-muted small mt-3 mb-0">
                                        Total de classificados: <strong>{{ $resultSummary['classified'] }}</strong>
                                        &middot;
                                        Vagas oferecidas: <strong>{{ $resultSummary['vacancies'] }}</strong>
                                    </p>
                                @endif

                                @if ($result->classified)
                                    <div class="alert alert-success py-2 px-3 mt-3"
                                        style="border-radius:8px; font-size:0.85rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        <strong>Classificado(a).</strong>
                                        A convocação para matrícula segue a ordem de classificação e a
                                        disponibilidade de vagas, conforme o edital.
                                    </div>
                                @else
                                    <div class="alert alert-secondary py-2 px-3 mt-3"
                                        style="border-radius:8px; font-size:0.85rem;">
                                        <i class="bi bi-info-circle-fill me-1"></i>
                                        <strong>Não classificado(a).</strong>
                                        Sua posição ficou fora do limite de classificação previsto no edital, e o
                                        candidato não classificado está eliminado do Processo Seletivo.
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning py-2 px-3 mb-0"
                                    style="border-radius:8px; font-size:0.85rem;">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    Não encontramos um resultado para a sua inscrição. Se você realizou a prova,
                                    entre em contato com a escola.
                                </div>

                            @endif

                        </div>

                        <div class="modal-footer" style="border-top:1px solid #f1f3f5;">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Fechar</button>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>{{-- wrapper --}}

@endsection
