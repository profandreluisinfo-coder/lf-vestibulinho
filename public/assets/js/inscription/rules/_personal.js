$(function () {
    const EXPEDITION_WARNING_KEY = 'expedition-warning-confirmed';
    const $form = $("#inscription");
    const $radioYes = $('#radioYes');
    const $expedition = $('#expedition');

    // Métodos de validação personalizados
    $.validator.addMethod("noSequences", value =>
        !value.trim().split(/\s+/).some(word => /^(\S)\1+$/.test(word)),
        "* Sequência de palavras inválida"
    );

    $.validator.addMethod("wordLength", value =>
        value.trim().split(/\s+/).every(word => word.length >= 2),
        "* Use pelo menos de 2 letras"
    );

    $.validator.addMethod("minWords", value =>
        value.trim().split(/\s+/).length >= 2,
        "* Use pelo menos de 2 palavras"
    );

    $.validator.addMethod("noSimplePatterns", value => {
        if (!value) return true;
        if (/^(\d)\1+$/.test(value)) return false;
        if (/123456789|987654321/.test(value)) return false;

        return !Array.from({ length: Math.floor(value.length / 2) }, (_, i) => i + 1)
            .some(i => value === value.slice(0, i).repeat(value.length / i));
    }, "* Padrão numérico inválido para o documento");

    $.validator.addMethod('dataNaoFutura', function (value, element) {
        if (!value) return true;

        const dataInformada = new Date(value);
        const dataAtual = new Date();
        dataAtual.setHours(0, 0, 0, 0);

        return dataInformada <= dataAtual;
    }, '* A data não pode ser superior à data atual.');

    // Calcula a diferença exata em anos completos entre duas datas
    const anosEntre = (dataInicial, dataFinal) => {
        let anos = dataFinal.getFullYear() - dataInicial.getFullYear();
        const aindaNaoFezAniversarioNoAno =
            (dataFinal.getMonth() < dataInicial.getMonth()) ||
            (dataFinal.getMonth() === dataInicial.getMonth() && dataFinal.getDate() < dataInicial.getDate());

        if (aindaNaoFezAniversarioNoAno) {
            anos--;
        }

        return anos;
    };

    // Exibe um alerta (SweetAlert2) quando o documento tiver expedição superior a 5 anos,
    // avisando o candidato sobre a necessidade de um documento com foto atualizada no dia da prova
    const verificarExpedicaoAntiga = () => {
        const valor = $expedition.val();
        if (!valor) return;

        // Garante que a data está completa (YYYY-MM-DD, com os 4 dígitos do ano já digitados).
        // Inputs type="date" podem disparar 'change' com valores parciais enquanto o usuário
        // ainda está digitando o ano (ex.: "0002-01-01"), então validamos o formato e um ano plausível
        // antes de calcular qualquer coisa, evitando o alerta aparecer antes do usuário terminar.
        if (!/^\d{4}-\d{2}-\d{2}$/.test(valor)) return;

        const anoInformado = parseInt(valor.slice(0, 4), 10);
        const anoAtual = new Date().getFullYear();
        if (anoInformado < 1900 || anoInformado > anoAtual) return;

        const dataExpedicao = new Date(valor);
        if (isNaN(dataExpedicao.getTime())) return;

        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);

        const anosDesdeExpedicao = anosEntre(dataExpedicao, hoje);

        if (anosDesdeExpedicao > 5) {
            // Evita reexibir o alerta repetidamente para o mesmo valor já confirmado pelo candidato
            if (sessionStorage.getItem(EXPEDITION_WARNING_KEY) === valor) return;

            // Apenas um aviso: não bloqueia nem impede o candidato de continuar a inscrição.
            Swal.fire({
                icon: 'warning',
                title: 'Documento com expedição antiga',
                html: 'A data de expedição informada é superior a <strong>5 anos</strong>.<br><br>' +
                    'Nesse caso, o(a) candidato(a) deverá providenciar um <strong>documento oficial de ' +
                    'identificação com foto atualizada</strong> que permita seu reconhecimento no dia da prova.',
                confirmButtonText: 'Entendi'
            }).then(() => {
                sessionStorage.setItem(EXPEDITION_WARNING_KEY, valor);
            });
        }
    };

    // NOVO MÉTODO: Valida que a data de expedição não é anterior à data de nascimento
    $.validator.addMethod("dataExpedicaoValida", function(value, element) {
        if (!value) return true;
        
        const dataExpedicao = new Date(value);
        const dataNascimento = new Date($('#birth').val());
        
        if (!$('#birth').val()) return true;
        
        return dataExpedicao >= dataNascimento;
    }, "* A data de expedição não pode ser anterior à data de nascimento");

    const validateIfFilled = param => ({
        depends: el => $(el).val().trim() !== "",
        param
    });

    const ruleIf = (conditionFn, rule, param = true) => ({
        [rule]: {
            depends: conditionFn,
            param
        }
    });

    const isRadioYesChecked = () => $radioYes.is(':checked');

    const validator = $form.validate({
        ignore: ":hidden",
        rules: {
            cpf: { required: true, cpfBR: true },
            name: {
                required: true,
                maxlength: 100,
                pattern: /^[a-zA-ZÀ-ÿ ]*$/,
                noSequences: true,
                wordLength: true,
                minWords: true
            },
            social_name: {
                ...ruleIf(isRadioYesChecked, 'required'),
                ...ruleIf(isRadioYesChecked, 'maxlength', 60),
                ...ruleIf(isRadioYesChecked, 'pattern', /^[a-zA-ZÀ-ÿ ()]*$/),
                ...ruleIf(isRadioYesChecked, 'noSequences'),
                wordLength: validateIfFilled(),
                minWords: validateIfFilled()
            },
            authorization: {
                ...ruleIf(isRadioYesChecked, 'required'),
                extension: "pdf"
            },
            nationality: { required: true, range: [1, 4] },
            doc_type: { required: true, range: [1, 3] },
            doc_number: {
                required: true,
                minlength: 7,
                maxlength: 11,
                pattern: /^\d{7}[\dA-Za-z]{0,4}$/,
                noSimplePatterns: true
            },
            expedition: {
                required: true,
                date: true,
                dataNaoFutura: true,
                dataExpedicaoValida: true // NOVA REGRA ADICIONADA
            },
            gender: {
                required: true,
                range: [1, 4]
            },
            birth: {
                required: true,
                date: true,
                dataNaoFutura: true
            },
            phone: {
                required: true,
                minlength: 14,
                maxlength: 15
            }
        },
        messages: {
            cpf: {
                required: "* Obrigatório.",
                cpfBR: "* CPF inválido."
            },
            name: {
                required: "* Obrigatório.",
                maxlength: "* Máximo de 60 caracteres.",
                pattern: "* Apenas letras, acentos e espaços."
            },
            social_name: {
                required: "* Obrigatório.",
                maxlength: "* Máximo de 60 caracteres.",
                pattern: "* Apenas letras, acentos e espaços."
            },
            authorization: {
                required: "* Obrigatório.",
                extension: "* Apenas arquivos PDF são permitidos."
            },
            nationality: {
                required: "* Obrigatório.",
                range: "* Selecione uma nacionalidade válida."
            },
            doc_type: {
                required: "* Obrigatório.",
                range: "* Selecione um tipo de documento válido."
            },
            doc_number: {
                required: "* Obrigatório.",
                minlength: "* Use no mínimo 7 caracteres.",
                maxlength: "* Use no máximo 11 caracteres.",
                pattern: "* Formato inválido. Use apenas letras e números."
            },
            expedition: {
                required: "* Obrigatório.",
                date: "* Data inválida.",
                dataNaoFutura: "* A data não pode ser superior à data atual.",
                dataExpedicaoValida: "* A data de expedição não pode ser anterior à data de nascimento." // NOVA MENSAGEM
            },
            gender: {
                required: "* Obrigatório.",
                range: "* Selecione um gênero válido."
            },
            birth: {
                required: "* Obrigatório.",
                date: "* Data inválida.",
                dataNaoFutura: "* A data não pode ser superior à data atual."
            },
            phone: {
                required: "* Obrigatório.",
                minlength: "* Deve conter 14 ou 15 caracteres.",
                maxlength: "* Máximo de 15 caracteres."
            }
        },
        submitHandler: form => form.submit(),
        errorPlacement: (error, element) =>
            error.addClass('invalid-feedback').appendTo(element.closest('.form-group')),
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid')
    });

    // ... resto do código ...

    // NOVOS LISTENERS PARA VALIDAÇÃO DINÂMICA
    $('#birth').on('change', function() {
        if ($expedition.val()) {
            $expedition.valid();
        }
    });

    $expedition.on('change', function() {
        $(this).valid();
        verificarExpedicaoAntiga();
    });

    // Verifica também ao carregar a página, caso o campo já venha preenchido
    // (ex.: candidato retornando a uma etapa anterior da inscrição)
    verificarExpedicaoAntiga();

    // ... resto do código existente ...
});