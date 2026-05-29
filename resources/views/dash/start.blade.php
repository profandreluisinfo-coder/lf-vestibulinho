@extends('layouts.dash')

@section('page-title', 'Vestibulinho LF ' . $calendar?->year . ' | Área do Candidato')

@push('styles')
    <style>
        
        /* ── Título de seção ── */
        .section-title h4 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--color-navy);
        }

        /* ── Cards de passo ── */
        .registration-step-card {
            background: var(--color-white);
            border: 1px solid var(--color-light-mid);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 1.4rem 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1.1rem;
            transition: box-shadow var(--transition-base), transform var(--transition-base);
        }

        .registration-step-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .registration-step-card.final-step {
            border-left: 4px solid var(--color-teal);
            background: var(--color-teal-light);
        }

        /* ── Número do passo ── */
        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: var(--grad-teal);
            color: var(--color-white);
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-teal);
        }

        /* ── Conteúdo do passo ── */
        .step-content h5 {
            font-size: .95rem;
            font-weight: 700;
            margin-bottom: .3rem;
            color: var(--color-navy);
        }

        .step-content p {
            font-size: var(--font-size-sm);
            color: var(--color-muted);
            margin-bottom: 0;
            line-height: 1.5;
        }

        /* ── Box de informações importantes ── */
        .important-info-box {
            border: 1px solid var(--color-light-mid);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .info-header {
            background: var(--grad-navy);
            color: var(--color-white);
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: .9rem;
            padding: .85rem 1.3rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .info-content {
            background: var(--color-white);
            padding: 1.1rem 1.3rem;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            font-size: var(--font-size-sm);
            color: var(--color-navy);
        }

        .info-item i {
            color: var(--color-teal);
            font-size: 1rem;
            margin-top: .05rem;
            flex-shrink: 0;
        }

        /* ── CTA ── */
        .registration-cta {
            padding: 1.5rem;
            background: var(--color-white);
            border: 1px solid var(--color-light-mid);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }

        /* ── Footer da página ── */
        .registration-footer {
            font-size: var(--font-size-xs);
            color: var(--color-muted);
            display: flex;
            flex-direction: column;
            gap: .15rem;
            padding-top: 1rem;
            border-top: 1px solid var(--color-light-mid);
        }

        .registration-footer strong {
            color: var(--color-navy);
            font-size: var(--font-size-sm);
        }
    </style>
@endpush

@section('dash-content')

    <div class="wrapper">

        {{-- PASSOS --}}

        <div class="section-title mb-4">
            <h4>
                <i class="bi bi-list-check me-2"></i>
                Como fazer sua inscrição
            </h4>

            <div class="divider-teal"></div>
        </div>

        <div class="row mb-5 g-4">

            {{-- PASSO --}}
            <div class="col-lg-6">
                <div class="registration-step-card h-100">

                    <div class="step-icon">
                        1
                    </div>

                    <div class="step-content">
                        <h5>Leia o Edital</h5>

                        <p>
                            Consulte o edital oficial do processo seletivo antes de iniciar.
                        </p>

                        @if ($notice && $notice->file && file_exists(public_path('storage/' . $notice->file)))
                            <a href="{{ asset('storage/' . $notice->file) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm mt-2">

                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Abrir Edital
                            </a>
                        @endif

                    </div>

                </div>
            </div>

            {{-- PASSO --}}
            <div class="col-lg-6">
                <div class="registration-step-card h-100">

                    <div class="step-icon">
                        2
                    </div>

                    <div class="step-content">
                        <h5>Preencha o Formulário</h5>

                        <p>
                            Complete cuidadosamente todos os campos obrigatórios.
                        </p>
                    </div>

                </div>
            </div>

            {{-- PASSO --}}
            <div class="col-lg-6">
                <div class="registration-step-card h-100">

                    <div class="step-icon">
                        3
                    </div>

                    <div class="step-content">
                        <h5>Informe seu CPF</h5>

                        <p>
                            Utilize exclusivamente seu próprio CPF durante a inscrição.
                        </p>

                        <a href="https://www.gov.br/pt-br/servicos/inscrever-no-cpf" target="_blank"
                            class="small text-decoration-none">

                            Não possui CPF?
                        </a>
                    </div>

                </div>
            </div>

            {{-- PASSO --}}
            <div class="col-lg-6">
                <div class="registration-step-card h-100">

                    <div class="step-icon">
                        4
                    </div>

                    <div class="step-content">
                        <h5>Revise os Dados</h5>

                        <p>
                            Confira atentamente todas as informações antes de finalizar.
                        </p>
                    </div>

                </div>
            </div>

            {{-- PASSO --}}
            <div class="col-12">
                <div class="registration-step-card final-step">

                    <div class="step-icon">
                        5
                    </div>

                    <div class="step-content">
                        <h5>Guarde sua Ficha</h5>

                        <p class="mb-0">
                            Imprima ou salve sua ficha de inscrição para futuras consultas.
                        </p>
                    </div>

                </div>
            </div>

        </div>

        {{-- INFORMAÇÕES --}}
        <div class="important-info-box mb-5">

            <div class="info-header">
                <i class="bi bi-info-circle-fill"></i>
                Informações importantes
            </div>

            <div class="info-content">

                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>

                    <span>
                        Candidatos transgêneros podem utilizar nome social mediante autorização.
                    </span>
                </div>

                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>

                    <span>
                        Processo seletivo exclusivo para moradores de Sumaré - SP.
                    </span>
                </div>

                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>

                    <span>
                        O acompanhamento do processo seletivo é responsabilidade do candidato.
                    </span>
                </div>

            </div>

        </div>

        {{-- CTA --}}
        <div class="registration-cta text-center">

            <p class="text-muted mb-4">
                <i class="bi bi-question-circle me-1"></i>
                Em caso de dúvidas, consulte nossos canais oficiais de atendimento.
            </p>

            <a href="{{ route('step.personal') }}" class="btn btn-primary btn-lg px-5 py-3">

                <i class="bi bi-person-plus me-2"></i>
                Iniciar Inscrição
            </a>

        </div>

        {{-- FOOTER --}}
        <div class="registration-footer text-center">

            <strong>E. M. Dr. Leandro Franceschini</strong>

            <span>
                Prefeitura Municipal de Sumaré
            </span>

        </div>

    </div>

@endsection
