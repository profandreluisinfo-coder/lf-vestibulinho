@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF - Detalhes da Inscrição')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/inscriptions/show.css') }}">
@endpush

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="fi-header">
            <div>
                <p class="fi-header-title">Detalhes da Inscrição</p>
                <p class="fi-header-sub">Processo Seletivo — Visualização Detalhada</p>
            </div>
            <span class="fi-active-badge">Ativo</span>
        </div>

        {{-- Inscrição --}}
        <p class="fi-section-label">Dados da Inscrição</p>
        <div class="fi-card mb-4">
            <div class="fi-card-header">
                <div class="fi-card-icon">📄</div>
                <p class="fi-card-title">Inscrição</p>
            </div>
            <div class="d-flex align-items-center gap-4 flex-wrap p-3">
                <div class="d-flex flex-column gap-1">
                    <span class="fi-section-label mb-0">Número</span>
                    <span class="fi-num-badge">{{ $user->inscription->id }}</span>
                </div>
                <div>
                    <span class="fi-section-label d-block mb-1">Curso Pretendido</span>
                    <span class="fi-course-tag">{{ $user->inscription->course->description }}</span>
                </div>
                <div>
                    <span class="fi-section-label d-block mb-1">Data</span>
                    <span class="fi-row-value">{{ $user->inscription->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Identificação + Documentos --}}
        <p class="fi-section-label">Dados Pessoais</p>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="fi-card h-100">
                    <div class="fi-card-header">
                        <div class="fi-card-icon">🧑‍💼</div>
                        <p class="fi-card-title">Identificação</p>
                    </div>
                    <div class="fi-row"><span class="fi-row-label">CPF</span><span
                            class="fi-row-value">{{ $user->cpf }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Nome</span><span
                            class="fi-row-value">{{ $user?->lgbt?->status === 'accepted' ? $user?->lgbt?->name . ' (LGBTQIA+)' : $user->name }}</span>
                    </div>
                    <div class="fi-row"><span class="fi-row-label">Gênero</span><span
                            class="fi-row-value">{{ $user->gender }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">E-mail</span><span class="fi-row-value"
                            style="word-break:break-all">{{ $user->email }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Telefone</span><span
                            class="fi-row-value">{{ $user->phone }}</span></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="fi-card h-100">
                    <div class="fi-card-header">
                        <div class="fi-card-icon">📑</div>
                        <p class="fi-card-title">Documentos Pessoais</p>
                    </div>
                    <div class="fi-row"><span class="fi-row-label">Nacionalidade</span><span
                            class="fi-row-value">{{ $user->nationality }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Tipo Doc.</span><span
                            class="fi-row-value">{{ $user->document->type }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Nº Documento</span><span
                            class="fi-row-value">{{ $user->document->number }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Nascimento</span><span
                            class="fi-row-value">{{ $user->birth }}</span></div>
                </div>
            </div>
        </div>

        {{-- Certidão --}}
        <p class="fi-section-label">Certidão de Nascimento</p>
        <div class="fi-card mb-4">
            <div class="fi-card-header">
                <div class="fi-card-icon">📝</div>
                <p class="fi-card-title">Certidão</p>
            </div>
            <div class="fi-row"><span class="fi-row-label">Certidão Nº</span><span
                    class="fi-row-value">{{ $user->certificate->number }}</span></div>
            @if ($user->certificate->type !== 1)
                <div class="fi-row"><span class="fi-row-label">Folhas</span><span
                        class="fi-row-value">{{ $user->certificate->fls }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Livro</span><span
                        class="fi-row-value">{{ $user->certificate->book }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Município</span><span
                        class="fi-row-value">{{ $user->certificate->city }}</span></div>
            @endif
        </div>

        {{-- Filiação --}}
        <p class="fi-section-label">Família e Responsável</p>
        <div class="fi-card mb-4">
            <div class="fi-card-header">
                <div class="fi-card-icon">👨‍👩‍👧‍👦</div>
                <p class="fi-card-title">Filiação / Responsável Legal</p>
            </div>
            <div class="fi-row">
                <span class="fi-row-label">Mãe</span>
                <span class="fi-row-value">{{ $user->mother->name }}
                    @if ($user?->mother?->phone)
                        <span class="fi-phone">☎ {{ $user?->mother?->phone }}</span>
                    @endif
                </span>
            </div>
            @if ($user?->father)
                <div class="fi-row">
                    <span class="fi-row-label">Pai</span>
                    <span class="fi-row-value">{{ $user?->father?->name }}
                        @if ($user?->father?->phone)
                            <span class="fi-phone">☎ {{ $user?->father?->phone }}</span>
                        @endif
                    </span>
                </div>
            @endif
            @if ($user?->guardian)
                <div class="fi-row fi-row-highlight">
                    <span class="fi-row-label">Responsável</span>
                    <span class="fi-row-value">{{ $user?->guardian?->name }}</span>
                </div>
                <div class="fi-row fi-row-highlight">
                    <span class="fi-row-label">Parentesco</span>
                    <span class="fi-row-value">{{ $user?->guardian?->degree }}
                        @if ($user?->guardian?->kinship)
                            — {{ $user?->guardian?->kinship }}
                        @endif
                        <span class="fi-phone">☎ {{ $user?->guardian?->phone }}</span>
                    </span>
                </div>
            @endif
            <div class="fi-row"><span class="fi-row-label">E-mail Família</span><span class="fi-row-value"
                    style="word-break:break-all">{{ $user->parent_email->address }}</span></div>
        </div>

        {{-- Escolaridade + Endereço --}}
        <p class="fi-section-label">Formação e Endereço</p>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="fi-card h-100">
                    <div class="fi-card-header">
                        <div class="fi-card-icon">🎓</div>
                        <p class="fi-card-title">Escolaridade</p>
                    </div>
                    <div class="fi-row"><span class="fi-row-label">Escola</span><span
                            class="fi-row-value">{{ $user->academic->school }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">RA</span><span
                            class="fi-row-value">{{ $user->academic->ra }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Cidade/UF</span><span
                            class="fi-row-value">{{ $user->academic->city }} /
                            {{ $user->academic->state }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Conclusão</span><span
                            class="fi-row-value">{{ $user->academic->year }}</span></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="fi-card h-100">
                    <div class="fi-card-header">
                        <div class="fi-card-icon">🏠</div>
                        <p class="fi-card-title">Endereço</p>
                    </div>
                    <div class="fi-row"><span class="fi-row-label">CEP</span><span
                            class="fi-row-value">{{ $user->zip }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Rua / Nº</span><span
                            class="fi-row-value">{{ $user->street }}, {{ $user->number }}
                            @if ($user?->complement)
                                — {{ $user?->complement }}
                            @endif
                        </span></div>
                    <div class="fi-row"><span class="fi-row-label">Bairro</span><span
                            class="fi-row-value">{{ $user->burgh }}</span></div>
                    <div class="fi-row"><span class="fi-row-label">Cidade/UF</span><span
                            class="fi-row-value">{{ $user->city }} / {{ $user->state }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if ($user?->pne || $user?->nis || $user?->health_issue)
            {{-- Complementares --}}
            <p class="fi-section-label">Informações Complementares</p>

            <div class="row g-3 mb-4">

                @if ($user?->pne)
                    <div class="col-md-6">
                        <div class="fi-card h-100 fi-card-special">
                            <div class="fi-card-header">
                                <div class="fi-card-icon">♿</div>
                                <p class="fi-card-title">Educação Especial</p>
                                <div class="ms-auto">
                                    @if ($user?->pne?->status === 'accepted')
                                        <span class="fi-pill fi-pill-yes">Elegível</span>
                                    @elseif ($user?->pne?->status === 'pending')
                                        <span class="fi-pill fi-pill-warn">Pendente</span>
                                    @else
                                        <span class="fi-pill fi-pill-no">Não elegível</span>
                                    @endif
                                </div>
                            </div>
                            <div class="fi-row">
                                <span class="fi-row-label">Necessidade</span>
                                <span class="fi-row-value">{{ $user?->pne?->description }}</span>
                                <span class="fi-row-value flex-fill"><a
                                        href="{{ Storage::url($user?->lgbt?->authorization) }}" target="_blank"
                                        class="small float-end">
                                        <i class="bi bi-file-earmark-check me-1"></i>Ver autorização
                                    </a>
                            </div>
                            <div class="fi-row">
                                <span class="fi-row-label">Auxílio</span>
                                <span class="fi-row-value">{{ $user?->pne?->support }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($user?->nis)
                    <div class="col-md-6">
                        <div class="fi-card h-100">
                            <div class="fi-card-header">
                                <div class="fi-card-icon">🤝</div>
                                <p class="fi-card-title">Programas Sociais</p>
                                <div class="ms-auto">
                                    <span class="fi-pill fi-pill-yes">Beneficiário</span>
                                </div>
                            </div>
                            <div class="fi-row"><span class="fi-row-label">NIS</span><span
                                    class="fi-row-value">{{ $user?->nis }}</span></div>
                        </div>
                    </div>
                @endif

                @if ($user?->health_issue)
                    <div class="col-12">
                        <div class="fi-card">
                            <div class="fi-card-header">
                                <div class="fi-card-icon">💡</div>
                                <p class="fi-card-title">Saúde e Alergias</p>
                                <div class="ms-auto">
                                    <span class="fi-pill fi-pill-warn">Atenção</span>
                                </div>
                            </div>
                            <div class="fi-row"><span class="fi-row-label">Descrição</span><span
                                    class="fi-row-value">{{ $user?->health_issue }}</span></div>
                        </div>
                    </div>
                @endif

            </div>

        @endif

    </div>
@endsection