$(function () {
    const $form = $("#resend-email");
    $form.validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "Obrigatório",
                email: "Por favor, insira um e-mail válido"
            }
        },
        submitHandler: form => form.submit(),
        errorPlacement: (error, element) => {
            error.addClass('invalid-feedback').appendTo(element.closest('.mb-3'));
        },
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid').addClass('is-valid')
    });
});