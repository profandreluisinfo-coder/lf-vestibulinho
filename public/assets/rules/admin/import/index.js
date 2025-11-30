$(document).ready(function () {
    $.validator.addMethod("excelExtension", function (value, element) {
        if (!value) return false;
        let ext = value.split('.').pop().toLowerCase();
        return ext === "xlsx";
    }, "Apenas arquivos .xlsx são permitidos");

    $('#import-results').validate({
        rules: {
            file: {
                required: true,
                excelExtension: true,
                filesize: 10485760
            }
        },
        messages: {
            file: {
                required: "Selecione um arquivo",
                excelExtension: "Apenas arquivos .xlsx são permitidos",
                filesize: "O arquivo deve ter no máximo 10MB"
            }
        },

        submitHandler: function (form) {
            const $btn = $("#btn-submit");

            $btn.prop("disabled", true).html(
                `<span class="spinner-border spinner-border-sm me-1"></span>Aguarde, por favor...    `
            );

            setTimeout(() => form.submit(), 50);
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('div').append(error);
        },

        highlight: function (element) {
            $(element).addClass('is-invalid');
        },

        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });

    // Previne envio com Enter
    $("#import-results").on("keyup keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#import-results").on("invalid-form.validate", function () {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });

});