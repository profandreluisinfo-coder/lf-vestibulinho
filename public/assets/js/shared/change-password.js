// change-password.js - Versão unificada com correção
import { lockSubmitButton } from './spinner.js';

window.initChangePasswordValidation = function () {

    if (!$.validator.methods.strongPassword) {
        $.validator.addMethod("strongPassword", function(value, element) {
            return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,8}$/.test(value);
        }, "A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.");
    }

    const $form = $("#change-password");

    if (!$form.length) {
        console.warn('Formulário #change-password não encontrado');
        return;
    }
    
    if ($form.data('validator')) {
        console.warn('Validador já inicializado');
        return;
    }

    const rules = {
        current_password: {
            required: true
        },
        new_password: {
            required: true,
            strongPassword: true
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
            strongPassword: "* A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número. Não utilize caracteres especiais (@, #, $, *)."
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

    // Aguarda um pequeno delay para garantir que o DOM esteja pronto
    setTimeout(function() {
        // Inicializa o medidor de força da senha
        initPasswordStrength('#newPassword', $form);
        
        // Inicializa o toggle de mostrar/ocultar senhas
        initPasswordToggle();
    }, 100);
}

// Função de força de senha
function initPasswordStrength(inputSelector, formElement) {
    // console.log('Inicializando medidor de força para:', inputSelector);
    
    const inputs = document.querySelectorAll(inputSelector);
    console.log('Inputs encontrados:', inputs.length);
    
    inputs.forEach(function (input) {
        if (input.dataset.strengthInit) {
            console.log('Input já inicializado');
            return;
        }
        input.dataset.strengthInit = true;

        const container = input.closest('.mb-3');
        if (!container) {
            console.warn('Container .mb-3 não encontrado para o input');
            return;
        }

        console.log('Container encontrado:', container);

        const form = formElement || input.closest('form');
        const submitButton = form ? form[0]?.querySelector('.submit-password') : null;

        // Remove elementos antigos se existirem
        const oldProgress = container.querySelector('.progress');
        const oldText = container.querySelector('.text-muted.d-block.mt-1');
        const oldChecklist = container.querySelector('.password-rules');
        
        if (oldProgress) oldProgress.remove();
        if (oldText) oldText.remove();
        if (oldChecklist) oldChecklist.remove();

        // barra de força
        const progress = document.createElement('div');
        progress.className = 'progress mt-2';
        progress.style.height = '6px';

        const bar = document.createElement('div');
        bar.className = 'progress-bar';
        bar.style.width = '0%';
        progress.appendChild(bar);

        // texto da força
        const strengthText = document.createElement('small');
        strengthText.className = 'text-muted d-block mt-1';
        strengthText.innerText = 'Digite uma senha';

        // checklist
        const checklist = document.createElement('ul');
        checklist.className = 'list-unstyled small mt-2 password-rules';

        checklist.innerHTML = `
            <li data-rule="length">❌ 6 a 8 caracteres</li>
            <li data-rule="upper">❌ 1 letra maiúscula</li>
            <li data-rule="lower">❌ 1 letra minúscula</li>
            <li data-rule="number">❌ 1 número</li>
        `;

        container.appendChild(progress);
        container.appendChild(strengthText);
        container.appendChild(checklist);

        console.log('Elementos de força criados com sucesso');

        const rules = {
            length: checklist.querySelector('[data-rule="length"]'),
            upper: checklist.querySelector('[data-rule="upper"]'),
            lower: checklist.querySelector('[data-rule="lower"]'),
            number: checklist.querySelector('[data-rule="number"]')
        };

        input.addEventListener('input', function () {
            let password = this.value;
            let score = 0;

            if (password.length >= 6 && password.length <= 8) {
                rules.length.innerHTML = "✔ 6 a 8 caracteres";
                rules.length.style.color = "green";
                score++;
            } else {
                rules.length.innerHTML = "❌ 6 a 8 caracteres";
                rules.length.style.color = "";
            }

            if (/[A-Z]/.test(password)) {
                rules.upper.innerHTML = "✔ 1 letra maiúscula";
                rules.upper.style.color = "green";
                score++;
            } else {
                rules.upper.innerHTML = "❌ 1 letra maiúscula";
                rules.upper.style.color = "";
            }

            if (/[a-z]/.test(password)) {
                rules.lower.innerHTML = "✔ 1 letra minúscula";
                rules.lower.style.color = "green";
                score++;
            } else {
                rules.lower.innerHTML = "❌ 1 letra minúscula";
                rules.lower.style.color = "";
            }

            if (/[0-9]/.test(password)) {
                rules.number.innerHTML = "✔ 1 número";
                rules.number.style.color = "green";
                score++;
            } else {
                rules.number.innerHTML = "❌ 1 número";
                rules.number.style.color = "";
            }

            let width = (score / 4) * 100;
            bar.style.width = width + "%";

            if (score <= 1) {
                bar.className = "progress-bar bg-danger";
                strengthText.innerText = "Senha fraca";
            }
            else if (score <= 3) {
                bar.className = "progress-bar bg-warning";
                strengthText.innerText = "Senha média";
            }
            else {
                bar.className = "progress-bar bg-success";
                strengthText.innerText = "Senha forte";
            }

            if (submitButton) {
                submitButton.disabled = score < 4;
            }

            if (formElement && formElement.data('validator')) {
                formElement.validate().element(input);
            }
        });
    });
}

// Função para toggle de mostrar/ocultar senhas
function initPasswordToggle() {
    // console.log('Inicializando toggle de senhas');
    
    const toggleButton = document.getElementById('toggleAllPasswords');
    
    if (!toggleButton) {
        console.warn('Botão #toggleAllPasswords não encontrado');
        return;
    }
    
    if (toggleButton.dataset.toggleInit) {
        console.log('Toggle já inicializado');
        return;
    }
    toggleButton.dataset.toggleInit = true;

    console.log('Toggle encontrado e inicializado');

    toggleButton.addEventListener('click', function () {
        let inputs = document.querySelectorAll('.password-field');
        
        if (inputs.length === 0) {
            console.warn('Nenhum campo .password-field encontrado');
            return;
        }

        let showing = inputs[0].type === 'text';

        inputs.forEach(function (input) {
            input.type = showing ? 'password' : 'text';
        });

        if (showing) {
            this.innerHTML = '<i class="bi bi-eye"></i> Mostrar senhas';
        } else {
            this.innerHTML = '<i class="bi bi-eye-slash"></i> Ocultar senhas';
        }
    });
}

// Mantém as funções globais
window.initPasswordStrength = initPasswordStrength;
window.initPasswordToggle = initPasswordToggle;

initChangePasswordValidation();