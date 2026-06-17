// ═══════════════════════════════════════════════════════════════════
// Funções Globais
// ═══════════════════════════════════════════════════════════════════

/**
 * Exibir detalhes do comunicado no modal
 */
function showCommunicateDetails(data) {

    $('#view-titulo').text(data.titulo || '—');

    $('#view-resumo').html(data.resumo || '—');

    if (data.url) {

        $('#view-url').html(
            `<a href="${data.url}"
                target="_blank"
                rel="noopener noreferrer">
                ${data.url}
            </a>`
        );

    } else {

        $('#view-url').text('—');

    }

    const status = String(data.status || '').toLowerCase();

    $('#view-status').html(
        status === 'publicado'
            ? '<span class="badge bg-success">Publicado</span>'
            : '<span class="badge bg-secondary">Rascunho</span>'
    );

    // Anexos
    const $attachments = $('#view-attachments');

    if (data.attachments && data.attachments.length > 0) {

        let html = '<div class="list-group">';

        data.attachments.forEach(function (attachment) {

            html += `
                <a
                    href="${attachment.url}"
                    target="_blank"
                    class="list-group-item list-group-item-action">

                    <i class="bi bi-paperclip me-2"></i>
                    ${attachment.name}

                </a>
            `;
        });

        html += '</div>';

        $attachments.html(html);

    } else {

        $attachments.html(
            '<span class="text-muted">Nenhum anexo.</span>'
        );

    }
}

/**
 * Confirmar exclusão do comunicado
 */
function confirmCommunicateDelete(comunicadoId, titulo) {

    Swal.fire({
        title: 'Confirmar exclusão',
        text: `Tem certeza que deseja excluir o comunicado "${titulo}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {
            document
                .getElementById(`delete-communicate-form-${comunicadoId}`)
                .submit();
        }

    });
}

/**
 * Limpar formulário e editor
 */
function resetCommunicateForm() {
    const $form = $('#communicateForm');
    const $summernote = $('#resumo');

    // Resetar o formulário
    $form[0].reset();

    // Limpar o Summernote
    if ($summernote.data('summernote')) {
        $summernote.summernote('code', '');
    }

    // Resetar validação
    if ($form.data('validator')) {
        $form.validate().resetForm();
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();
    }

    // Remover borda de erro do Summernote
    $summernote.siblings('.note-editor').find('.note-editing-area').css('border', '');
}

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
// Inicialização
// ═══════════════════════════════════════════════════════════════════

$(document).ready(function () {

    // ─────────────────────────────────────────────────────────────
    // Inicializar Summernote
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
            },
            onChange: function (contents) {
                // Sincronizar valor do textarea
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
    // Configurar Validação do Formulário
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
                summernoteMinlength: "O conteúdo deve ter no mínimo 10 caracteres.",
                summernoteRequired: "Por favor, insira o conteúdo do comunicado."
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
                    .css('border', '1px solid #dc3545');
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

    // ─────────────────────────────────────────────────────────────
    // Eventos do Modal
    // ─────────────────────────────────────────────────────────────

    // Limpar formulário quando o modal de novo comunicado é fechado
    $('#setNewCommunicate').on('hidden.bs.modal', function () {
        resetCommunicateForm();
    });

    // Limpar detalhes quando o modal de visualização é aberto
    $('#viewCommunicate').on('show.bs.modal', function (event) {

        const button = $(event.relatedTarget);

        const data = button.data('communicate');

        showCommunicateDetails(data);

    });

    // Limpar detalhes quando modal de visualização é fechado
    $('#viewCommunicate').on('hidden.bs.modal', function () {
        $('#view-titulo').text('');
        $('#view-resumo').html('');
        $('#view-url').html('');
        $('#view-attachments').html('');
    });
});
