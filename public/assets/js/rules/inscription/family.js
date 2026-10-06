$(document).ready(function () {

    const OTHER_DEGREE_ID = '8';
    const NAME_PATTERN = /^[a-zA-ZÀ-ÿ ()]*$/;

    // Domínios de e-mail inválidos (mesma lista do FamilyRequest.php)
    const INVALID_DOMAINS = [
        '@gmail.com.br', '@test.com', '@fakeemail.com', '@invalid.com',
        '@example.com', '@example.com.br', '@email.com', '@email.com.br',
        '@educacaosumare.com', '@hotmail.com.br', '@outlook.com.br'
    ];

    // Validação de domínio de e-mail inválido (sem diferenciar maiúsculas/minúsculas,
    // pois o servidor converte o e-mail para minúsculas antes de validar)
    $.validator.addMethod("isValidDomainName", function (value, element) {
        const email = value.trim().toLowerCase();
        return !INVALID_DOMAINS.some(domain => email.endsWith(domain));
    }, "* O domínio de e-mail informado é inválido.");

    // Confirmação de e-mail (servidor: 'confirmed', após converter para minúsculas)
    $.validator.addMethod("emailConfirmation", function (value, element) {
        return value.trim().toLowerCase() === $.trim($('#parents_email').val()).toLowerCase();
    }, "* Os e-mails não coincidem.");

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

    // Dependências: nome é obrigatório se o telefone correspondente foi informado
    const dependsOnMotherPhone = () => $.trim($('#mother_phone').val()) !== "";
    const dependsOnFatherPhone = () => $.trim($('#father_phone').val()) !== "";

    /**
     * Última linha de defesa antes de enviar: garante que campos de um bloco
     * oculto não sejam enviados com valores "fantasmas" (ex.: autofill do
     * navegador, botão Voltar/bfcache). Espelha o prepareForValidation() do servidor.
     */
    function sanitizeHiddenFields() {
        if (!dependsOnRespOption1()) {
            $('#responsible, #responsible_phone, #degree_id, #kinship').val('');
        } else if ($('#degree_id').val() !== OTHER_DEGREE_ID) {
            $('#kinship').val('');
        }
    }

    // Ao trocar "Sim/Não", só precisamos revalidar a exigência de responsável legal.
    // (Os campos do bloco ficam ocultos/ignorados ou são validados quando o usuário interagir.)
    $('#respOption1, #respOption2').on('change', function () {
        $('#respOption1').valid();
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

    // Radios ficam em .form-check (o erro não é "irmão" do input): destaca o grupo todo
    const getTargets = (element) =>
        element.type === 'radio' ? $('input[name="' + element.name + '"]') : $(element);

    $("#inscription").validate({
        ignore: ":hidden",
        rules: {
            mother: {
                required: { depends: dependsOnMotherPhone },
                ...validateIfFilled({
                    maxlength: 60,
                    pattern: NAME_PATTERN,
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
                    pattern: NAME_PATTERN,
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
                pattern: NAME_PATTERN,
                noSequences: true,
                wordLength: true,
                minWords: true,
                normalizer: value => $.trim(value)
            },
            responsible_phone: {
                required: { depends: dependsOnRespOption1 },
                normalizer: value => $.trim(value)
            },
            degree_id: {
                required: { depends: dependsOnRespOption1 },
                range: [1, 8]
            },
            kinship: {
                // Servidor: obrigatório quando há responsável legal E degree_id = 8
                required: {
                    depends: () => dependsOnRespOption1() && $("#degree_id").val() === OTHER_DEGREE_ID
                },
                maxlength: 45,
                pattern: NAME_PATTERN,
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
                emailConfirmation: true,
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
            degree_id: {
                required: "* Obrigatório.",
                range: "* Selecione um grau de parentesco válido."
            },
            kinship: {
                required: "* Descreva o grau de parentesco quando selecionar \"Outro\".",
                maxlength: "* Máximo de 45 caracteres.",
                pattern: "* Apenas letras, acentos e espaços."
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
                emailConfirmation: "* Os e-mails não coincidem."
            }
        },
        submitHandler: function (form) {
            sanitizeHiddenFields();
            form.submit();
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            if (element.attr('type') === 'radio') {
                error.addClass('d-block');
            }
            element.closest('.form-group').append(error);
        },
        highlight: function (element) {
            getTargets(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            getTargets(element).removeClass('is-invalid');
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