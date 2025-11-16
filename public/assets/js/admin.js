$(document).ready(function () {

    $("#form-login").validate({

        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }         
        },
        messages: {            
            email: {
                required: "Obrigatório",
                email: "Por favor, insira um e-mail v&aacute;lido"
            },
            password: {
                required: "Obrigatório"
            }
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
            // $(element).addClass('is-valid');
        }
    });
});