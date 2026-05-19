import { lockSubmitButton } from '../../ui/spinner.js';

/**
 * Inicializa a validação do formulário de registro
 */
export function initRegisterValidation() {

    // Verifica se o formulário existe
    const $form = $('#form-register');

    if (!$form.length) return;

    // Botão submit
    const $submitButton = $('.submit-password');

    // Lista de domínios inválidos
    const invalidDomains = [
        '@gmail.com.br',
        '@test.com',
        '@fakeemail.com',
        '@invalid.com',
        '@example.com',
        '@example.com.br',
        '@email.com',
        '@email.com.br',
        '@educacaosumare.com'
    ];

    /**
     * Regra customizada:
     * domínio inválido
     */
    if (!$.validator.methods.isValidDomainName) {

        $.validator.addMethod(
            "isValidDomainName",

            function (value) {
                return !invalidDomains.some(domain =>
                    value.endsWith(domain)
                );
            },

            "O domínio de e-mail informado é inválido."
        );
    }

    /**
     * Regra customizada:
     * senha forte
     */
    if (!$.validator.methods.strongPassword) {

        $.validator.addMethod(
            "strongPassword",

            function (value) {
                return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,8}$/.test(value);
            },

            "Senha fraca."
        );
    }

    /**
     * Inicializa validação
     */
    const validator = $form.validate({

        rules: {

            email: {
                required: true,
                email: true,
                isValidDomainName: true
            },

            password: {
                required: true,
                strongPassword: true
            },

            password_confirmation: {
                required: true,
                equalTo: "#registerPassword"
            }
        },

        messages: {

            email: {
                required: "* Obrigatório",
                email: "* Insira um e-mail válido"
            },

            password: {
                required: "* Obrigatório",
                strongPassword: "* Senha fraca"
            },

            password_confirmation: {
                required: "* Obrigatório",
                equalTo: "* As senhas devem ser iguais"
            }
        },

        /**
         * Posicionamento das mensagens
         */
        errorPlacement: function (error, element) {

            error.addClass('invalid-feedback');

            element.closest('.mb-3').append(error);
        },

        /**
         * Campo inválido
         */
        highlight: function (element) {

            $(element)
                .addClass('is-invalid')
                .removeClass('is-valid');
        },

        /**
         * Campo válido
         */
        unhighlight: function (element) {

            $(element)
                .removeClass('is-invalid')
                .addClass('is-valid');
        },

        /**
         * Submit do formulário
         */
        submitHandler: function (form) {

            lockSubmitButton(form);

            form.submit();
        }
    });

    /**
     * Controla estado do botão submit
     */
    function toggleSubmitButton() {

        const isValid = validator.checkForm();

        $submitButton.prop('disabled', !isValid);
    }

    /**
     * Revalida ao digitar
     */
    $form.find('input').on('keyup blur change', function () {

        toggleSubmitButton();
    });

    /**
     * Estado inicial
     */
    toggleSubmitButton();
}