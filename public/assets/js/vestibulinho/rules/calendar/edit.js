$(document).ready(function () {

    // Inicialização do jQuery Validate
    $("#form-calendar").validate({
        rules: {
            year: {
                required: true,
                number: true,
                min: 2026
            },
            inscription_start: {
                required: true,
                date: true,
            },
            inscription_end: {
                required: true,
                date: true,
            },
            exam_location_publish: {
                required: true,
                date: true,
            },
            exam_date: {
                required: true,
                date: true,
            },
            answer_key_publish: {
                required: true,
                date: true,
            },
            exam_revision_start: {
                required: true,
                date: true,
            },
            exam_revision_end: {
                required: true,
                date: true,
            },
            final_result_publish: {
                required: true,
                date: true,
            },
            enrollment_start: {
                required: true,
                date: true,
            },
            enrollment_end: {
                required: true,
                date: true,
            }
        },
        messages: {
            year: {
                required: "Por favor, informe o ano do processo seletivo.",
                number: "Por favor, insira um ano válido.",
                min: "O ano deve ser 2026 ou posterior."
            },
            inscription_start: {
                required: "Por favor, informe a data de início das inscrições.",
                date: "Por favor, insira uma data válida."
            },
            inscription_end: {
                required: "Por favor, informe a data de término das inscrições.",
                date: "Por favor, insira uma data válida."
            },
            exam_location_publish: {
                required: "Por favor, informe a data de divulgação dos locais de prova.",
                date: "Por favor, insira uma data válida."
            },
            exam_date: {
                required: "Por favor, informe a data de aplicação das provas.",
                date: "Por favor, insira uma data válida."
            },
            answer_key_publish: {
                required: "Por favor, informe a data de divulgação do gabarito.",
                date: "Por favor, insira uma data válida."
            },
            exam_revision_start: {
                required: "Por favor, informe a data de início do prazo para revisão.",
                date: "Por favor, insira uma data válida."
            },
            exam_revision_end: {
                required: "Por favor, informe a data de término do prazo para revisão.",
                date: "Por favor, insira uma data válida."
            },
            final_result_publish: {
                required: "Por favor, informe a data de divulgação da classificação final.",
                date: "Por favor, insira uma data válida."
            },
            enrollment_start: {
                required: "Por favor, informe a data de início das matrículas.",
                date: "Por favor, insira uma data válida."
            },
            enrollment_end: {
                required: "Por favor, informe a data de cronograma de vagas remanescentes.",
                date: "Por favor, insira uma data válida."
            }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        submitHandler: function (form) {
            
            form.submit();
        }
    });

    // Feedback visual ao digitar
    $('#form-calendar input').on('blur', function () {
        if ($(this).valid()) {
            $(this).addClass('is-valid');
        }
    });

});