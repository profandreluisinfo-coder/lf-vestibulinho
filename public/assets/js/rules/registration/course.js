$(document).ready(function () {

    $("#inscription").validate({
        rules: {
            course_id: {
                required: true,
            },
        },
        messages: {
            course_id: {
                required: 'Obrigatório',
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