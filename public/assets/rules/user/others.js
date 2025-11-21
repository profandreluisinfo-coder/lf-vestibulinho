$(document).ready(function () {
    $.validator.addMethod("noSequences", function (value, element) {
        var words = value.trim().split(/\s+/);

        for (var i = 0; i < words.length; i++) {
            if (/^(\S)\1+$/.test(words[i])) {
                return false;
            }
        }
        return true;
    }, "Sequência de palavras inválida");

    $.validator.addMethod("wordLength", function (value, element) {
        var words = value.trim().split(/\s+/);

        for (var i = 0; i < words.length; i++) {
            if (words[i].length < 2) {
                return false;
            }
        }

        return true;
    }, "Use pelo menos de 2 letras");

    // Adiciona o método 'nis' ao jQuery Validator
    $.validator.addMethod("validateNis", function (value, element) {
        // Remove caracteres não numéricos
        const nis = value.replace(/\D/g, '');

        // Verifica se tem 11 dígitos
        if (nis.length !== 11) return false;

        // Cálculo do dígito verificador
        const pesos = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        let soma = 0;

        for (let i = 0; i < 10; i++) {
            soma += parseInt(nis.charAt(i)) * pesos[i];
        }

        const resto = soma % 11;
        const dv = resto <= 1 ? 0 : 11 - resto;

        // Retorna true se o DV for válido
        return dv === parseInt(nis.charAt(10));
    }, "Por favor, insira um NIS/PIS válido."); // Mensagem de erro padrão

    $("#inscription").validate({
        rules: {
            // Alergias
            health: {
                required: true,
                range: [1, 2]
            },
            health_issue: {
                required: {
                    depends: function () {
                        return $("#health").val() == 1;
                    }
                },
                // maxlength: validateIfFilled(60),
                maxlength: {
                    depends: function () {
                        return $("#health").val() == 1;
                    },
                    param: 60
                },
                // pattern: validateIfFilled(/^[\p{L}0-9\s.,-]+$/u),
                pattern: {
                    depends: function () {
                        return $("#health").val() == 1;
                    },
                    param: /^[a-zA-ZÀ-ÿ0-9 ()]*$/
                },

            },

            // Acessibilidade
            accessibility: {
                required: true,
                range: [1, 2]
            },
            accessibility_description: {
                required: {
                    depends: function () {
                        return $("#accessibility").val() == 1;
                    }
                },
                // maxlength: validateIfFilled(60),
                maxlength: {
                    depends: function () {
                        return $("#accessibility").val() == 1;
                    },
                    param: 60
                },
                // pattern: validateIfFilled(/^[a-zA-ZÀ-ÿ0-9 ()]*$/),
                pattern: {
                    depends: function () {
                        return $("#accessibility").val() == 1;
                    }
                },

            },

            // Programas sociais
            social_program: {
                required: true,
                range: [1, 2]
            },
            nis: {
                required: {
                    depends: function () {
                        return $("#social_program").val() == 1;
                    }
                },
                // validateNis: validateIfFilled(),
                validateNis: {
                    depends: function () {
                        return $("#social_program").val() == 1;
                    }
                }
            }
        },
        messages: {
            // Alergias
            health: {
                required: "Obrigatório.",
                range: "Selecione uma opção válida."
            },
            health_issue: {
                required: "Obrigatório.",
                maxlength: "Máximo de 60 caracteres."
            },
            // Acessibilidade
            accessibility: {
                required: "Obrigatório.",
                range: "Selecione uma opção válida."
            },
            accessibility_description: {
                required: "Informe o serviço de educação especial necessário.",
                maxlength: "Máximo de 60 caracteres.",
                pattern: "Apenas letras, números e espaços.",
            },
            // Programas sociais
            social_program: {
                required: "Obrigatório.",
                range: "Selecione um programa social válido."
            },
            nis: {
                required: "Obrigatório.",
                nis: "NIS/PIS inválido.",
            }
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
            // $(element).addClass('is-valid');
        }
    });

    // Previne envio com Enter
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