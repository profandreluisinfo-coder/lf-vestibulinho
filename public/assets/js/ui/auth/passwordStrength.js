export function initPasswordStrength(selector = '.password-strength-field') {

    document.querySelectorAll(selector).forEach(function (input) {

        if (input.dataset.strengthInit) return;
        input.dataset.strengthInit = true;

        const container = input.closest('.mb-3');

        const form = input.closest('form');
        const submitButton = form.querySelector('.submit-password');

        // barra de força
        const progress = document.createElement('div');
        progress.className = 'progress mt-2';
        progress.style.height = '6px';

        const bar = document.createElement('div');
        bar.className = 'progress-bar';

        progress.appendChild(bar);

        // texto da força
        const strengthText = document.createElement('small');
        strengthText.className = 'text-muted d-block mt-1';

        // checklist
        // const checklist = document.createElement('ul');
        // checklist.className = 'list-unstyled small mt-2';
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

        const rules = {
            length: checklist.querySelector('[data-rule="length"]'),
            upper: checklist.querySelector('[data-rule="upper"]'),
            lower: checklist.querySelector('[data-rule="lower"]'),
            number: checklist.querySelector('[data-rule="number"]')
        };

        input.addEventListener('input', function () {

            let password = this.value;
            let score = 0;

            // regra tamanho
            if (password.length >= 6 && password.length <= 8) {
                rules.length.innerHTML = "✔ 6 a 8 caracteres";
                rules.length.style.color = "green";
                score++;
            } else {
                rules.length.innerHTML = "❌ 6 a 8 caracteres";
                rules.length.style.color = "";
            }

            // maiúscula
            if (/[A-Z]/.test(password)) {
                rules.upper.innerHTML = "✔ 1 letra maiúscula";
                rules.upper.style.color = "green";
                score++;
            } else {
                rules.upper.innerHTML = "❌ 1 letra maiúscula";
                rules.upper.style.color = "";
            }

            // minúscula
            if (/[a-z]/.test(password)) {
                rules.lower.innerHTML = "✔ 1 letra minúscula";
                rules.lower.style.color = "green";
                score++;
            } else {
                rules.lower.innerHTML = "❌ 1 letra minúscula";
                rules.lower.style.color = "";
            }

            // número
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

        });

    });

}