export function initLoginValidation() {
    $("#form-login").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: "required"
        },
        messages: {
            email: {
                required: "Obrigatório",
                email: "Por favor, insira um e-mail válido"
            },
            password: "Obrigatório"
        },
        errorClass: "invalid-feedback",
        validClass: "is-valid",
        errorPlacement: (error, element) =>
            element.closest(".mb-3").append(error),

        highlight: (element) =>
            $(element).addClass("is-invalid"),

        unhighlight: (element) =>
            $(element).removeClass("is-invalid").addClass("is-valid"),

        submitHandler: function (form) {
            const $btn = $(form).find('button[type="submit"]');

            $btn
                .prop("disabled", true)
                .html(`
                    <span class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true"></span>
                    Aguarde...
                `);

            form.submit();
        }
    });
}