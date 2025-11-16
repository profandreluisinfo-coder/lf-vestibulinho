if (typeof $.fn.validate !== "function") {
    alert("jQuery Validate não está carregado corretamente!");
}

$(document).ready(function () {

    const invalidDomains = [
        '@gmail.com.br', '@test.com', '@fakeemail.com', '@invalid.com',
        '@example.com', '@example.com.br', '@email.com', '@email.com.br', '@educacaosumare.com'
    ];

    // Regras customizadas
    $.validator.addMethod("isValidDomainName", function (value) {
        return !invalidDomains.some(domain => value.endsWith(domain));
    }, "O domínio de e-mail informado é inválido.");

    $.validator.addMethod("strongPassword", function (value) {
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,8}$/.test(value);
    }, "A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.");

    $("#form-register").validate({
        rules: {
            email: {
                required: true,
                email: true,
                isValidDomainName: true
            },
            password: {
                required: true,
                strongPassword: true
            },
            password_confirmation: {
                required: true,
                equalTo: "#registerPassword"
            },
            terms: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Obrigatório",
                email: "Insira um e-mail válido"
            },
            password: {
                required: "Obrigatório",
                strongPassword: "Senha fraca",
            },
            password_confirmation: {
                required: "Obrigatório",
                equalTo: "As senhas devem ser iguais"
            },
            terms: {
                required: "Aceite os termos"
            }
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            if (element.attr("type") === "checkbox") {
                element.closest('.form-check').append(error);
            } else {
                element.closest('.mb-3').append(error);
            }
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function (form) {
            // Isso só é executado se o formulário for válido
            $('.btn[type="submit"]').prop('disabled', true).html('Enviando...');
            form.submit();
        }
    });

    // Proteção extra
    $('#form-register').on('submit', function (e) {
        if (!$(this).valid()) {
            console.warn("❌ Form inválido: submissão bloqueada");
            e.preventDefault(); // Garante que nunca envie com erros
        }
    });

    

});
