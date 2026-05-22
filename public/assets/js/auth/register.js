// ── Estado global ──────────────────────────────────────────
let pwdVisible = false;

const rules = {
    len: v => v.length >= 6 && v.length <= 8,
    upper: v => /[A-Z]/.test(v),
    lower: v => /[a-z]/.test(v),
    num: v => /[0-9]/.test(v),
};

// ── Toggle TODAS as senhas ─────────────────────────────────
function toggleAllPwd() {
    pwdVisible = !pwdVisible;
    document.querySelectorAll('.pwd-input').forEach(el => {
        el.type = pwdVisible ? 'text' : 'password';
    });
    document.querySelectorAll('.eye-btn i').forEach(i => {
        i.className = pwdVisible ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
    });
    document.getElementById('toggleAllIcon').className = pwdVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
    document.getElementById('toggleAllLabel').textContent = pwdVisible ? 'Ocultar senhas' : 'Mostrar senhas';
}

// ── Toggle senha individual ────────────────────────────────
function toggleEye(inputId, iconId) {
    const el = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    icon.className = el.type === 'text' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
}

// ── Validar e-mail ─────────────────────────────────────────
function validateEmail() {
    const el = document.getElementById('regEmail');
    const msg = document.getElementById('msgEmail');
    const v = el.value.trim();
    if (!v) {
        setFieldState(el, msg, '', '');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
        setFieldState(el, msg, 'error', '<i class="bi bi-x-circle-fill"></i> E-mail inválido');
    } else {
        setFieldState(el, msg, 'ok', '<i class="bi bi-check-circle-fill"></i> E-mail válido');
    }
    checkSubmit();
}

// ── Checar força e regras da senha ─────────────────────────
function onPwdInput() {
    const v = document.getElementById('regPwd').value;

    // Atualiza chips de regras
    setRule('len', rules.len(v));
    setRule('upper', rules.upper(v));
    setRule('lower', rules.lower(v));
    setRule('num', rules.num(v));

    // Calcula força (0-4)
    let score = 0;
    if (rules.len(v)) score++;
    if (rules.upper(v)) score++;
    if (rules.lower(v)) score++;
    if (rules.num(v)) score++;

    const fill = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    const map = [{
        w: '0%',
        bg: 'transparent',
        txt: ''
    },
    {
        w: '30%',
        bg: '#e74c3c',
        txt: 'Muito fraca'
    },
    {
        w: '55%',
        bg: 'var(--amber)',
        txt: 'Fraca'
    },
    {
        w: '78%',
        bg: '#f0c030',
        txt: 'Razoável'
    },
    {
        w: '100%',
        bg: 'var(--teal)',
        txt: 'Forte ✓'
    },
    ];
    const s = v.length === 0 ? 0 : Math.max(1, score);
    fill.style.width = map[s].w;
    fill.style.background = map[s].bg;
    label.textContent = map[s].txt;
    label.style.color = map[s].bg === 'transparent' ? 'var(--muted)' : map[s].bg;

    validateConfirm();
    checkSubmit();
}

function setRule(key, valid) {
    const chip = document.getElementById('rule-' + key);
    const ri = document.getElementById('ri-' + key);
    chip.classList.toggle('valid', valid);
    ri.innerHTML = valid ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-dash"></i>';
}

// ── Validar confirmação de senha ───────────────────────────
function validateConfirm() {
    const pwd = document.getElementById('regPwd').value;
    const conf = document.getElementById('regConfirm');
    const msg = document.getElementById('msgConfirm');
    const v = conf.value;
    if (!v) {
        setFieldState(conf, msg, '', '');
    } else if (v !== pwd) {
        setFieldState(conf, msg, 'error', '<i class="bi bi-x-circle-fill"></i> As senhas não coincidem');
    } else {
        setFieldState(conf, msg, 'ok', '<i class="bi bi-check-circle-fill"></i> Senhas conferem');
    }
    checkSubmit();
}

// ── Helper: aplica estado visual ao campo ──────────────────
function setFieldState(input, msgEl, state, html) {
    input.classList.remove('input-ok', 'input-error');
    if (state === 'ok') input.classList.add('input-ok');
    if (state === 'error') input.classList.add('input-error');
    msgEl.innerHTML = html;
    msgEl.className = 'field-msg' + (state ? ' ' + state : '');
}

// ── Habilita/desabilita botão submit ───────────────────────
function checkSubmit() {
    const email = document.getElementById('regEmail').value.trim();
    const pwd = document.getElementById('regPwd').value;
    const confirm = document.getElementById('regConfirm').value;
    const btn = document.getElementById('btnSubmit');

    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    const pwdOk = rules.len(pwd) && rules.upper(pwd) && rules.lower(pwd) && rules.num(pwd);
    const confirmOk = pwd === confirm && confirm.length > 0;

    btn.disabled = !(emailOk && pwdOk && confirmOk);
}

document.querySelector('form').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');

    btn.disabled = true;

    btn.innerHTML = `
                <span class="spinner-border spinner-border-sm"
                style="width:1rem;height:1rem;border-width:2px;">
                </span>
                Cadastrando...
            `;
});