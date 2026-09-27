$(document).ready(function () {
    $.validator.addMethod("noSequences", function (value, element) {
        var words = value.trim().split(/\s+/);

        for (var i = 0; i < words.length; i++) {
            if (/^(\S)\1+$/.test(words[i])) {
                return false;
            }
        }
        return true;
    }, "* Sequência de palavras inválida");

    $.validator.addMethod("wordLength", function (value, element) {
        var words = value.trim().split(/\s+/);

        for (var i = 0; i < words.length; i++) {
            if (words[i].length < 2) {
                return false;
            }
        }

        return true;
    }, "* Use pelo menos de 2 letras");

    $("#inscription").validate({
        rules: {
            // Acessibilidade
            pne: {
                required: true,
                range: [1, 2]
            },
            accessibility_description: {
                required: {
                    depends: function () {
                        return $("#accessibility").val() == 1;
                    }
                },
                maxlength: {
                    depends: function () {
                        return $("#accessibility").val() == 1;
                    },
                    param: 60
                },
                pattern: {
                    depends: function () {
                        return $("#accessibility").val() == 1;
                    },
                    param: /^[\p{L}0-9 ().,-]+$/u
                },
            },
            pne_report: {
                required: {
                    depends: function () {
                        return $("#accessibility").val() == 1;
                    }
                },
                extension: "pdf"
            },

            // agora é select Sim/Não
            pne_description: {
                required: { depends: function () { return $("#accessibility").val() == 1; } },
                range: [1, 2]
            },

            // novo campo de especificação
            pne_description_detail: {
                required: {
                    depends: function () { return $("#pne_description").val() == 1; }
                },
                maxlength: {
                    depends: function () { return $("#pne_description").val() == 1; },
                    param: 60
                },
                pattern: {
                    depends: function () { return $("#pne_description").val() == 1; },
                    param: /^[\p{L}0-9 ().,-]+$/u
                }
            }
        },
        messages: {
            // Acessibilidade
            pne: {
                required: "* Obrigatório.",
                range: "* Selecione uma opção válida."
            },
            accessibility_description: {
                required: "* Descreva a necessidade de acessibilidade.",
                maxlength: "* Máximo de 60 caracteres.",
                pattern: "* Apenas letras, números e espaços.",
            },
            pne_report: {
                required: "* O campo de relatório de educação especial é obrigatório",
                extension: "* Apenas arquivos PDF são permitidos."
            },
            pne_description: {
                required: "* Informe o serviço de educação especial necessário.",
                maxlength: "* Máximo de 60 caracteres.",
                pattern: "* Apenas letras, números e espaços."
            },
            pne_description_detail: {
                required: "* Descreva o recurso necessário.",
                maxlength: "* Máximo de 60 caracteres.",
                pattern: "* Apenas letras, números e espaços."
            }
        },

        submitHandler: function (form) {
            console.log("Formulário válido, enviando...");
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