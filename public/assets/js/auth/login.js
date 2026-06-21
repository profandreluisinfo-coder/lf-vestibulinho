// ── Toggle olhinho ─────────────────────────────────────────
function toggleEye(inputId, iconId) {
    const el = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    icon.className = el.type === 'text' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
}

// ── Limpa borda de erro ao digitar ─────────────────────────
function clearError(el) {
    el.classList.remove('input-error');
    document.getElementById('alertError').classList.add('hidden');
}

// ── Valida campos ──────────────────────────────────────────
function validate() {
    const email = document.getElementById('loginEmail');
    const pwd = document.getElementById('loginPassword');
    let ok = true;

    if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        email.classList.add('input-error');
        ok = false;
    }
    if (!pwd.value.trim()) {
        pwd.classList.add('input-error');
        ok = false;
    }
    return ok;
}

// ── Mostrar erro ───────────────────────────────────────────
function showError(msg) {
    const alert = document.getElementById('alertError');
    document.getElementById('alertMsg').textContent = msg;
    alert.classList.remove('hidden');
    alert.style.animation = 'none';
    requestAnimationFrame(() => {
        alert.style.animation = 'shake .4s ease';
    });
}

// ── Restaura botão ─────────────────────────────────────────
function resetBtn(btn) {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Entrar';
}

// ── Valida e habilita/desabilita botão ─────────────────────
function validateForm() {
    const email = document.getElementById('loginEmail');
    const pwd = document.getElementById('loginPassword');
    const btn = document.querySelector('.btn-login');
    
    // Verifica email
    const emailOk = email.value.trim() && 
                    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    
    // Verifica password
    const pwdOk = pwd.value.trim();
    
    // Habilita botão APENAS se ambos forem válidos
    btn.disabled = !(emailOk && pwdOk);
}

// ── Submit via fetch ───────────────────────────────────────
document.querySelector('form').addEventListener('submit', async function (event) {
    event.preventDefault();

    if (!validate()) {
        showError('Preencha todos os campos obrigatórios.');
        return;
    }

    const btn = document.querySelector('.btn-login');
    const form = event.target;

    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm"
              style="width:.95rem;height:.95rem;border-width:2px;">
        </span>
        Entrando...
    `;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',  // ← faz wantsJson() retornar true
            },
        });

        const data = await response.json();

        if (data.success) {
            // ── Exibe overlay e redireciona ─────────────────
            document.getElementById('successOverlay').classList.add('show');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1800);
            return;
        }

        showError(data.message ?? 'E-mail ou senha incorretos.');
        resetBtn(btn);

    } catch (err) {
        console.error('Erro na requisição de login:', err);
        showError('Erro de conexão. Tente novamente.');
        resetBtn(btn);
    }
});