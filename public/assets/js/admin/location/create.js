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
                max: 50
            }
        },
        messages: {
            name: {
                required: '* Campo obrigatório',
                maxlength: '* Máximo de 100 caracteres'
            },
            address: {
                required: '* Campo obrigatório',
                maxlength: '* Máximo de 200 caracteres'
            },
            rooms_available: {
                required: '* Campo obrigatório',
                min: '* O número de salas deve ser maior que 0',
                max: '* O número de salas deve ser menor ou igual a 50'
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

    $("#exam-location").on("keyup keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
});