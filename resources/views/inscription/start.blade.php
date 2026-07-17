@extends('layouts.inscription')

@section('page-title', 'Vestibulinho LF ' . $process?->year . ' - Procedimentos para inscrição')

@section('content')

    @include('inscription.partials.navbar')

    <div class="wrapper">
        <div class="important-info-box mb-5">
            <div class="info-header">
                <i class="bi bi-info-circle-fill"></i> Informações importantes
            </div>
            <div class="info-content">
                <div class="info-item">
                    <span class="fw-bold">Antes de inscrever-se, leia com muito atenção as informações abaixo:</span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Consulte o @if (file_exists(public_path('uploads/' . $process?->edital)))
                            <a href="{{ Storage::url($process?->edital) }}" target="_blank">edital</a>
                        @endif do processo seletivo antes de iniciar.
                    </span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Candidatos transgêneros menores de idade que desejarem utilizar o nome social deverão anexar, no ato da inscrição, autorização assinada por seu responsável legal, conforme modelo disponibilizado pela instituição.
                    </span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Os candidatos que se enquadrarem na condição de <strong>Pessoa com Deficiência (PCD)</strong> e necessitarem de atendimento especializado, adaptações ou recursos de acessibilidade para a realização das etapas do processo seletivo deverão anexar, no ato da inscrição, <strong>laudo</strong> ou <strong>relatório médico</strong> atualizado que comprove a condição declarada, nos termos estabelecidos neste edital.
                    </span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Este processo seletivo é <strong>exclusivo</strong> para moradores de <strong>Sumaré - SP</strong>.
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
    
        <div class="registration-cta text-center">
            <p class="text-muted mb-4">
                <i class="bi bi-question-circle me-1"></i>
                Em caso de dúvidas, consulte nossos canais oficiais de atendimento.
            </p>
            <a href="{{ route('inscription.step.personal') }}" class="btn btn-primary btn-lg px-5 py-3">
                <i class="bi bi-person-plus me-2"></i> Iniciar Inscrição
            </a>
        </div>
        
        <div class="registration-footer text-center">
            <strong>EM Dr Leandro Franceschini</strong>
            <span>Prefeitura Municipal de Sumaré</span>
        </div>
    </div>

@endsection