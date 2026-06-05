import { lockSubmitButton } from '../../ui/spinner.js';

export function initChangePasswordValidation() {

    if (!$.validator.methods.strongPassword) {
        $.validator.addMethod("strongPassword", function(value, element) {
            // mínimo 6, máximo 8, pelo menos 1 maiúscula, 1 minúscula, 1 número, sem caracteres especiais
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,8}$/.test(value);
        }, "A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.");
    }

    const $form = $("#change-password");

    if (!$form.length) return;
    if ($form.data('validator')) return;

    const rules = {
        current_password: {
            required: true
        },
        new_password: {
            required: true,
            strongPassword: true
            // removido o "pattern: ," que estava incompleto
        },
        password_confirmation: {
            required: true,
            equalTo: "#newPassword"
        }
    };

    const messages = {
        current_password: {
            required: "* Obrigatório"
        },
        new_password: {
            required: "* Obrigatório",
            strongPassword: "* A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número. Não utilize caracteres especiais (@, #, $, *).  "
        },
        password_confirmation: {
            required: "* Obrigatório",
            equalTo: "* As senhas devem ser iguais"
        }
    };

    $form.validate({
        rules,
        messages,
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).addClass('is-valid');
        },
        submitHandler: function (form) {
            lockSubmitButton(form);
            form.submit();
        }
    });
}