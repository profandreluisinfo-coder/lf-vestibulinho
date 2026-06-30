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
        if (!value) return true; // Campo vazio é validado pelo 'required'

        const dataInformada = new Date(value);
        const dataAtual = new Date();
        dataAtual.setHours(0, 0, 0, 0); // Zera horas para comparar apenas dias

        return dataInformada <= dataAtual;
    }, '* A data não pode ser superior à data atual.');

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
                dataNaoFutura: true
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
                dataNaoFutura: "* A data não pode ser superior à data atual."
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

    $form.find("input, select, textarea").on("input change", function () {
        $(this).valid();
    });

    $form.on("keypress", e => {
        if (e.which === 13) e.preventDefault();
    });

    $form.on("invalid-form.validate", () => {
        alert("Existem campos inválidos. Por favor, revise o formulário.");
    });

    /**
     * calcula a idade do documento.
     * @param {string} date - Data de expedição do documento.
     * @returns {number} - Idade do documento em anos.
     */
    function getDocumentAge(date) {

        const today = new Date();
        const expedition = new Date(date);

        let years = today.getFullYear() - expedition.getFullYear();

        const month = today.getMonth() - expedition.getMonth();

        if (month < 0 || (month === 0 && today.getDate() < expedition.getDate())) {
            years--;
        }

        return years;
    }

    /**
     * Exibe o aviso de documento expirado.
     * @returns {Promise} - Promise que resolve quando o usuário confirma ou cancela.
     */
    function showExpeditionWarning() {

        return Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            html: `
            <p>
                O documento de identificação informado foi expedido há
                <strong>5 anos ou mais</strong>.
            </p>

            <p>
                Para ingresso no local de prova, será obrigatória a apresentação
                de documento oficial de identificação com fotografia que permita
                sua adequada identificação.
            </p>

            <p class="mb-0">
                Caso o documento informado não possua fotografia atual ou suficiente
                para sua identificação, providencie a emissão de um novo documento
                antes da data da prova.
            </p>
        `,
            confirmButtonText: 'Entendi',
            allowOutsideClick: false,
            allowEscapeKey: false
        });

    }

    /**
     * Verifica a idade do documento de identidade e exibe aviso se necessário.
     */
    $expedition.on('blur', function () {

        const years = getDocumentAge(this.value);

        // Documento recente: limpa a confirmação
        if (years < 5) {
            sessionStorage.removeItem(EXPEDITION_WARNING_KEY);
            return;
        }

        // Documento antigo: se já confirmou, não mostra novamente
        if (sessionStorage.getItem(EXPEDITION_WARNING_KEY)) {
            return;
        }

        showExpeditionWarning().then(result => {

            if (result.isConfirmed) {
                sessionStorage.setItem(EXPEDITION_WARNING_KEY, 'true');
            }

        });
    });
});