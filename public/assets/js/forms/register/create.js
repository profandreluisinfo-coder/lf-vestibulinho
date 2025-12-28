import { lockSubmitButton } from '../../ui/spinner.js';

/**
 * Inicializa a validação do formulário de registro
 * Verifica se o formulário existe e se não foi inicializado anteriormente
 * Define as regras de validação e mensagens de erro
 * Adiciona um método customizado para validação de e-mail
 * Adiciona um método customizado para validação de senha forte
 * Substitui o método de submissão do formulário para desabilitar o botão de envio e mudar o texto para "Enviando..."
 */
export function initRegisterValidation() {
    // Verifica se o formulário existe
    const $form = $('#form-register');
    if (!$form.length) return;

    // Definição das regras e mensagens de validação
    const invalidDomains = [
        '@gmail.com.br', '@test.com', '@fakeemail.com', '@invalid.com',
        '@example.com', '@example.com.br', '@email.com', '@email.com.br', '@educacaosumare.com'
    ];

    // Regras customizadas
    // O uso do if  garante:
    // registro único
    // comportamento previsível
    // zero impacto negativo
    if (!$.validator.methods.isValidDomainName) {
        $.validator.addMethod("isValidDomainName", function (value) {
            return !invalidDomains.some(domain => value.endsWith(domain));
        }, "O domínio de e-mail informado é inválido.");
    }

    if (!$.validator.methods.strongPassword) {
        $.validator.addMethod("strongPassword", function (value) {
            return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,8}$/.test(value);
         }, "Senha fraca.");
    }

    $("#form-register").validate({
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
            },
            terms: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Obrigatório",
                email: "Insira um e-mail válido"
            },
            password: {
                required: "Obrigatório",
                strongPassword: "Senha fraca",
            },
            password_confirmation: {
                required: "Obrigatório",
                equalTo: "As senhas devem ser iguais"
            },
            terms: {
                required: "Aceite os termos"
            }
        },
        /**
         * Função para posicionamento de mensagens de erro
         * Verifica se o elemento é um checkbox e coloca a mensagem de erro no pai com a classe 'form-check'
         * Caso contrário, coloca a mensagem de erro no pai com a classe 'mb-3'
         * @param {jQuery} error - Elemento de erro
         * @param {jQuery} element - Elemento que originou o erro
         */
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            if (element.attr("type") === "checkbox") {
                element.closest('.form-check').append(error);
            } else {
                element.closest('.mb-3').append(error);
            }
        },
        /**
         * Adiciona a classe de erro ao elemento
         * @param {jQuery} element - Elemento a ser marcado como inválido
         */
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        /**
         * Remove a classe de erro do elemento e adiciona uma classe de sucesso
         * @param {jQuery} element - Elemento a ser desmarcado
         */
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        /**
         * Submissão do formulário
         * Desabilita o botão de submit, muda o texto do botão para "Enviando..." e envia o formulário
         * @param {object} form - O formulário a ser submetido
         * */
        submitHandler: function (form) {
            lockSubmitButton(form);
            form.submit();
        }
    });
}