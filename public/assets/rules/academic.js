$(document).ready(function () {

    $.validator.addMethod("noSimplePatterns", function (value, element) {
        if (!value) return true;

        // Remove formatação (pontos e hífen) e converte para análise
        const rawValue = value.replace(/[^\w]/g, ''); // Remove tudo exceto letras/números
        const numericPart = rawValue.substring(0, 9);  // Pega apenas os 9 primeiros (devem ser números)
        const digit = rawValue.substring(9, 10);      // Último caractere (pode ser letra/número)

        // Se não tiver 10 caracteres ou os 9 primeiros não forem todos números, ignora a validação
        if (rawValue.length < 10 || !/^\d{9}$/.test(numericPart)) {
            return true; // Deixa outras regras (como required/mask) tratarem isso
        }

        // 1. Todos os 9 primeiros dígitos iguais (111.111.111-X)
        if (/^(\d)\1{8}$/.test(numericPart)) {
            return false;
        }

        // 2. Sequência crescente (123.456.789-X) ou decrescente (987.654.321-X)
        if ('0123456789'.includes(numericPart) || '987654321'.includes(numericPart)) {
            return false;
        }

        // 3. Padrões repetitivos nos 9 primeiros dígitos (ex: 121.212.121-X, 123.123.123-X)
        const half = Math.floor(numericPart.length / 2);
        for (let i = 1; i <= half; i++) {
            const pattern = numericPart.substring(0, i);
            if (pattern.repeat(numericPart.length / i) === numericPart) {
                return false;
            }
        }

        return true; // Passou em todas as verificações
    }, "Padrão numérico inválido para o documento");

    $.validator.addMethod("noSequences", function (value, element) {
        var words = value.trim().split(/\s+/);

        for (var i = 0; i < words.length; i++) {
            if (/^(\S)\1+$/.test(words[i])) {
                return false;
            }
        }

        return true;
    }, "Sequência inválida");

    $("#inscription").validate({
        rules: {
            school_name: {
                required: true,
                maxlength: 60,
                pattern: /^[a-zA-ZÀ-ÿ0-9 ()]*$/,
                noSequences: false
            },
            school_ra: {
                required: true,
                pattern: /^\d{3}\.\d{3}\.\d{3}-[A-Za-z0-9]{1}$/,
                noSimplePatterns: true
            },
            school_city: {
                required: true,
                maxlength: 30,
                pattern: /^[a-zA-ZÀ-ÿ ()]*$/,
            },
            school_state: {
                required: true,
            },
            school_year: {
                required: true,
                pattern: /^\d{4}$/
            },
        },
        messages: {
            school_name: {
                required: "Obrigatório.",
                maxlength: "Máximo de 60 caracteres.",
                pattern: "Apenas letras, números e espaços."
            },
            school_ra: {
                required: "Obrigatório.",
                pattern: "Formato inválido.",
            },
            school_city: {
                required: "Obrigatório.",
                maxlength: "Máximo de 30 caracteres.",
                pattern: "Apenas letras, acentos e espaços."
            },
            school_state: {
                required: "Obrigatório."
            },
            school_year: {
                required: "Obrigatório.",
                pattern: "O ano deve conter 4 dígitos."
            },
        },
        submitHandler: function (form) {
            form.submit();
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });

    $("#inscription").on("keyup keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#inscription").on("invalid-form.validate", function () {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });
});