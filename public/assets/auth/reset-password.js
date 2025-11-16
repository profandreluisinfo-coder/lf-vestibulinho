$(function () {
    // Adiciona método customizado para validação de senha forte
    $.validator.addMethod("strongPassword", function(value, element) {
        // Verifica se tem 6-8 caracteres, pelo menos uma maiúscula, uma minúscula e um número
        return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,8}$/.test(value);
    }, "A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.");

    const $form = $("#reset-password");
    const rules = {
        password: {
            required: true,
            strongPassword: true
        },
        password_confirmation: {
            required: true,
            equalTo: "#password"
        }
    };
    
    const messages = {
        password: {
            required: "Obrigatório"
        },
        password_confirmation: {
            required: "Obrigatório",
            equalTo: "As senhas devem ser iguais"
        }
    };

    $form.validate({
        rules,
        messages,
        errorPlacement: (error, element) => {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid').addClass('is-valid'),
        submitHandler: function (form) {
            const $btn = $(form).find('button[type="submit"]');
            $btn.prop("disabled", true).html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Aguarde...`);
            form.submit();
        }
    });
});