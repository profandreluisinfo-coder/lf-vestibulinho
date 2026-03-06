$(document).ready(function () {
    // Definição das regras e mensagens de validação
    $("#setNewCallForm").validate({
        // Regras de validação para cada campo
        rules: {
            number: {
                required: true,
                min: 1,
                digits: true,
            },
            limit: {
                required: true,
                min: 1,
                digits: true,
            },
            date: {
                required: true,
                date: true,
                minDate: new Date(), // A data não pode ser no passado
            },
            time: {
                required: true,
            },
        },

        // Mensagens de erro personalizadas
        messages: {
            number: {
                required: "Por favor, informe o número da chamada",
                min: "O número deve ser maior que zero",
                digits: "O número deve conter apenas dígitos",
            },
            limit: {
                required: "Por favor, informe a quantidade de candidatos",
                min: "A quantidade deve ser maior que zero",
                digits: "A quantidade deve conter apenas dígitos",
            },
            date: {
                required: "Por favor, informe a data de comparecimento",
                date: "Por favor, informe uma data válida",
                minDate: "A data não pode ser no passado",
            },
            time: {
                required: "Por favor, informe a hora de comparecimento",
            },
        },

        // Configuração da exibição dos erros
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".mb-3").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },

        // Submissão do formulário
        submitHandler: function (form) {
            // Desabilita o botão de submit para evitar múltiplos envios
            $('button[type="submit"]')
                .prop("disabled", true)
                .html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registrando...'
                );

            // Envia o formulário
            form.submit();
        },
    });

    // Adiciona validação para data mínima (hoje)
    $.validator.addMethod("minDate", function (value, element) {
        var today = new Date();
        var inputDate = new Date(value);

        // Zera as horas para comparar apenas a data
        today.setHours(0, 0, 0, 0);
        inputDate.setHours(0, 0, 0, 0);

        return inputDate >= today;
    });
});
