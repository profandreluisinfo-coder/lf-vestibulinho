@extends('layouts.admin')

@section('page-title', 'Ficha de Inscrição')

@push('styles')
<style>
    .fi-header {
        background: var(--grad-navy);
        border-radius: var(--radius-lg);
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .fi-header-title {
        font-family: var(--font-heading);
        font-size: 18px;
        font-weight: 800;
        color: #fff;
        margin: 0;
    }
    .fi-header-sub { font-size: 13px; color: rgba(255,255,255,0.6); margin: 0; }
    .fi-active-badge {
        background: rgba(0,168,150,0.2);
        color: #00D4BF;
        border: 1px solid rgba(0,168,150,0.35);
        border-radius: var(--radius-pill);
        font-size: 12px;
        font-weight: 600;
        padding: 4px 14px;
    }
    .fi-section-label {
        font-family: var(--font-heading);
        font-size: 10px;
        font-weight: 700;
        color: var(--color-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        padding-left: 2px;
    }
    .fi-card {
        background: #fff;
        border: 0.5px solid var(--color-light-mid);
        border-radius: var(--radius-md);
        overflow: hidden;
        margin-bottom: 0;
    }
    .fi-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 0.5px solid var(--color-light);
        background: #F8FAFD;
    }
    .fi-card-icon {
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        background: var(--color-light);
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; flex-shrink: 0;
    }
    .fi-card-title {
        font-family: var(--font-heading);
        font-size: 13px; font-weight: 700;
        color: var(--color-navy); margin: 0;
    }
    .fi-row {
        display: flex; align-items: baseline;
        padding: 8px 16px; gap: 8px;
        border-bottom: 0.5px solid var(--color-light);
    }
    .fi-row:last-child { border-bottom: none; }
    .fi-row-label {
        font-size: 11px; font-weight: 600;
        color: var(--color-muted); min-width: 120px;
        flex-shrink: 0; text-transform: uppercase; letter-spacing: 0.4px;
    }
    .fi-row-value { font-size: 13px; color: var(--text-dark); font-weight: 500; }
    .fi-num-badge {
        background: var(--grad-teal);
        color: #fff; border-radius: var(--radius-sm);
        font-size: 20px; font-weight: 800;
        padding: 6px 14px;
        font-family: var(--font-heading);
    }
    .fi-course-tag {
        background: var(--color-light);
        color: var(--color-navy-light);
        border-radius: var(--radius-sm);
        font-size: 12px; font-weight: 700;
        padding: 4px 12px; display: inline-block;
    }
    .fi-pill {
        display: inline-flex; align-items: center;
        gap: 5px; border-radius: var(--radius-pill);
        font-size: 11px; font-weight: 700; padding: 3px 10px;
    }
    .fi-pill-yes { background: #d1fae5; color: #065f46; }
    .fi-pill-no  { background: var(--color-light); color: var(--color-muted); }
    .fi-pill-warn { background: #fef3c7; color: #92400e; }
    .fi-row-highlight {
        background: #fffbeb;
        border-left: 3px solid var(--color-amber) !important;
        padding-left: 13px !important;
    }
    .fi-card-special .fi-card-header { background: #f0fdfa; }
    .fi-card-special { border-left: 3px solid var(--color-teal); }
    .fi-phone { color: var(--color-muted); font-size: 12px; margin-left: 8px; }
</style>
@endpush

@section('dash-content')
<div class="container">

    {{-- Header --}}
    <div class="fi-header">
        <div>
            <p class="fi-header-title">Ficha de Inscrição</p>
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
                <span class="fi-row-value">{{ \Carbon\Carbon::parse($user->inscription->created_at)->format('d/m/Y') }}</span>
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
                <div class="fi-row"><span class="fi-row-label">CPF</span><span class="fi-row-value">{{ $user->cpf }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Nome</span><span class="fi-row-value">{{ $user->name }}</span></div>
                @if ($user->social_name)
                <div class="fi-row fi-row-highlight"><span class="fi-row-label">Nome Social</span><span class="fi-row-value">{{ $user->social_name }}</span></div>
                @endif
                <div class="fi-row"><span class="fi-row-label">Gênero</span><span class="fi-row-value">{{ $user->gender }}</span></div>
                <div class="fi-row"><span class="fi-row-label">E-mail</span><span class="fi-row-value" style="word-break:break-all">{{ $user->email }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Telefone</span><span class="fi-row-value">{{ $user->user_detail->phone }}</span></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="fi-card h-100">
                <div class="fi-card-header">
                    <div class="fi-card-icon">📑</div>
                    <p class="fi-card-title">Documentos Pessoais</p>
                </div>
                <div class="fi-row"><span class="fi-row-label">Nacionalidade</span><span class="fi-row-value">{{ $user->user_detail->nationality }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Tipo Doc.</span><span class="fi-row-value">{{ $user->user_detail->doc_type }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Nº Documento</span><span class="fi-row-value">{{ $user->user_detail->doc_number }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Nascimento</span><span class="fi-row-value">{{ $user->birth }}</span></div>
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
        @if (!empty($user->user_detail->new_number))
            <div class="fi-row"><span class="fi-row-label">Nº Certidão</span><span class="fi-row-value">{{ $user->user_detail->new_number }}</span></div>
        @else
            <div class="fi-row"><span class="fi-row-label">Folhas</span><span class="fi-row-value">{{ $user->user_detail->fls }}</span></div>
            <div class="fi-row"><span class="fi-row-label">Livro</span><span class="fi-row-value">{{ $user->user_detail->book }}</span></div>
            <div class="fi-row"><span class="fi-row-label">Nº Certidão</span><span class="fi-row-value">{{ $user->user_detail->old_number }}</span></div>
            <div class="fi-row"><span class="fi-row-label">Município</span><span class="fi-row-value">{{ $user->user_detail->municipality }}</span></div>
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
            <span class="fi-row-value">{{ $user->user_detail->mother }}
                @if ($user->user_detail->mother_phone)
                    <span class="fi-phone">☎ {{ $user->user_detail->mother_phone }}</span>
                @endif
            </span>
        </div>
        @if ($user->user_detail->father)
        <div class="fi-row">
            <span class="fi-row-label">Pai</span>
            <span class="fi-row-value">{{ $user->user_detail->father }}
                @if ($user->user_detail->father_phone)
                    <span class="fi-phone">☎ {{ $user->user_detail->father_phone }}</span>
                @endif
            </span>
        </div>
        @endif
        @if ($user->user_detail->responsible)
        <div class="fi-row fi-row-highlight">
            <span class="fi-row-label">Responsável</span>
            <span class="fi-row-value">{{ $user->user_detail->responsible }}</span>
        </div>
        <div class="fi-row fi-row-highlight">
            <span class="fi-row-label">Parentesco</span>
            <span class="fi-row-value">{{ $user->user_detail->degree }}
                @if ($user->user_detail->kinship) — {{ $user->user_detail->kinship }} @endif
                <span class="fi-phone">☎ {{ $user->user_detail->responsible_phone }}</span>
            </span>
        </div>
        @endif
        <div class="fi-row"><span class="fi-row-label">E-mail Família</span><span class="fi-row-value" style="word-break:break-all">{{ $user->user_detail->parents_email }}</span></div>
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
                <div class="fi-row"><span class="fi-row-label">Escola</span><span class="fi-row-value">{{ $user->user_detail->school_name }}</span></div>
                <div class="fi-row"><span class="fi-row-label">RA</span><span class="fi-row-value">{{ $user->user_detail->school_ra }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Cidade/UF</span><span class="fi-row-value">{{ $user->user_detail->school_city }} / {{ $user->user_detail->school_state }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Conclusão</span><span class="fi-row-value">{{ $user->user_detail->school_year }}</span></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="fi-card h-100">
                <div class="fi-card-header">
                    <div class="fi-card-icon">🏠</div>
                    <p class="fi-card-title">Endereço</p>
                </div>
                <div class="fi-row"><span class="fi-row-label">CEP</span><span class="fi-row-value">{{ $user->user_detail->zip }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Rua / Nº</span><span class="fi-row-value">{{ $user->user_detail->street }}, {{ $user->user_detail->number }}@if ($user->user_detail->complement) — {{ $user->user_detail->complement }}@endif</span></div>
                <div class="fi-row"><span class="fi-row-label">Bairro</span><span class="fi-row-value">{{ $user->user_detail->burgh }}</span></div>
                <div class="fi-row"><span class="fi-row-label">Cidade/UF</span><span class="fi-row-value">{{ $user->user_detail->city }} / {{ $user->user_detail->state }}</span></div>
            </div>
        </div>
    </div>

    {{-- Complementares --}}
    <p class="fi-section-label">Informações Complementares</p>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="fi-card h-100 @if ($user->user_detail?->accessibility) fi-card-special @endif">
                <div class="fi-card-header">
                    <div class="fi-card-icon">♿</div>
                    <p class="fi-card-title">Educação Especial</p>
                    <div class="ms-auto">
                        @if ($user->user_detail?->accessibility)
                            <span class="fi-pill fi-pill-yes">Elegível</span>
                        @else
                            <span class="fi-pill fi-pill-no">Não Elegível</span>
                        @endif
                    </div>
                </div>
                @if ($user->user_detail?->accessibility)
                <div class="fi-row"><span class="fi-row-label">Necessidade</span><span class="fi-row-value">{{ $user->user_detail->accessibility }}</span></div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="fi-card h-100">
                <div class="fi-card-header">
                    <div class="fi-card-icon">🤝</div>
                    <p class="fi-card-title">Programas Sociais</p>
                    <div class="ms-auto">
                        @if ($user->user_detail?->nis)
                            <span class="fi-pill fi-pill-yes">Beneficiário</span>
                        @else
                            <span class="fi-pill fi-pill-no">Não Beneficiário</span>
                        @endif
                    </div>
                </div>
                @if ($user->user_detail?->nis)
                <div class="fi-row"><span class="fi-row-label">NIS</span><span class="fi-row-value">{{ $user->user_detail->nis }}</span></div>
                @endif
            </div>
        </div>
        <div class="col-12">
            <div class="fi-card">
                <div class="fi-card-header">
                    <div class="fi-card-icon">💡</div>
                    <p class="fi-card-title">Saúde e Alergias</p>
                    <div class="ms-auto">
                        @if ($user->user_detail->health)
                            <span class="fi-pill fi-pill-warn">Atenção</span>
                        @else
                            <span class="fi-pill fi-pill-no">Nenhuma</span>
                        @endif
                    </div>
                </div>
                @if ($user->user_detail?->health)
                <div class="fi-row"><span class="fi-row-label">Descrição</span><span class="fi-row-value">{{ $user->user_detail->health }}</span></div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection