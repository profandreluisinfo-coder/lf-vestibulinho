$(document).ready(function () {

    // Inicialização do jQuery Validate
    $("#form-calendar").validate({
        rules: {
            year: {
                required: true,
                number: true
            },
            start: {
                required: true,
                date: true,
            },
            end: {
                required: true,
                date: true,
            },
            location_publish: {
                required: true,
                date: true,
            },
            exam_date: {
                required: true,
                date: true,
            },
            answer_publish: {
                required: true,
                date: true,
            },
            revision_start: {
                required: true,
                date: true,
            },
            revision_end: {
                required: true,
                date: true,
            },
            result_publish: {
                required: true,
                date: true,
            },
            enrol_start: {
                required: true,
                date: true,
            },
            enrol_end: {
                required: true,
                date: true,
            }
        },
        messages: {
            year: {
                required: "Por favor, informe o ano do processo seletivo.",
                number: "Por favor, insira um ano válido."
            },
            start: {
                required: "Por favor, informe a data de início das inscrições.",
                date: "Por favor, insira uma data válida."
            },
            end: {
                required: "Por favor, informe a data de término das inscrições.",
                date: "Por favor, insira uma data válida."
            },
            location_publish: {
                required: "Por favor, informe a data de divulgação dos locais de prova.",
                date: "Por favor, insira uma data válida."
            },
            exam_date: {
                required: "Por favor, informe a data de aplicação das provas.",
                date: "Por favor, insira uma data válida."
            },
            answer_publish: {
                required: "Por favor, informe a data de divulgação do gabarito.",
                date: "Por favor, insira uma data válida."
            },
            revision_start: {
                required: "Por favor, informe a data de início do prazo para revisão.",
                date: "Por favor, insira uma data válida."
            },
            revision_end: {
                required: "Por favor, informe a data de término do prazo para revisão.",
                date: "Por favor, insira uma data válida."
            },
            result_publish: {
                required: "Por favor, informe a data de divulgação da classificação final.",
                date: "Por favor, insira uma data válida."
            },
            enrol_start: {
                required: "Por favor, informe a data de início das matrículas.",
                date: "Por favor, insira uma data válida."
            },
            enrol_end: {
                required: "Por favor, informe a data de cronograma de vagas remanescentes.",
                date: "Por favor, insira uma data válida."
            }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) {
            $(element).addClass("is-invalid"); // quando estiver certo
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid"); // quando estiver errado
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    // Feedback visual ao digitar
    // $('#form-calendar input').on('blur', function () {
    //     if ($(this).valid()) {
    //         $(this).addClass('is-valid');
    //     }
    // });

});