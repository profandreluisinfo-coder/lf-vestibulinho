$(document).ready(function () {
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
        dialogsInBody: true,
        callbacks: {
            onInit: function () {
                // Garantir que o editor funcione dentro do modal
                $('.note-editor').css('z-index', '9999');
            }
        }
    });

    // Limpar o editor quando o modal for fechado
    $('#setNewFAQ').on('hidden.bs.modal', function () {
        $('.summernote').summernote('code', '');
        $('#faqForm')[0].reset();
    });

    // Validação do formulário
    $('#faqForm').validate({
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
            const $btn = $(form).find('button[type="submit"]');
            $btn.prop("disabled", true).html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Aguarde...`);
            form.submit();
        }
    });
});