$(document).ready(function () {

    // Inicialização do jQuery Validate
    $("#form-calendar").validate({
        rules: {
            year: {
                required: true,
                number: true
            },
            edital: {
                required: true
            },
            start: {
                required: true,
                date: true,
            },
            end: {
                required: true,
                date: true,
            },
            deferrals: {
                required: true,
                date: true
            },
            appeals_start: {
                required: true,
                date: true
            },
            appeals_end: {
                required: true,
                date: true
            },
            appeals_results: {
                required: true,
                date: true
            },
            definitive_list: {
                required: true,
                date: true
            },
            location_publish: {
                required: true,
                date: true,
            },
            exam_date: {
                required: true,
                date: true,
            },
            exam_time: {
                required: true,
                time: true,
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
            enrol_remaining: {
                required: true,
                date: true,
            },
            enrol_deadline: {
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
            edital: {
                required: "Por favor, selecione o edital."
            },
            start: {
                required: "Por favor, informe a data de início das inscrições.",
                date: "Por favor, insira uma data válida."
            },
            end: {
                required: "Por favor, informe a data de término das inscrições.",
                date: "Por favor, insira uma data válida."
            },
            deferrals: {
                required: "Por favor, informe a data de divulgação.",
                date: "Por favor, insira uma data válida."
            },
            appeals_start: {
                required: "Por favor, informe a data de início dos recursos.",
                date: "Por favor, insira uma data válida."
            },
            appeals_end: {
                required: "Por favor, informe a data de término dos recursos.",
                date: "Por favor, insira uma data válida."
            },
            appeals_results: {
                required: "Por favor, informe a data de divulgação do resultado dos recursos.",
                date: "Por favor, insira uma data válida."
            },
            definitive_list: {
                required: "Por favor, informe a data de divulgação da lista definitiva.",
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
            exam_time: {
                required: "Por favor, informe a hora de aplicação das provas.",
                time: "Por favor, insira um horário válido."
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
            },
            enrol_remaining: {
                required: "Por favor, informe a data de cronograma de vagas remanescentes.",
                date: "Por favor, insira uma data válida."
            },
            enrol_deadline: {
                required: "Por favor, informe a data de limite para novas convocações.",
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
});