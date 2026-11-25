$(document).ready(function () {
    $("#courseForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            description: {
                required: true,
                minlength: 3
            },
            duration: {
                required: true,
                minlength: 1,
                digits: true
            }
        },
        messages: {
            name: {
                required: "Por favor, insira o nome do curso",
                minlength: "O nome do curso deve ter pelo menos 3 caracteres"
            },
            description: {
                required: "Por favor, insira a descrição do curso",
                minlength: "A descrição do curso deve ter pelo menos 3 caracteres"
            },
            duration: {
                required: "Por favor, insira a duração do curso",
                minlength: "A duração do curso deve ter pelo menos 1 caractere",
                digits: "A duração do curso deve conter apenas números"
            }
        },
        submitHandler: function (form) {
            const $btn = $(form).find('button[type="submit"]');
            $btn.prop("disabled", true).html(
                `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Aguarde...`
            );
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
    
    $("#courseForm").on("keyup keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#courseForm").on("invalid-form.validate", function () {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });

})