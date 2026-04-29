@extends('dash.master')

@section('page-title', config('app.name') . ' ' . $calendar->year . ' | Área do Candidato')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/user/perfil.css') }}">
@endpush

@section('dash-content')

<div class="candidato-wrap">

    {{-- ╔══════════════════════════════════════════╗ --}}
    {{-- ║         CONVOCAÇÃO PARA MATRÍCULA        ║ --}}
    {{-- ╚══════════════════════════════════════════╝ --}}
    @if (auth()->user()->hasConfirmedCall())
        <div class="card-call animate__animated animate__fadeInDown">
            <div class="card-call-header">
                <i class="bi bi-megaphone-fill" style="font-size:1.1rem; color:#f59e0b;"></i>
                <h5 class="card-call-title">Convocação para matrícula</h5>
                <span class="status-badge analise">Chamada nº {{ $call?->call_number }}</span>
            </div>

            <div class="call-data-grid">
                <div class="call-data-item">
                    <div class="call-data-label"><i class="bi bi-calendar3 me-1"></i>Data</div>
                    <div class="call-data-value">
                        {{ Carbon\Carbon::parse($call?->date)->format('d/m/Y') }}
                    </div>
                </div>
                <div class="call-data-item">
                    <div class="call-data-label"><i class="bi bi-clock me-1"></i>Horário</div>
                    <div class="call-data-value">
                        {{ Carbon\Carbon::parse($call?->time)->format('H:i') }}
                    </div>
                </div>
            </div>

            <p class="mb-2" style="font-size:0.8rem; color:#92400e;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                O não comparecimento na data e horário informados acarretará na <strong>perda da vaga</strong>.
            </p>

            <button class="btn btn-warning btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#callDetailModal">
                <i class="bi bi-search me-1"></i> Ver detalhes da convocação
            </button>
        </div>
    @endif

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

        {{-- Cabeçalho com avatar --}}
        @php
            $displayName = (auth()->user()->social_name_option && auth()->user()->authorization_accepted == 1)
                ? auth()->user()->social_name
                : auth()->user()->name;
            $nameParts = explode(' ', trim($displayName));
            $initials  = strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1] ?? '', 0, 1));
        @endphp

        <div class="candidate-header">
            <div class="candidate-avatar">{{ $initials }}</div>
            <div>
                <p class="candidate-name">{{ $displayName }}</p>
                <div class="candidate-meta">
                    <span class="inscription-badge">
                        <i class="bi bi-hash"></i>
                        Inscrição <strong>{{ auth()->user()->inscription->id }}</strong>
                    </span>
                </div>
            </div>
        </div>

        {{-- Dados principais --}}
        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Nome Completo</div>
                <div class="data-value">{{ auth()->user()->name }}</div>
            </div>
            <div class="data-item">
                <div class="data-label">CPF</div>
                <div class="data-value">{{ auth()->user()->cpf }}</div>
            </div>
            <div class="data-item">
                <div class="data-label">Sexo</div>
                <div class="data-value">{{ auth()->user()->gender }}</div>
            </div>
        </div>

        {{-- Nome Social --}}
        @if (auth()->user()->social_name_option)
            <div class="info-block">
                <div class="info-block-label">
                    <i class="bi bi-person-badge"></i> Nome Social / Afetivo
                </div>
                <div class="info-block-row">
                    <div>
                        <div style="font-size:0.9rem; font-weight:500;">
                            {{ auth()->user()->social_name }}
                        </div>

                        @if (auth()->user()->authorization)
                            <a class="file-link"
                               href="{{ asset('storage/' . auth()->user()->authorization) }}"
                               target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Visualizar autorização
                            </a>
                        @endif
                    </div>

                    @if (auth()->user()->authorization_accepted == 1)
                        <span class="status-badge deferido">
                            <i class="bi bi-check-circle-fill"></i> Deferido
                        </span>
                    @elseif (auth()->user()->authorization_accepted == 2)
                        <div class="text-end">
                            <span class="status-badge indeferido">
                                <i class="bi bi-x-circle-fill"></i> Indeferido
                            </span>
                            @if (auth()->user()->authorization_rejection_reason)
                                <p class="mt-1 mb-0" style="font-size:0.78rem; color:#991b1b;">
                                    {{ auth()->user()->authorization_rejection_reason }}
                                </p>
                            @endif
                        </div>
                    @else
                        <span class="status-badge analise">
                            <i class="bi bi-hourglass-split"></i> Em análise
                        </span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Acessibilidade / PNE --}}
        @if (auth()->user()->user_detail?->pne)
            <div class="info-block">
                <div class="info-block-label">
                    <i class="bi bi-universal-access-circle"></i> Necessidade de Acessibilidade
                </div>
                <div class="info-block-row">
                    <div>
                        <div style="font-size:0.9rem; font-weight:500;">
                            {{ auth()->user()->user_detail?->accessibility }}
                        </div>

                        @if (!empty(auth()->user()->user_detail?->pne_report))
                            <a class="file-link"
                               href="{{ asset('storage/' . auth()->user()->user_detail?->pne_report) }}"
                               target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Visualizar laudo médico
                            </a>
                        @else
                            <span style="font-size:0.78rem; color:#6c757d; display:block; margin-top:4px;">
                                <i class="bi bi-dash-circle me-1"></i>Laudo não anexado
                            </span>
                        @endif
                    </div>

                    @if (auth()->user()->user_detail?->pne_report_accepted == 1)
                        <span class="status-badge deferido">
                            <i class="bi bi-check-circle-fill"></i> Deferido
                        </span>
                    @elseif (auth()->user()->user_detail?->pne_report_accepted == 2)
                        <div class="text-end">
                            <span class="status-badge indeferido">
                                <i class="bi bi-x-circle-fill"></i> Indeferido
                            </span>
                            @if (auth()->user()->user_detail?->pne_report_rejection_reason)
                                <p class="mt-1 mb-0" style="font-size:0.78rem; color:#991b1b;">
                                    {{ auth()->user()->user_detail?->pne_report_rejection_reason }}
                                </p>
                            @endif
                        </div>
                    @else
                        <span class="status-badge analise">
                            <i class="bi bi-hourglass-split"></i> Em análise
                        </span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Botões de ação --}}
        <div class="actions-row">
            <form action="{{ route('receipt.inscription') }}" method="post" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-filetype-pdf me-1"></i> Gerar PDF
                </button>
            </form>

            @if ($settings->location)
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#localDeProva">
                    <i class="bi bi-geo-alt me-1"></i> Local de Prova
                </button>
            @endif

            @if ($settings->result)
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#resultadoDeProva">
                    <i class="bi bi-list-ol me-1"></i> Classificação
                </button>
            @endif
        </div>

    </div>{{-- /card-modern --}}


    {{-- ╔══════════════════════════════════════════╗ --}}
    {{-- ║          MODAL — LOCAL DE PROVA          ║ --}}
    {{-- ╚══════════════════════════════════════════╝ --}}
    @if ($settings->location && $exam)
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

                        {{-- Candidato --}}
                        <div class="info-block mb-3">
                            <div class="info-block-label"><i class="bi bi-person"></i> Candidato</div>
                            <div style="font-size:0.95rem; font-weight:600;">
                                {{ auth()->user()->social_name ?: auth()->user()->name }}
                            </div>
                        </div>

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
                        <div class="call-data-grid" style="grid-template-columns: repeat(auto-fill, minmax(130px,1fr));">
                            <div class="call-data-item" style="border-color:#bee3f8; background:#ebf8ff;">
                                <div class="call-data-label" style="color:#2b6cb0;">Sala</div>
                                <div class="call-data-value" style="color:#1a365d;">{{ $exam->room_number }}</div>
                            </div>
                            <div class="call-data-item" style="border-color:#bee3f8; background:#ebf8ff;">
                                <div class="call-data-label" style="color:#2b6cb0;">Data</div>
                                <div class="call-data-value" style="color:#1a365d;">
                                    {{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="call-data-item" style="border-color:#bee3f8; background:#ebf8ff;">
                                <div class="call-data-label" style="color:#2b6cb0;">Horário</div>
                                <div class="call-data-value" style="color:#1a365d;">
                                    {{ \Carbon\Carbon::parse($exam->exam_time)->format('H:i') }}
                                </div>
                            </div>
                        </div>

                        @if ($exam->pne ?? false)
                            <div class="alert alert-warning py-2 px-3 mt-2" style="border-radius:8px; font-size:0.85rem;">
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
                        <a href="{{ route('card.exam') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i> Baixar PDF
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- ╔══════════════════════════════════════════╗ --}}
    {{-- ║       MODAL — RESULTADO DA PROVA         ║ --}}
    {{-- ╚══════════════════════════════════════════╝ --}}
    @if ($settings->result)
        <div class="modal fade" id="resultadoDeProva" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius:12px; border:none;">

                    <div class="modal-body p-3">
                        <div class="result-card-modern">

                            <div class="result-card-header">
                                <h5><i class="bi bi-list-ol me-2"></i>Resultado da Prova Objetiva</h5>
                                <span class="badge bg-light text-primary">Ano {{ $calendar->year }}</span>
                            </div>

                            <div class="result-body">
                                <p class="text-muted mb-1" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em;">Candidato(a)</p>
                                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                                <p style="font-size:0.82rem; color:#6c757d;">CPF: {{ $user->cpf }}</p>

                                <hr class="my-3">

                                <p class="text-muted mb-1" style="font-size:0.8rem;">Nota obtida</p>
                                <div class="result-score">{{ $examResult?->score }}</div>

                                <p class="text-muted mt-3 mb-1" style="font-size:0.8rem;">Classificação Geral</p>
                                <div class="result-ranking">{{ $examResult?->ranking }}º</div>
                            </div>

                            <div style="padding:0.75rem 1.25rem; background:#f8f9fa; border-top:1px solid #e9ecef; font-size:0.78rem; color:#6c757d; text-align:center; border-radius:0 0 12px 12px;">
                                Critério de desempate: idade do candidato (mais jovem tem prioridade).
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer" style="border-top:1px solid #f1f3f5; gap:8px;">
                        <a href="{{ route('card.result') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Gerar PDF
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    @endif


    {{-- ╔══════════════════════════════════════════╗ --}}
    {{-- ║      MODAL — DETALHES DA CONVOCAÇÃO      ║ --}}
    {{-- ╚══════════════════════════════════════════╝ --}}
    @if ($call && auth()->user()->hasConfirmedCall())
        <div class="modal fade" id="callDetailModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content" style="border-radius:12px; border:1px solid #fcd34d;">

                    <div class="modal-header" style="background:#fffbeb; border-bottom:1px solid #fef3c7;">
                        <h5 class="modal-title fw-semibold" style="color:#92400e;">
                            <i class="bi bi-megaphone-fill me-2" style="color:#f59e0b;"></i>
                            Detalhes da Convocação
                        </h5>
                    </div>

                    <div class="modal-body p-4">

                        {{-- Dados da chamada --}}
                        <div class="call-data-grid mb-3" style="grid-template-columns: repeat(auto-fill, minmax(130px,1fr));">
                            <div class="call-data-item">
                                <div class="call-data-label">Candidato</div>
                                <div class="call-data-value" style="font-size:0.85rem;">
                                    {{ auth()->user()->social_name ?? auth()->user()->name }}
                                </div>
                            </div>
                            <div class="call-data-item">
                                <div class="call-data-label">Chamada nº</div>
                                <div class="call-data-value">{{ $call?->call_number }}</div>
                            </div>
                            <div class="call-data-item">
                                <div class="call-data-label">Data</div>
                                <div class="call-data-value">
                                    {{ Carbon\Carbon::parse($call?->date)->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="call-data-item">
                                <div class="call-data-label">Horário</div>
                                <div class="call-data-value">
                                    {{ Carbon\Carbon::parse($call?->time)->format('H:i') }}
                                </div>
                            </div>
                        </div>

                        {{-- Local da matrícula --}}
                        <div class="modal-section-title">Local da Matrícula</div>
                        <div class="info-block mb-3">
                            <p class="mb-1" style="font-size:0.88rem; font-weight:600;">
                                R. Geraldo de Souza, 221 — Jardim São Carlos
                            </p>
                            <p class="mb-1" style="font-size:0.82rem; color:#6c757d;">Sumaré - SP, 13170-232</p>
                            <p class="mb-1" style="font-size:0.82rem;">
                                <i class="bi bi-telephone me-1 text-muted"></i>(19) 3873-2605
                            </p>
                            <p class="mb-0" style="font-size:0.82rem;">
                                <i class="bi bi-clock me-1 text-muted"></i>Funcionamento: 14:00 às 23:00
                            </p>
                        </div>

                        {{-- Documentos necessários --}}
                        <div class="modal-section-title">Documentos necessários (item 7.4 do edital)</div>
                        <div class="alert alert-warning py-2 px-3 mb-2" style="border-radius:8px; font-size:0.82rem;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            A falta de documentação ou o não comparecimento resultará na <strong>perda da vaga</strong>.
                        </div>
                        <ol class="docs-list-modern">
                            <li>Declaração de Conclusão do Ensino Fundamental ou Histórico Escolar (original + 1 cópia)</li>
                            <li>1 foto 3×4</li>
                            <li>Documento de identidade com foto — RG/CIN ou RNE para estrangeiros (original + 1 cópia)</li>
                            <li>CPF (original + 1 cópia)</li>
                            <li>Certidão de nascimento (original + 1 cópia)</li>
                            <li>Carteira de vacinação (original + 1 cópia)</li>
                            <li>Comprovante de residência em Sumaré com menos de 60 dias, em nome dos pais ou responsável legal (original + 1 cópia)</li>
                        </ol>

                    </div>

                    <div class="modal-footer" style="border-top:1px solid #fef3c7; gap:8px;">
                        <a href="{{ route('card.call') }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Gerar PDF
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Fechar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>{{-- /candidato-wrap --}}

@endsection