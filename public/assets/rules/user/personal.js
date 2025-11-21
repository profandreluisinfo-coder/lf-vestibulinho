$(function () {
    const $form = $("#inscription");
    const $radio1 = $('#radio1');

    // Métodos de validação personalizados
    $.validator.addMethod("noSequences", value => 
        !value.trim().split(/\s+/).some(word => /^(\S)\1+$/.test(word)),
        "Sequência de palavras inválida"
    );

    $.validator.addMethod("wordLength", value => 
        value.trim().split(/\s+/).every(word => word.length >= 2),
        "Use pelo menos de 2 letras"
    );

    $.validator.addMethod("minWords", value => 
        value.trim().split(/\s+/).length >= 2,
        "Use pelo menos de 2 palavras"
    );

    $.validator.addMethod("noSimplePatterns", value => {
        if (!value) return true;
        if (/^(\d)\1+$/.test(value)) return false;
        if (/123456789|987654321/.test(value)) return false;

        return !Array.from({ length: Math.floor(value.length / 2) }, (_, i) => i + 1)
            .some(i => value === value.slice(0, i).repeat(value.length / i));
    }, "Padrão numérico inválido para o documento");

    const validateIfFilled = param => ({
        depends: el => $(el).val().trim() !== "",
        param
    });

    const ruleIf = (conditionFn, rule, param = true) => ({
        [rule]: {
            depends: conditionFn,
            param
        }
    });

    const isRadio1Checked = () => $radio1.is(':checked');

    const validator = $form.validate({
        ignore: ":hidden",
        rules: {
            cpf: { required: true, cpfBR: true },
            name: {
                required: true,
                maxlength: 60,
                pattern: /^[a-zA-ZÀ-ÿ ]*$/,
                noSequences: true,
                wordLength: true,
                minWords: true
            },
            social_name: {
                ...ruleIf(isRadio1Checked, 'required'),
                ...ruleIf(isRadio1Checked, 'maxlength', 60),
                ...ruleIf(isRadio1Checked, 'pattern', /^[a-zA-ZÀ-ÿ ()]*$/),
                ...ruleIf(isRadio1Checked, 'noSequences'),
                wordLength: validateIfFilled(),
                minWords: validateIfFilled()
            },
            nationality: { required: true, range: [1, 2] },
            doc_type: { required: true, range: [1, 3] },
            doc_number: {
                required: true,
                minlength: 7,
                maxlength: 11,
                pattern: /^\d{7}[\dA-Za-z]{0,4}$/,
                noSimplePatterns: true
            },
            gender: { required: true, range: [1, 4] },
            birth: { required: true, date: true },
            phone: {
                required: true,
                minlength: 14,
                maxlength: 15
            }
        },
        messages: {
            cpf: {
                required: "Obrigatório.",
                cpfBR: "CPF inválido."
            },
            name: {
                required: "Obrigatório.",
                maxlength: "Máximo de 60 caracteres.",
                pattern: "Apenas letras, acentos e espaços."
            },
            social_name: {
                required: "Obrigatório.",
                maxlength: "Máximo de 60 caracteres.",
                pattern: "Apenas letras, acentos e espaços."
            },
            nationality: {
                required: "Obrigatório.",
                range: "Selecione uma nacionalidade válida."
            },
            doc_type: {
                required: "Obrigatório.",
                range: "Selecione um tipo de documento válido."
            },
            doc_number: {
                required: "Obrigatório.",
                minlength: "Use no mínimo 7 caracteres.",
                maxlength: "Use no máximo 11 caracteres.",
                pattern: "Formato inválido. Use apenas letras e números."
            },
            gender: {
                required: "Obrigatório.",
                range: "Selecione um gênero válido."
            },
            birth: {
                required: "Obrigatório.",
                date: "Data inválida."
            },
            phone: {
                required: "Obrigatório.",
                minlength: "Deve conter 14 ou 15 caracteres.",
                maxlength: "Máximo de 15 caracteres."
            }
        },
        submitHandler: form => form.submit(),
        errorPlacement: (error, element) => 
            error.addClass('invalid-feedback').appendTo(element.closest('.form-group')),
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid')
    });

    // ✅ Validação em tempo real
    $form.find("input, select, textarea").on("input change", function () {
        $(this).valid();
    });

    $form.on("keypress", e => {
        if (e.which === 13) e.preventDefault();
    });

    $form.on("invalid-form.validate", () => {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });
});