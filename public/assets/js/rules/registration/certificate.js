$(function () {
    const $form = $("#inscription");
    const $radio1 = $('#radio1');
    const $radio2 = $('#radio2');

    const isChecked = $radio => $radio.is(':checked');

    // Método: Sequência de caracteres iguais
    $.validator.addMethod("noSequences", value => 
        !value.trim().split(/\s+/).some(word => /^(\S)\1+$/.test(word)),
        "Sequência de palavras inválida"
    );

    // Método: Padrões numéricos simples
    $.validator.addMethod("noSimplePatterns", value => {
        if (!value) return true;
        if (/^(\d)\1+$/.test(value)) return false; // dígitos iguais
        if (/123456789|987654321/.test(value)) return false; // sequência

        return !Array.from({ length: Math.floor(value.length / 2) }, (_, i) => i + 1)
            .some(i => value === value.slice(0, i).repeat(value.length / i));
    }, "Padrão numérico inválido para o documento");

    // Função: criar regra condicional
    const ruleIf = ($radio, rule, param = true) => ({
        [rule]: {
            depends: () => isChecked($radio),
            param
        }
    });

    // Função: validação se preenchido
    const validateIfFilled = param => ({
        depends: el => $(el).val().trim() !== "",
        param
    });

    const commonRequired = ruleIf;

    $form.validate({
        ignore: ":hidden",
        rules: {
            certificateModel: { required: true, range: [1, 2] },
            new_number: {
                ...commonRequired($radio1, 'required'),
                ...ruleIf($radio1, 'pattern', /^\d{32}$/),
                // noSimplePatterns: validateIfFilled()
            },
            fls: {
                ...commonRequired($radio2, 'required'),
                ...ruleIf($radio2, 'pattern', /^\d{1,4}$/)  // Apenas números, 1 a 4 dígitos
            },
            book: {
                ...commonRequired($radio2, 'required'),
                ...ruleIf($radio2, 'pattern', /^[A-Za-z0-9]{1,10}$/)  // Letras/números, 1 a 10
            },
            old_number: {
                ...commonRequired($radio2, 'required'),
                ...ruleIf($radio2, 'pattern', /^\d{1,6}$/)  // Apenas números, 1 a 6 dígitos
            },
            municipality: {
                ...commonRequired($radio2, 'required'),
                ...ruleIf($radio2, 'maxlength', 45),
                ...ruleIf($radio2, 'pattern', /^[a-zA-ZÀ-ÿ ()]*$/)
            }
        },
        messages: {
            certificateModel: {
                required: "Informe um dos modelos da sua certidão de nascimento.",
                range: "Selecione um modelo de certidão de nascimento válido."
            },
            new_number: {
                required: "Obrigatório.",
                pattern: "A certidão precisa conter exatamente 32 dígitos."
            },
            fls: {
                required: "Obrigatório.",
                pattern: "Formato inválido. Apenas números, de 1 a 4 dígitos."
            },
            book: {
                required: "Obrigatório.",
                pattern: "Formato inválido. Apenas letras e/ou números, de 1 a 10 caracteres."
            },
            old_number: {
                required: "Obrigatório.",
                pattern: "Formato inválido. Apenas números, de 1 a 6 dígitos."
            },
            municipality: {
                required: "Obrigatório.",
                maxlength: "Máximo de 45 caracteres.",
                pattern: "Apenas letras, acentos e espaços."
            }
        },
        submitHandler: form => form.submit(),
        errorPlacement: (error, element) => 
            error.addClass('invalid-feedback').appendTo(element.closest('.form-group')),
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid')
    });

    // Prevenir submit no Enter
    $form.on("keypress", e => {
        if (e.which === 13) e.preventDefault();
    });

    // Alerta em caso de erro na validação
    $form.on("invalid-form.validate", () => {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });
});