// ── Validação do e-mail em tempo real ──────────────────────
function onEmailInput() {
    const el = document.getElementById('myEmail');
    const msg = document.getElementById('msgEmail');
    const btn = document.getElementById('btnSubmit');  // ← ADICIONE ISTO
    const v = el.value.trim();

    if (!v) {
        el.classList.remove('input-ok', 'input-error');
        msg.innerHTML = '';
        msg.className = 'field-msg';
        btn.disabled = true;  // ← ADICIONE ISTO
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
        el.classList.remove('input-ok');
        el.classList.add('input-error');
        msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> E-mail inválido';
        msg.className = 'field-msg error';
        btn.disabled = true;  // ← ADICIONE ISTO
    } else {
        el.classList.remove('input-error');
        el.classList.add('input-ok');
        msg.innerHTML = '<i class="bi bi-check-circle-fill"></i> E-mail válido';
        msg.className = 'field-msg ok';
        btn.disabled = false;  // ← ADICIONE ISTO (habilita!)
    }
}

// ── Contador para reenvio ──────────────────────────────────
let countdownInterval = null;

function startCountdown() {
    // let seconds   = 60;
    let seconds = 300;
    const countEl = document.getElementById('countdown');
    const labelEl = document.getElementById('resendLabel');
    const btnEl = document.getElementById('btnResend');

    countdownInterval = setInterval(() => {
        seconds--;
        countEl.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(countdownInterval);
            btnEl.disabled = false;
            btnEl.classList.add('ready');
            labelEl.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Reenviar e-mail';
        }
    }, 1000);
}

async function doResend(btn) {
    if (btn.disabled) return;

    btn.disabled = true;
    btn.classList.remove('ready');
    btn.innerHTML = `
    <span class="spinner-border spinner-border-sm"
        style="width:.8rem;height:.8rem;border-width:2px;">
    </span>
    Enviando...
`;

    try {
        const response = await fetch(
            document.getElementById('resend-email').action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: new FormData(document.getElementById('resend-email'))
        }
        );

        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        btn.innerHTML = `
        <i class="bi bi-check-circle-fill"></i>
        Enviado!
    `;

        setTimeout(() => {
            // Reinicia barra
            const timerBar = document.getElementById('timerBar');
            timerBar.style.animation = 'none';
            requestAnimationFrame(() => {
                timerBar.style.animation =
                    'progressFill 300s linear reverse forwards';
            });

            // Reinicia botão
            btn.innerHTML = `
            <i class="bi bi-arrow-clockwise"></i>
            <span id="resendLabel">
                Reenviar em <span id="countdown">60</span>s
            </span>
        `;

            startCountdown();
        }, 1500);

    } catch (error) {
        btn.disabled = false;
        btn.classList.add('ready');
        btn.innerHTML = `
        <i class="bi bi-x-circle-fill"></i>
        Tentar novamente
    `;
    }
}

// ── Submit do formulário ───────────────────────────────────
document
    .getElementById('resend-email')
    .addEventListener('submit', async function (event) {
        event.preventDefault();

        const form = this;
        const input = document.getElementById('myEmail');
        const msg = document.getElementById('msgEmail');
        const btn = document.getElementById('btnSubmit');
        const email = input.value.trim();

        // Reset visual
        input.classList.remove('input-error');

        // Validação front-end
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            input.classList.add('input-error');
            msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> Informe um e-mail válido.';
            msg.className = 'field-msg error';
            input.focus();
            return;
        }

        // Loading
        btn.disabled = true;
        btn.innerHTML = `
        <span class="spinner-border spinner-border-sm"
            style="width:.9rem;height:.9rem;border-width:2px;">
        </span>
        Enviando...
    `;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: new FormData(form)
            });

            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            // Mostra e-mail na tela de sucesso
            document.getElementById('emailPreviewText').textContent = email;

            // Troca de view
            const viewForm = document.getElementById('viewForm');
            const viewSuccess = document.getElementById('viewSuccess');

            viewForm.style.opacity = '0';
            viewForm.style.transform = 'translateY(-12px)';

            setTimeout(() => {
                viewForm.classList.add('hidden');
                viewSuccess.classList.remove('hidden');
                viewSuccess.style.opacity = '1';
                viewSuccess.style.transform = 'translateY(0)';

                startCountdown();
            }, 350);

        } catch (error) {
            btn.disabled = false;
            btn.innerHTML = `
            <i class="bi bi-envelope-check"></i>
            Reenviar E-mail de Verificação
        `;
            msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> Não foi possível enviar o e-mail.';
            msg.className = 'field-msg error';
        }
    });