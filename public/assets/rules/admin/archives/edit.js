$('#form-file-edit').validate({
    rules: {
        year: {
            required: true,
            number: true
        },
        file: {
            required: true,
            extension: "pdf"
        },
        answer: {
            extension: "pdf"
        }
    },
    messages: {
        year: {
            required: 'Ano em que a prova foi realizada',
            number: 'Ano em que a prova foi realizada'
        },
        file: {
            required: 'Arquivo relacionado',
            extension: 'Formato de arquivo inválido'
        },
        answer: {
            extension: 'Formato de arquivo inválido'
        }
    },
    errorElement: 'span',
    submitHandler: function (form) {
        const $btn = $(form).find('button[type="submit"]');
        $btn.prop("disabled", true).html(
            `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Aguarde...`
        );
        form.submit();
    },
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-floating').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});