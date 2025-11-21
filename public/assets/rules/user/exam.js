$(document).ready(function () {

    $("#exam-location").validate({
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            address: {
                required: true,
                maxlength: 200
            },
            rooms_available: {
                required: true,
                min: 1,
                max: 40
            }
        },
        messages: {
            name: {
                required: 'Obrigatório',
                maxlength: 'Máximo de 100 caracteres'
            },
            address: {
                required: 'Obrigatório',
                maxlength: 'Máximo de 200 caracteres'
            },
            rooms_available: {
                required: 'Obrigatório',
                min: 'O número de salas deve ser maior que 0',
                max: 'O número de salas deve ser menor ou igual a 40'
            }
        },
        submitHandler: function (form) {
            form.submit();
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-floating').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });

    $("#exam-location").on("keyup keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#exam-location").on("invalid-form.validate", function () {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });

});