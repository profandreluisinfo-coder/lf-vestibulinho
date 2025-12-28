import { lockSubmitButton } from '../../ui/spinner.js';

/**
 * Inicializa a validação do formulário de recuperação de senha
 * Verifica se o formulário existe e se não foi inicializado anteriormente
 * Define as regras de validação e mensagens de erro
 * Adiciona um método customizado para validação de e-mail
 * Substitui o método de submissão do formulário para desabilitar o botão de envio e mudar o texto para "Aguarde..."
 */
export function initForgotPasswordValidation() {

    const $form = $("#forgot-password");

    if (!$form.length) return;

    // Evita inicializar duas vezes
    if ($form.data('validator')) return;

    const rules = {
        email: {
            required: true,
            email: true
        }
    };

    const messages = {
        email: {
            required: "Obrigatório",
            email: "Por favor, insira um e-mail válido"
        }
    };

    $form.validate({
        
        rules,
        messages,
        /**
         * Coloca a mensagem de erro em um elemento pai com a classe 'mb-3'
         * @param {jQuery} error - Elemento que contem a mensagem de erro
         * @param {jQuery} element - Elemento que precisa exibir a mensagem de erro
         */
        errorPlacement: (error, element) => {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },        
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid').addClass('is-valid'),

        /**
         * Substitui o método de submissão do formulário para desabilitar o botão de envio e mudar o texto para "Aguarde..."
         * @param {jQuery} form - O formulário a ser submetido
         */
        submitHandler: function (form) {
            lockSubmitButton(form);
            form.submit();
        }
    });
}