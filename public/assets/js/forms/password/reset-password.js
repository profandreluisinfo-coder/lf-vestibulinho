import { lockSubmitButton } from '../../ui/spinner.js';
/**
 * Inicializa a validação do formulário de redefinição de senha
 * Verifica se o formulário existe e se não foi inicializado anteriormente
 * Define as regras de validação e mensagens de erro
 * Adiciona um método customizado para validação de e-mail
 * Substitui o método de submissão do formulário para desabilitar o botão de envio e mudar o texto para "Aguarde..."
 */
 export function initResetPasswordValidation() {
     
    // Adiciona método customizado para validação de senha forte
    if (!$.validator.methods.strongPassword) {
        $.validator.addMethod("strongPassword", function(value, element) {
            // Verifica se tem 6-8 caracteres, pelo menos uma maiúscula, uma minúscula e um número
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,8}$/.test(value);
        }, "A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.");
    }

    const $form = $("#reset-password");

    if (!$form.length) return;

    // Evita inicializar duas vezes
    if ($form.data('validator')) return;
    
    const rules = {
        password: {
            required: true,
            strongPassword: true
        },
        password_confirmation: {
            required: true,
            equalTo: "#password"
        }
    };
    
    const messages = {
        password: {
            required: "Obrigatório",
            strongPassword: "Senha fraca"
        },
        password_confirmation: {
            required: "Obrigatório",
            equalTo: "As senhas devem ser iguais"
        }
    };

    $form.validate({
        rules,
        messages,
        /**
         * Função para posicionamento de mensagens de erro
         * @param {jQuery} error - Elemento de erro
         * @param {jQuery} element - Elemento que originou o erro
         */
        errorPlacement: (error, element) => {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },

        highlight: element => $(element).addClass('is-invalid'),

        unhighlight: element => $(element).removeClass('is-invalid').addClass('is-valid'),
        
        /**
         * Função para tratar o envio do formulário
         * Desabilita o botão de envio, troca o texto para "Aguarde..." e
         * adiciona um spinner de carregamento.
         * @param {jQuery} form - Formulário que originou o envio
         */
        submitHandler: function (form) {
            lockSubmitButton(form);
            form.submit();
        }
    });
 }