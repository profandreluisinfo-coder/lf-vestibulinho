$(document).ready(function () {
    // Inicializar Summernote
    $('.summernote').summernote({
        height: 300,
        lang: 'pt-BR',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Digite a resposta aqui...',
        callbacks: {
            onInit: function () {
                console.log('Summernote initialized');
            }
        }
    });

    // Validação do formulário
    $('.edit-form').validate({
        rules: {
            question: {
                required: true,
                minlength: 5
            },
            answer: {
                required: true,
                minlength: 10
            }
        },
        messages: {
            question: {
                required: "Por favor, insira a pergunta",
                minlength: "A pergunta deve ter pelo menos 5 caracteres"
            },
            answer: {
                required: "Por favor, insira a resposta",
                minlength: "A resposta deve ter pelo menos 10 caracteres"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            // Garantir que o conteúdo do Summernote seja enviado
            $(form).find('.summernote').each(function () {
                if ($(this).summernote('isEmpty')) {
                    $(this).val('');
                }
            });

            // Prevenir envio duplicado - buscar o botão dentro do formulário específico
            var $submitButton = $(form).find('button[type="submit"]');
            $submitButton.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i> Atualizando...');

            form.submit();
        }
    });
});