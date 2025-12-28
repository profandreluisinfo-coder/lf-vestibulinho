import { lockSubmitButton } from '../../ui/spinner.js';

/**
 * Inicializa a validação do formulário de reenvio de e-mail de verificação
 * Verifica se o formulário existe e se não foi inicializado anteriormente
 * Define as regras de validação e mensagens de erro
 * Adiciona um método customizado para validação de e-mail
 * Substitui o método de submissão do formulário para desabilitar o botão de envio e mudar o texto para "Enviando..."
 */
export function initResendEmailValidation() {

    const $form = $("#resend-email");
    
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
        errorPlacement: (error, element) => {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        
        highlight: element =>
            $(element).addClass('is-invalid'),

        unhighlight: element =>
            $(element).removeClass('is-invalid').addClass('is-valid'),

        /**
         * Submissão do formulário
         * Desabilita o botão de submit, muda o texto do botão para "Enviando..." e envia o formulário
         * @param {object} form - O formulário a ser submetido
         */
        submitHandler: function (form) {
            lockSubmitButton(form);
            form.submit();
        }
    });
}