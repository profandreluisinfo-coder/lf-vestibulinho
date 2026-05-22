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

document.querySelector('form').addEventListener('submit', function (event) {

    if (!validate()) {
        showError('Preencha todos os campos obrigatórios.');
        event.preventDefault();
        return;
    }

    const btn = document.querySelector('.btn-login');

    btn.disabled = true;

    btn.innerHTML = `
    <span class="spinner-border spinner-border-sm"
      style="width:.95rem;height:.95rem;border-width:2px;">
    </span>
    Entrando...
  `;
});