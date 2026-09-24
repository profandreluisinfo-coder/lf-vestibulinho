@extends('layouts.inscription')

@section('page-title', 'Vestibulinho LF ' . $process?->year . ' - Procedimentos para inscrição')

@push('styles')
    <style>
        .btn.disabled {
            cursor: not-allowed;
            pointer-events: none;
            /* reforça o bloqueio de clique */
        }
    </style>
@endpush

@section('content')

    @include('inscription.partials.navbar')

    <div class="wrapper">
        <div class="important-info-box mb-3">
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
                        Consulte o
                        @if ($process && Storage::disk('public')->exists($process?->edital))
                            <a href="{{ Storage::url($process?->edital) }}" class="text-decoration-none"
                                target="_blank">edital</a>
                        @endif do processo seletivo antes de iniciar.
                    </span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Candidatos transgêneros menores de idade que desejarem utilizar o nome social deverão anexar, no ato
                        da inscrição, autorização assinada por seu responsável legal, conforme modelo disponibilizado pela
                        instituição.<br>
                        @if ($template && Storage::disk('public')->exists($template->file_path))
                            <a href="{{ Storage::url($template->file_path) }}"
                                class="text-decoration-none d-inline-flex align-items-center mt-2" target="_blank"
                                rel="noopener">
                                <i class="bi bi-download me-1"></i>
                                Baixar modelo de autorização
                            </a>
                        @else
                            <span class="text-danger d-inline-flex align-items-center mt-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Modelo de autorização indisponível no momento.
                            </span>
                        @endif
                        <div class="small text-muted mt-2">
                            Baixe o modelo, preencha todos os campos, assine e salve-o em PDF para anexá-lo ao formulário.
                        </div>
                    </span>
                </div>
                <div class="info-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>
                        Os candidatos que se enquadrarem na condição de <strong>Pessoa com Deficiência (PCD)</strong> e
                        necessitarem de atendimento especializado, adaptações ou recursos de acessibilidade para a
                        realização das etapas do processo seletivo deverão anexar, no ato da inscrição,
                        <strong>laudo</strong> ou <strong>relatório médico</strong> atualizado que comprove a condição
                        declarada, nos termos estabelecidos no
                        @if ($process && Storage::disk('public')->exists($process?->edital))
                            <a href="{{ Storage::url($process?->edital) }}" class="text-decoration-none"
                                target="_blank">edital</a>
                        @endif do processo seletivo.
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

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="confirmRead">
            <label class="form-check-label" for="confirmRead">
                Li e compreendi as instruções acima.
            </label>
        </div>

        <div class="registration-cta text-center">
            <p class="text-muted mb-4">
                <i class="bi bi-question-circle me-1"></i>
                Em caso de dúvidas, consulte nossos canais oficiais de atendimento.
            </p>
            <a href="{{ route('inscription.step.personal') }}" id="startBtn"
                class="btn btn-primary btn-lg px-5 py-3 disabled" aria-disabled="true" tabindex="-1">
                <i class="bi bi-person-plus me-2"></i> Iniciar Inscrição
            </a>
        </div>

        <div class="registration-footer text-center">
            <strong>EM Dr Leandro Franceschini</strong>
            <span>Prefeitura Municipal de Sumaré</span>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const STORAGE_KEY = 'inscription_instructions_confirmed';
            const checkbox = document.getElementById('confirmRead');
            const btn = document.getElementById('startBtn');
            const confirmedOnServer = @json(session('instructions_confirmed', false));

            function setEnabled(enabled) {
                btn.classList.toggle('disabled', !enabled);
                if (enabled) {
                    btn.removeAttribute('aria-disabled');
                    btn.removeAttribute('tabindex');
                } else {
                    btn.setAttribute('aria-disabled', 'true');
                    btn.setAttribute('tabindex', '-1');
                }
            }

            // Prioriza o valor do servidor; sessionStorage é só um cache local
            let saved = confirmedOnServer;
            try {
                if (!saved) {
                    saved = sessionStorage.getItem(STORAGE_KEY) === 'true';
                }
            } catch (e) {
                // sessionStorage indisponível — segue com o valor do servidor
            }

            checkbox.checked = saved;
            setEnabled(saved);

            checkbox.addEventListener('change', function() {
                setEnabled(this.checked);

                try {
                    sessionStorage.setItem(STORAGE_KEY, this.checked);
                } catch (e) {
                    // não crítico
                }

                fetch("{{ route('inscription.confirm-instructions') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        confirmed: this.checked
                    }),
                }).catch(() => {
                    
                });
            });

            btn.addEventListener('click', function(e) {
                if (btn.classList.contains('disabled')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
