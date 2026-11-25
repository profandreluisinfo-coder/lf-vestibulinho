$(document).ready(function () {
    // O textarea fica oculto pelo Summernote e pode conter apenas HTML vazio,
    // como <p><br></p>, que a regra required padrão considera preenchido.
    $.validator.addMethod('summernoteRequired', function (value, element) {
        const content = $(element).summernote('code');
        const text = $('<div>').html(content).text().replace(/[\s\u00a0\u200b\ufeff]/g, '');
        const hasMedia = /<(img|iframe|video|audio|object|embed)\b/i.test(content);

        return text.length > 0 || hasMedia;
    }, 'Por favor, insira a resposta');

    // Inicializar Summernote
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
        placeholder: 'Digite a resposta aqui...',
        dialogsInBody: true
    });

    // Validação do formulário
    $('#faqForm').validate({
        // O Summernote oculta o textarea original; ele ainda precisa ser validado.
        ignore: ':hidden:not(.summernote)',
        rules: {
            category: {
                required: true
            },
            question: {
                required: true
            },
            answer: {
                summernoteRequired: true
            }
        },
        messages: {
            category: {
                required: "Por favor, selecione uma categoria"
            },
            question: {
                required: "Por favor, insira a pergunta"
            },
            answer: {
                summernoteRequired: "Por favor, insira a resposta"
            }
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            
            // Para o campo answer, colocar a mensagem de erro em um local específico
            if (element.attr('id') === 'answer') {
                // Coloca a mensagem de erro depois do container do Summernote
                $('.note-editor').closest('.form-group').append(error);
            } else {
                element.closest('.form-group').append(error);
            }
        },
        highlight: function (element, errorClass, validClass) {
            if ($(element).hasClass('summernote')) {
                // Adiciona borda vermelha ao editor do Summernote
                $(element).siblings('.note-editor').find('.note-editing-area').css('border', '1px solid #dc3545');
            } else {
                $(element).addClass('is-invalid');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if ($(element).hasClass('summernote')) {
                // Remove borda vermelha do editor do Summernote
                $(element).siblings('.note-editor').find('.note-editing-area').css('border', '');
            } else {
                $(element).removeClass('is-invalid');
            }
        },
        submitHandler: function (form) {
            // IMPORTANTE: Sincronizar o conteúdo do Summernote antes de enviar
            $('.summernote').each(function() {
                $(this).val($(this).summernote('code'));
            });
            
            const $btn = $(form).find('button[type="submit"]');
            $btn.prop("disabled", true).html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Aguarde...`);
            form.submit();
        },
        // Adicionar validação em tempo real para o Summernote
        onfocusout: function(element) {
            if ($(element).hasClass('summernote')) {
                // Sincronizar e validar quando sai do editor
                $(element).val($(element).summernote('code'));
                $(element).valid();
            } else {
                $(element).valid();
            }
        }
    });

    // Função para validar conteúdo do Summernote considerando tags HTML
    $.validator.addMethod('summernoteMinlength', function(value, element, param) {
        if ($(element).hasClass('summernote')) {
            const content = $(element).summernote('code');
            // Remove tags HTML para contar apenas texto
            const textContent = $('<div>').html(content).text().trim();
            return textContent.length >= param;
        }
        return value.length >= param;
    });
});
