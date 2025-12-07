$('#form-file').validate({
    rules: {
        file: {
            required: true,
            extension: "pdf"
        },
        archive_id: {
            required: true,
            number: true
        }
    },
    messages: {
        file: {
            required: 'Selecione o arquivo do gabarito',
            extension: 'Formato de arquivo inválido'
        },
        archive_id: {
            required: 'O gabarito pertence a qual Vestibulinho?',
            number: 'Formato de arquivo inválido'
        }
    },
    errorElement: 'span',
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