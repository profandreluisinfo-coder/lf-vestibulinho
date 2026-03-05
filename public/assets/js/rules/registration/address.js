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

    function optionalFieldRules(rules) {
        const result = {
            required: false
        };
        for (const rule in rules) {
            result[rule] = {
                depends: function (element) {
                    return $(element).val().length > 0;
                },
                param: rules[rule]
            };
        }
        return result;
    }

    function validateIfFilled(param) {
        return {
            depends: function (element) {
                return $(element).val().trim() !== "";
            },
            param: param
        };
    }

    $("#inscription").validate({
        rules: {
            zip: {
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            street: {
                required: true,
                maxlength: 60,
                pattern: /^[a-zA-ZÀ-ÿ0-9 ()]*$/,
                noSequences: true,
                wordLength: true
            },
            number: {
                required: true,
                maxlength: 10,
                pattern: /^[a-zA-ZÀ-ÿ0-9 \/]*$/
            },
            complement: optionalFieldRules({
                maxlength: 20,
                pattern: /^[a-zA-ZÀ-ÿ0-9().\s]*$/,
                noSequences: validateIfFilled(),
                wordLength: validateIfFilled()
            }),
            burgh: {
                required: true,
                maxlength: 60,
                pattern: /^[a-zA-ZÀ-ÿ0-9 ()]*$/,
                noSequences: true,
                wordLength: true
            },
            city: {
                required: true,
                maxlength: 30,
                pattern: /^[a-zA-ZÀ-ÿ ]*$/,
                noSequences: true,
                wordLength: true
            },
            state: {
                required: true,
                maxlength: 32,
                pattern: /^[a-zA-ZÀ-ÿ ]*$/,
            },
        },
        messages: {
            zip: {
                required: "Obrigatório.",
                minlength: "Mínimo de 10 caracteres.",
                maxlength: "Máximo de 10 caracteres.",
            },
            street: {
                required: "Obrigatório.",
                maxlength: "Máximo de 60 caracteres.",
                pattern: "Apenas letras, números e espaços.",
            },
            number: {
                required: "Obrigatório. Caso não tenha, digite S/N.",
                maxlength: "Máximo de 10 caracteres.",
                pattern: "Apenas letras, números e espaços.",
            },
            complement: {
                maxlength: "Máximo de 20 caracteres.",
                pattern: "Apenas letras, números e espaços.",
            },
            burgh: {
                required: "Obrigatório.",
                maxlength: "Máximo de 30 caracteres.",
                pattern: "Apenas letras, números e espaços.",
            },
            city: {
                required: "Obrigatório.",
                maxlength: "Máximo de 30 caracteres.",
                pattern: "Apenas letras, acentos e espaços.",
            },
            state: {
                required: "Obrigatório.",
                maxlength: "Máximo de 32 caracteres.",
                pattern: "Apenas letras, acentos e espaços.",
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