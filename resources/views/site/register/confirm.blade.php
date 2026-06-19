@extends('layouts.dash')

@section('page-title', 'Vestibulinho LF')

@php
    $degrees = [
        1 => 'Padrasto', 2 => 'Madrasta', 3 => 'Avô(ó)', 4 => 'Tio(a)',
        5 => 'Irmão(ã)', 6 => 'Primo(a)', 7 => 'Tio(a)', 8 => 'Outro',
    ];

    $doc = match ($step1['doc_type']) {
        '1' => 'RG — Registro Geral',
        '2' => 'CIN — Carteira de Identidade Nacional',
        '3' => 'RNE — Registro Nacional de Estrangeiro',
        default => $step1['doc_type'],
    };

    $gender = match ($step1['gender']) {
        '1' => 'Masculino', '2' => 'Feminino', '3' => 'Outro',
        default => 'Prefiro não informar',
    };
@endphp

@section('content')
<div class="wrapper">

    {{-- Título --}}
    <div class="section-title mb-4">
        <h4><i class="bi bi-check2-all me-2"></i>Revisão da Inscrição</h4>
        <div class="divider-teal"></div>
    </div>

    @include('partials.forms.stepper')

    {{-- Banner --}}
    <div class="confirm-banner">
        <i class="bi bi-info-circle-fill"></i>
        Revise todos os dados antes de finalizar. Após a confirmação não será possível editar.
    </div>

    {{-- 1. Identificação --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-person-vcard"></i> Identificação do Candidato
        </div>
        <div class="review-row">
            <span class="review-label">CPF</span>
            <span class="review-value">{{ $step1['cpf'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Nome</span>
            <span class="review-value">{{ $step1['name'] }}</span>
        </div>
        @if ($step1['social_name_option'] == 1)
        <div class="review-row">
            <span class="review-label">Nome social</span>
            <span class="review-value d-flex flex-column align-items-end gap-1">
                {{ $step1['social_name'] }}
                <a href="{{ Storage::disk('public')->url(session('step1.authorization')) }}"
                   target="_blank" class="small text-decoration-none">
                    <i class="bi bi-file-earmark-check me-1"></i>Ver autorização
                </a>
            </span>
        </div>
        @endif
    </div>

    {{-- 2. Documentos --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-card-text"></i> Documentos Pessoais
        </div>
        <div class="review-row">
            <span class="review-label">Nacionalidade</span>
            <span class="review-value">{{ $step1['nationality'] == 1 ? 'Brasileira' : 'Estrangeira' }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Documento</span>
            <span class="review-value">{{ $doc }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Nº Documento</span>
            <span class="review-value">{{ $step1['doc_number'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Nascimento</span>
            <span class="review-value">{{ \Carbon\Carbon::parse($step1['birth'])->format('d/m/Y') }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Gênero</span>
            <span class="review-value">{{ $gender }}</span>
        </div>
    </div>

    {{-- 3. Certidão --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-file-earmark-text"></i> Certidão de Nascimento
        </div>
        @if ($step2['certificateModel'] == '1')
        <div class="review-row">
            <span class="review-label">Nº Certidão</span>
            <span class="review-value">{{ $step2['new_number'] }}</span>
        </div>
        @else
        <div class="review-row">
            <span class="review-label">Folhas</span>
            <span class="review-value">{{ $step2['fls'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Livro</span>
            <span class="review-value">{{ $step2['book'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Nº Certidão</span>
            <span class="review-value">{{ $step2['old_number'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Município</span>
            <span class="review-value">{{ $step2['municipality'] }}</span>
        </div>
        @endif
    </div>

    {{-- 4. Contato --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-telephone"></i> Contato
        </div>
        <div class="review-row">
            <span class="review-label">E-mail</span>
            <span class="review-value">{{ Auth::user()->email }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Telefone</span>
            <span class="review-value">{{ $step1['phone'] }}</span>
        </div>
    </div>

    {{-- 5. Endereço --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-geo-alt"></i> Endereço
        </div>
        <div class="review-row">
            <span class="review-label">CEP</span>
            <span class="review-value">{{ $step3['zip'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Rua</span>
            <span class="review-value">{{ $step3['street'] }}</span>
        </div>
        @if ($step3['number'])
        <div class="review-row">
            <span class="review-label">Número</span>
            <span class="review-value">{{ $step3['number'] }}</span>
        </div>
        @endif
        @if ($step3['complement'])
        <div class="review-row">
            <span class="review-label">Complemento</span>
            <span class="review-value">{{ $step3['complement'] }}</span>
        </div>
        @endif
        <div class="review-row">
            <span class="review-label">Bairro</span>
            <span class="review-value">{{ $step3['burgh'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Cidade / UF</span>
            <span class="review-value">{{ $step3['city'] }} — {{ $step3['state'] }}</span>
        </div>
    </div>

    {{-- 6. Escolaridade --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-book"></i> Escolaridade
        </div>
        <div class="review-row">
            <span class="review-label">Escola</span>
            <span class="review-value">{{ $step4['school_name'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">RA</span>
            <span class="review-value">{{ $step4['school_ra'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Cidade / UF</span>
            <span class="review-value">{{ $step4['school_city'] }} — {{ $step4['school_state'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Conclusão</span>
            <span class="review-value">{{ $step4['school_year'] }}</span>
        </div>
    </div>

    {{-- 7. Filiação --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-people"></i> Filiação / Responsável Legal
        </div>
        <div class="review-row">
            <span class="review-label">Mãe</span>
            <span class="review-value">{{ $step5['mother'] }}</span>
        </div>
        @if ($step5['mother_phone'])
        <div class="review-row">
            <span class="review-label">Tel. Mãe</span>
            <span class="review-value">{{ $step5['mother_phone'] }}</span>
        </div>
        @endif
        @if ($step5['father'])
        <div class="review-row">
            <span class="review-label">Pai</span>
            <span class="review-value">{{ $step5['father'] }}</span>
        </div>
        @endif
        @if ($step5['father_phone'])
        <div class="review-row">
            <span class="review-label">Tel. Pai</span>
            <span class="review-value">{{ $step5['father_phone'] }}</span>
        </div>
        @endif
        @if ($step5['responsible'])
        <div class="review-row">
            <span class="review-label">Responsável</span>
            <span class="review-value">{{ $step5['responsible'] }}</span>
        </div>
        <div class="review-row">
            <span class="review-label">Parentesco</span>
            <span class="review-value">
                {{ $degrees[$step5['degree']] ?? '' }}
                @if ($step5['kinship']) — {{ $step5['kinship'] }} @endif
            </span>
        </div>
        <div class="review-row">
            <span class="review-label">Tel. Responsável</span>
            <span class="review-value">{{ $step5['responsible_phone'] }}</span>
        </div>
        @endif
        <div class="review-row">
            <span class="review-label">E-mail responsável</span>
            <span class="review-value">{{ $step5['parents_email'] }}</span>
        </div>
    </div>

    {{-- 8. Educação Especial --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-universal-access"></i> Educação Especial
        </div>
        <div class="review-row">
            <span class="review-label">Elegível</span>
            <span class="review-value">
                @if ($step6['pne'] == 1)
                    <span class="badge bg-success">Sim</span>
                @else
                    <span class="badge bg-danger">Não</span>
                @endif
            </span>
        </div>
        @if ($step6['pne'] == '1')
        <div class="review-row">
            <span class="review-label">Descrição</span>
            <span class="review-value d-flex flex-column align-items-end gap-1">
                {{ $step6['accessibility_description'] }}
                <a href="{{ Storage::disk('public')->url(session('step6.pne_report')) }}"
                   target="_blank" class="small text-decoration-none">
                    <i class="bi bi-file-earmark-medical me-1"></i>Ver laudo
                </a>
            </span>
        </div>
        <div class="review-row">
            <span class="review-label">Recursos de acessibilidade</span>
            <span class="review-value d-flex flex-column align-items-end gap-1">
                {{ $step6['pne_description'] }}
            </span>
        </div>
        @endif
    </div>

    {{-- 9. Programas Sociais + Saúde --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-award"></i> Programas Sociais e Saúde
        </div>
        <div class="review-row">
            <span class="review-label">Bolsa-Família</span>
            <span class="review-value">
                @if ($step6['social_program'] == 1)
                    <span class="badge bg-success">Sim</span>
                @else
                    <span class="badge bg-danger">Não</span>
                @endif
            </span>
        </div>
        @if ($step6['social_program'] == 1)
        <div class="review-row">
            <span class="review-label">NIS</span>
            <span class="review-value">{{ $step6['nis'] }}</span>
        </div>
        @endif
        <div class="review-row">
            <span class="review-label">Problema de saúde / alergia</span>
            <span class="review-value">
                @if ($step6['health'] == 1)
                    <span class="badge bg-success">Sim</span>
                @else
                    <span class="badge bg-danger">Não</span>
                @endif
            </span>
        </div>
        @if ($step6['health'] == 1)
        <div class="review-row">
            <span class="review-label">Descrição</span>
            <span class="review-value">{{ $step6['health_issue'] }}</span>
        </div>
        @endif
    </div>

    {{-- 10. Curso --}}
    <div class="review-section">
        <div class="review-section-header">
            <i class="bi bi-journal-bookmark"></i> Intenção de Curso
        </div>
        <div class="review-row">
            <span class="review-label">Curso</span>
            <span class="review-value">{{ \App\Models\Course::getDescription($step7['course_id']) }}</span>
        </div>
    </div>

    {{-- Termos + Finalizar --}}
    <form id="finalize-inscription" action="{{ route('step.finalize') }}" method="POST" class="mt-4">
        @csrf

        <div class="terms-box mb-4">
            <h5 class="fw-bold mb-2 required" style="font-size: .9rem;">Termos e condições</h5>
            <div class="terms-text">
                Estou ciente de que a formalização da inscrição implica a aceitação de todas as regras e condições
                estabelecidas no edital de abertura de inscrições. Estou ciente, ainda, que, caso venha a ser
                aprovado e convocado, deverei entregar os documentos comprobatórios dos requisitos exigidos para a
                matrícula por ocasião da aprovação. Concordo com os termos que constam no edital, bem como aceito
                que os meus dados pessoais, sensíveis ou não, sejam tratados e processados de forma a possibilitar a
                efetiva execução do Processo Seletivo, com a aplicação dos critérios de avaliação e seleção,
                autorizando expressamente a divulgação de meu nome, número de inscrição e notas, em observância aos
                princípios da publicidade e da transparência que regem a Administração Pública e nos termos da Lei
                Federal nº 13.709, de 14 de agosto de 2018.
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="agree_terms" name="agree_terms">
                <label class="form-check-label" for="agree_terms" style="font-size: var(--font-size-sm);">
                    Li e concordo com os termos acima.
                </label>
            </div>
        </div>

        <div class="d-flex gap-2 border-top pt-3">
            <a href="{{ route('step.course') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Voltar
            </a>
            <button type="button" class="btn btn-success btn-sm" onclick="confirmFinalize()">
                Confirmar inscrição <i class="bi bi-check-circle ms-1"></i>
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/swa/registration/confirm.js') }}"></script>
@endpush