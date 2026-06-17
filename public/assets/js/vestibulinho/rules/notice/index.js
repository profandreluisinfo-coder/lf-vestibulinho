$(document).ready(function () {
    $('#form-file').validate({
        rules: {
            path: {
                required: true,
                extension: "pdf"
            },
        },
        messages: {
            path: {
                required: '* Selecione um arquivo',
                extension: '* Formato de arquivo inválido. Apenas arquivos PDF.'
            },
        },
        submitHandler: function (form) {
            const $btn = $(form).find('button[type="submit"]');
            $btn.prop("disabled", true).html(
                `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Aguarde...`
            );
            form.submit();
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
    $("#form-file").on("keyup keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#form-file").on("invalid-form.validate", function () {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });
});