$(function () {
    $("#forgot-password").validate({
        rules: { email: { required: true, email: true } },
        messages: { email: { required: "Obrigatório", email: "Por favor, insira um e-mail válido" } },
        submitHandler: form => form.submit(),
        errorElement: "span",
        errorClass: "invalid-feedback",
        errorPlacement: (error, element) => error.appendTo(element.closest(".mb-3")),
        highlight: element => $(element).addClass("is-invalid"),
        unhighlight: element => $(element).removeClass("is-invalid").addClass("is-valid"),

        submitHandler: function (form) {
            const $btn = $(form).find('button[type="submit"]');
            $btn.prop("disabled", true).html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Aguarde...`);
            form.submit();
        }
    });
});

