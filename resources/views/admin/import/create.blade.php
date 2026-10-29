@extends('layouts.admin')

@section('page-title', 'Importar Notas')

@section('content')

    <div class="container">

        {{-- Título da Página --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-upload text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Importar Notas</h6>
            </div>
        </div>

        <div class="progress mb-3 d-none" id="progress-wrapper">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" role="progressbar"
                style="width: 0%">0%</div>
        </div>

        {{-- ALERTA --}}
        <div class="alert alert-warning d-flex align-items-center bg-warning py-2 px-3 text-white">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            <strong>Atenção - leia antes de importar</strong>
        </div>

        <div class="mb-3 shadow-sm border border-warning-subtle rounded d-flex flex-column mb-3">

            <div class="p-3 mb-3">Na planilha de notas a importar, o arquivo deve conter obrigatoriamente os seguintes
                cabeçalhos, na seguinte ordem:
            </div>

            <div class="table-responsive">
                <table class="table table-sm">
                    <caption class="py-2 px-3 text-muted" style="font-size:12px;"><i
                            class="bi bi-info-circle me-2"></i>Modelo de cabeçalho esperado</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">inscription_id</th>
                            <th scope="col">user_id</th>
                            <th scope="col">user_cpf</th>
                            <th scope="col">user_name</th>
                            <th scope="col">user_birth</th>
                            <th scope="col">points</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="list-group gap-2 px-3 py-3">
                <span class="fw-semibold me-1">Observações:</span>
                <span><i class="bi bi-check2-circle text-success me-2"></i>Os cabeçalhos devem
                    estar sem acentos ou espaços extras.</span>
                <span><i class="bi bi-check2-circle text-success me-2"></i>Cada importação
                    substituirá os dados anteriores.</span>
                <span><i class="bi bi-check2-circle text-success me-2"></i>Formato aceito:
                    .xlsx (máx. 10MB)</span>
            </div>

        </div>

        <form id="import-results" action="{{ route('admin.import.store') }}" method="POST"
            data-redirect="{{ route('admin.import.index') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="file" class="form-label fw-semibold">
                    Selecione o arquivo <span class="text-danger">*</span>
                </label>

                <input type="file" name="file" id="file" class="form-control" required>
            </div>

            <div class="progress mb-3 d-none" id="progress-wrapper">
                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" role="progressbar"
                    style="width: 0%">0%</div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm" id="btn-submit">
                <i class="bi bi-upload"></i> Importar
            </button>

        </form>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            const $form = $('#import-results');
            const $btn = $('#btn-submit');
            const $progressWrapper = $('#progress-wrapper');
            const $progressBar = $('#progress-bar');
            const originalBtnHtml = $btn.html();

            // ===== Validação simples =====
            $form.validate({
                rules: {
                    file: {
                        required: true,
                        extension: "xlsx"
                    }
                },
                messages: {
                    file: {
                        required: "Selecione um arquivo.",
                        extension: "Apenas arquivos .xlsx são permitidos."
                    }
                },
                errorClass: 'is-invalid',
                validClass: 'is-valid',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group, .mb-3').append(error);
                }
            });

            // ===== Submit AJAX =====
            $form.on('submit', function(e) {
                e.preventDefault();

                if (!$form.valid()) {
                    return;
                }

                const formData = new FormData(this);

                // UI - trava botão e mostra progresso
                $btn.prop('disabled', true).html(
                    `<span class="spinner-border spinner-border-sm me-2"></span>
             Importando...`
                );

                $progressWrapper.removeClass('d-none');
                $progressBar
                    .css('width', '0%')
                    .text('0%')
                    .addClass('progress-bar-animated')
                    .removeClass('bg-success bg-danger');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    // ===== Progresso de upload =====
                    xhr: function() {
                        let xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                let percent = Math.round((e.loaded / e.total) * 100);
                                $progressBar
                                    .css('width', percent + '%')
                                    .text(percent + '%');
                            }
                        });

                        return xhr;
                    },

                    // ===== SUCESSO =====
                    success: function(response) {
                        $progressBar
                            .removeClass('progress-bar-animated')
                            .addClass('bg-success')
                            .css('width', '100%')
                            .text('Concluído ✅');

                        $btn.html(
                            `<i class="bi bi-check-lg me-2"></i>Importado`
                        );

                        setTimeout(() => {
                            window.location.href = $form.data('redirect');
                        }, 800);
                    },

                    // ===== ERRO =====
                    error: function(xhr) {

                        let message = 'Erro inesperado ao importar.';

                        // Validação Laravel (422)
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors;
                            message = xhr.responseJSON?.message ?? message;

                            if (errors?.file) {
                                message = errors.file[0];
                                $('#file').addClass('is-invalid');
                            }
                        }

                        // Erro interno (500)
                        if (xhr.status === 500) {
                            message = xhr.responseJSON?.message ?? message;
                        }

                        $progressBar
                            .removeClass('progress-bar-animated')
                            .addClass('bg-danger')
                            .text('Erro ❌');

                        alert(message);

                        // Restaura botão
                        $btn.prop('disabled', false).html(originalBtnHtml);
                    }
                });
            });

        });
    </script>
@endpush