$('#form-file').validate({
    rules: {
        process_id: {
            required: true
        },
        file: {
            extension: "pdf"
        }
    },
    messages: {
        process_id: {
            required: 'Processo seletivo é obrigatório'
        },
        file: {
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
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});