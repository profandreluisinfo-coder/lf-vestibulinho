$(document).ready(function () {

    const $form = $('#import-results');
    const $btn  = $('#btn-submit');
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
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group, .mb-3').append(error);
        }
    });

    // ===== Submit AJAX =====
    $form.on('submit', function (e) {
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
            xhr: function () {
                let xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener('progress', function (e) {
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
            success: function (response) {
                $progressBar
                    .removeClass('progress-bar-animated')
                    .addClass('bg-success')
                    .css('width', '100%')
                    .text('Concluído ✅');

                $btn.html(
                    `<i class="bi bi-check-lg me-2"></i>Importado`
                );

                // Redireciona após um pequeno delay
                setTimeout(() => {
                    window.location.href = "/importar/notas";
                }, 800);
            },

            // ===== ERRO =====
            error: function (xhr) {

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