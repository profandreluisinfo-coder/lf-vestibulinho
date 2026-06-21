// ═══════════════════════════════════════════════════════════════════
// Métodos de Validação Customizados (ANTES de usar validate())
// ═══════════════════════════════════════════════════════════════════

$.validator.addMethod(
    'summernoteRequired',
    function (value, element) {
        if (!$(element).hasClass('summernote')) {
            return true;
        }

        const code = $(element).summernote('code');
        const text = $('<div>')
            .html(code)
            .text()
            .trim();

        return text.length > 0;
    },
    'Por favor, insira o conteúdo.'
);

$.validator.addMethod(
    'summernoteMinlength',
    function (value, element, param) {
        if (!$(element).hasClass('summernote')) {
            return true;
        }

        const code = $(element).summernote('code');
        const text = $('<div>')
            .html(code)
            .text()
            .trim();

        return text.length >= param;
    },
    function (param, element) {
        return 'O conteúdo deve ter no mínimo ' + param + ' caracteres.';
    }
);

// ═══════════════════════════════════════════════════════════════════
// Funções Globais
// ═══════════════════════════════════════════════════════════════════

function confirmAttachmentDelete(id, name) {

    Swal.fire({
        title: 'Remover anexo?',
        text: `Deseja excluir "${name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, remover',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            document
                .getElementById(
                    `delete-attachment-form-${id}`
                )
                .submit();
        }
    });
}

// ═══════════════════════════════════════════════════════════════════
// Inicialização
// ═══════════════════════════════════════════════════════════════════

$(document).ready(function () {

    // ─────────────────────────────────────────────────────────────
    // 1️⃣ INICIALIZAR SUMMERNOTE PRIMEIRO
    // ─────────────────────────────────────────────────────────────

    $('.summernote').summernote({
        height: 200,
        lang: 'pt-BR',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Digite o conteúdo aqui...',
        dialogsInBody: true,
        callbacks: {
            onInit: function () {
                // Garantir que o editor funcione dentro do modal
                $('.note-editor').css('z-index', '9999');

                // SINCRONIZAR o conteúdo do Summernote com o textarea
                $(this).val($(this).summernote('code'));
            },
            onChange: function (contents) {
                // Sincronizar valor do textarea em tempo real
                $(this).val(contents);

                // Validar em tempo real
                if ($(this).data('validator')) {
                    $(this).valid();
                }
            },
            onBlur: function (contents) {
                // Sincronizar e validar ao sair do editor
                $(this).val(contents);
                $(this).valid();
            }
        }
    });

    // ─────────────────────────────────────────────────────────────
    // 2️⃣ SINCRONIZAR ANTES DE VALIDAR
    // ─────────────────────────────────────────────────────────────

    $('.summernote').each(function () {
        const code = $(this).summernote('code');
        $(this).val(code);
    });

    // ─────────────────────────────────────────────────────────────
    // 3️⃣ CONFIGURAR VALIDAÇÃO DO FORMULÁRIO
    // ─────────────────────────────────────────────────────────────

    $('#communicateForm').validate({
        rules: {
            titulo: {
                required: true,
                minlength: 3
            },
            resumo: {
                required: true,
                summernoteRequired: true,
                summernoteMinlength: 10
            },
            tipo: {
                required: true
            },
            url: {
                url: true
            }
        },
        messages: {
            titulo: {
                required: "Por favor, insira o título do comunicado.",
                minlength: "O título deve ter no mínimo 3 caracteres."
            },
            resumo: {
                required: "Por favor, insira o conteúdo do comunicado.",
                summernoteRequired: "Por favor, insira o conteúdo do comunicado.",
                summernoteMinlength: "O conteúdo deve ter no mínimo 10 caracteres."
            },
            tipo: {
                required: "Por favor, selecione o tipo de comunicado."
            },
            url: {
                url: "Por favor, insira uma URL válida."
            }
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');

            // Tratamento especial para o Summernote
            if (element.attr('id') === 'resumo') {
                // Coloca a mensagem de erro depois do container do Summernote
                element.closest('.form-group').append(error);
            } else {
                element.closest('.form-group').append(error);
            }
        },
        highlight: function (element, errorClass, validClass) {
            const $element = $(element);

            if ($element.hasClass('summernote')) {
                // Adiciona borda vermelha ao editor do Summernote
                $element
                    .siblings('.note-editor')
                    .find('.note-editing-area')
                    .css('border', '2px solid #dc3545');
            } else {
                $element.addClass('is-invalid');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            const $element = $(element);

            if ($element.hasClass('summernote')) {
                // Remove borda vermelha do editor do Summernote
                $element
                    .siblings('.note-editor')
                    .find('.note-editing-area')
                    .css('border', '');
            } else {
                $element.removeClass('is-invalid');
            }
        },
        submitHandler: function (form) {
            // Sincronizar o conteúdo do Summernote antes de enviar
            $('.summernote').each(function () {
                const $summernote = $(this);
                const code = $summernote.summernote('code');
                $summernote.val(code);
            });

            // Desabilitar botão e mostrar loading
            const $btn = $(form).find('button[type="submit"]');
            const originalText = $btn.html();

            $btn
                .prop("disabled", true)
                .html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Salvando...`);

            console.log('submitHandler executado');
            // Submeter formulário
            form.submit();
        },
        // Validação em tempo real ao sair de um campo
        onfocusout: function (element) {
            $(element).valid();
        }
    });
});