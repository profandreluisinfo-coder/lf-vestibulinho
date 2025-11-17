$(document).ready(function () {
    // Mostrar/ocultar campo split_from_room conforme seleção
    $('#split_locations').change(function () {
        if ($(this).val() === 'yes') {
            $('#split_start_wrapper').removeClass('d-none');
        } else {
            $('#split_start_wrapper').addClass('d-none');
            // Resetar o valor quando esconder
            $('#split_from_room').val('');
        }
    });

    // Validação do formulário
    $("#exam-schedule").validate({
        rules: {
            candidates_per_room: {
                required: true,
                min: 1,
                max: 50
            },
            split_locations: {
                required: true
            },
            split_from_room: {
                required: function (element) {
                    return $('#split_locations').val() === 'yes';
                },
                min: 2
            },
            exam_date: {
                required: true,
                date: true
            },
            exam_time: {
                required: true
            }
        },
        messages: {
            candidates_per_room: {
                required: 'Obrigatório',
                min: 'O número de candidatos deve ser maior que 0',
                max: 'O número de candidatos deve ser menor ou igual a 50'
            },
            split_locations: {
                required: 'Obrigatório'
            },
            split_from_room: {
                required: 'Obrigatório quando dividir locais',
                min: 'O número deve ser maior que 1'
            },
            exam_date: {
                required: 'Obrigatório',
                date: 'Data inválida'
            },
            exam_time: {
                required: 'Obrigatório'
            }
        },
        // submitHandler: function (form) {
        //     form.submit();
        // },
        submitHandler: function (form) {
            var $submitBtn = $(form).find('button[type="submit"]');

            $submitBtn.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-2"></span>Aguarde...');

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

    // Desabilitar submit com Enter (se ainda necessário)
    $("#exam-schedule").on("keyup keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    // Verificar estado inicial do campo split_locations
    if ($('#split_locations').val() === 'yes') {
        $('#split_start_wrapper').removeClass('d-none');
    }
});