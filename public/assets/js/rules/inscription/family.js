$(document).ready(function () {

    // Validação de domínio de e-mail inválido
    $.validator.addMethod("isValidDomainName", function (value, element) {
        const invalidDomains = [
            '@gmail.com.br', '@test.com', '@fakeemail.com', '@invalid.com',
            '@example.com', '@example.com.br', '@email.com', '@email.com.br',
            '@educacaosumare.com', '@hotmail.com.br', '@outlook.com.br'
        ];
        return !invalidDomains.some(domain => value.endsWith(domain));
    }, "* O domínio de e-mail informado é inválido.");

    // Validação de cada palavra ter ao menos 2 letras
    $.validator.addMethod("wordLength", function (value, element) {
        return value.trim().split(/\s+/).every(word => word.length >= 2);
    }, "* Use pelo menos de 2 letras");

    // Validação de ao menos 2 palavras
    $.validator.addMethod("minWords", function (value, element) {
        return value.trim().split(/\s+/).length >= 2;
    }, "* Use pelo menos de 2 palavras");

    // Não permitir sequências como 'aaaa'
    $.validator.addMethod("noSequences", function (value, element) {
        return value.trim().split(/\s+/).every(word => !/^(\S)\1+$/.test(word));
    }, "* Sequência de palavras inválida");

    // Exige responsável legal (respOption1) quando nem mãe nem pai foram informados
    $.validator.addMethod("requiresLegalResponsible", function (value, element) {
        const motherEmpty = $.trim($('#mother').val()) === "";
        const fatherEmpty = $.trim($('#father').val()) === "";
        if (motherEmpty && fatherEmpty) {
            return value == "1";
        }
        return true;
    }, "* Como nenhum dos pais foi informado, é necessário indicar um responsável legal.");

    // Função de validação condicional para campos opcionais
    function validateIfFilled(rules) {
        const result = {};
        for (const rule in rules) {
            result[rule] = {
                depends: (element) => $.trim($(element).val()) !== "",
                param: rules[rule]
            };
        }
        return result;
    }

    // Mensagens padrão
    $.extend($.validator.messages, {
        pattern: "* Apenas letras, acentos e espaços."
    });

    // Dependências das opções de responsável
    const dependsOnRespOption1 = () => $('#respOption1').is(':checked');
    const dependsOnRespOption2 = () => $('#respOption2').is(':checked');

    // Dependências: nome é obrigatório se o telefone correspondente foi informado
    const dependsOnMotherPhone = () => $.trim($('#mother_phone').val()) !== "";
    const dependsOnFatherPhone = () => $.trim($('#father_phone').val()) !== "";

    // Atualiza validação ao mudar a escolha de responsável
    $('#respOption1, #respOption2').change(function () {
        $('#responsible').valid();
        $('#mother_phone').valid();
        $('#responsible_phone').valid();
        $('#degree').valid();
    });

    // Revalida o nome da mãe/pai assim que o respectivo telefone é preenchido/alterado
    $('#mother_phone').on('input change blur', function () {
        $('#mother').valid();
    });
    $('#father_phone').on('input change blur', function () {
        $('#father').valid();
    });

    // Revalida a exigência de responsável legal assim que mãe ou pai forem alterados
    $('#mother, #father').on('input change blur', function () {
        $('#respOption1').valid();
    });

    $("#inscription").validate({
        ignore: ":hidden",
        rules: {
            mother: {
                required: { depends: dependsOnMotherPhone },
                ...validateIfFilled({
                    maxlength: 60,
                    pattern: /^[a-zA-ZÀ-ÿ ()]*$/,
                    noSequences: true,
                    wordLength: true,
                    minWords: true
                })
            },
            mother_phone: {
                normalizer: value => $.trim(value)
            },
            father: {
                required: { depends: dependsOnFatherPhone },
                ...validateIfFilled({
                    maxlength: 60,
                    pattern: /^[a-zA-ZÀ-ÿ ()]*$/,
                    noSequences: true,
                    wordLength: true,
                    minWords: true
                })
            },
            father_phone: {
                normalizer: value => $.trim(value)
            },
            respLegalOption: {
                required: true,
                range: [1, 2],
                requiresLegalResponsible: true
            },
            responsible: {
                required: { depends: dependsOnRespOption1 },
                maxlength: 60,
                pattern: /^[a-zA-ZÀ-ÿ ()]*$/,
                noSequences: true,
                wordLength: true,
                minWords: true,
                normalizer: value => $.trim(value)
            },
            responsible_phone: {
                required: { depends: dependsOnRespOption1 },
                normalizer: value => $.trim(value)
            },
            degree: {
                required: { depends: dependsOnRespOption1 },
                range: [1, 8]
            },
            kinship: {
                required: {
                    depends: () => $("#degree").val() == "8"
                },
                pattern: /^[a-zA-ZÀ-ÿ ()]*$/,
                normalizer: value => $.trim(value)
            },
            parents_email: {
                required: true,
                email: true,
                isValidDomainName: true,
                normalizer: value => $.trim(value)
            },
            parents_email_confirmation: {
                required: true,
                email: true,
                equalTo: "#parents_email",
                normalizer: value => $.trim(value)
            }
        },
        messages: {
            mother: {
                required: "* Obrigatório, pois o telefone da mãe foi informado.",
                maxlength: "* Máximo de 60 caracteres.",
                pattern: "* Apenas letras, acentos e espaços."
            },
            father: {
                required: "* Obrigatório, pois o telefone do pai foi informado.",
                maxlength: "* Máximo de 60 caracteres.",
                pattern: "* Apenas letras, acentos e espaços."
            },
            respLegalOption: {
                required: "* Por favor, selecione uma opção.",
                range: "* Selecione uma opção válida.",
                requiresLegalResponsible: "* Como nenhum dos pais foi informado, é necessário indicar um responsável legal."
            },
            responsible: {
                required: "* Obrigatório.",
                maxlength: "* Máximo de 60 caracteres.",
                pattern: "* Use apenas letras, acentos e espaços."
            },
            degree: {
                required: "* Obrigatório.",
                range: "* Selecione um grau de parentesco válido."
            },
            kinship: {
                required: "* Obrigatório.",
                pattern: "* Apenas letras, acentos e espaços."
            },
            mother_phone: {
                required: "* Obrigatório."
            },
            responsible_phone: {
                required: "* Obrigatório."
            },
            parents_email: {
                required: "* Obrigatório.",
                email: "* E-mail inválido."
            },
            parents_email_confirmation: {
                required: "* Obrigatório.",
                email: "* E-mail inválido.",
                equalTo: "* Os emails não coincidem."
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
        }
    });

    // Previne envio com Enter
    $("#inscription").on("keyup keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    // Alerta ao tentar enviar com campos inválidos
    $("#inscription").on("invalid-form.validate", function () {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });

});