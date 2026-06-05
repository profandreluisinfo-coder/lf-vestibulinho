// ── Estado ─────────────────────────────────────────────────
let pwdVisible = false;
const rules = {
    len: v => v.length >= 6 && v.length <= 8,
    upper: v => /[A-Z]/.test(v),
    lower: v => /[a-z]/.test(v),
    num: v => /[0-9]/.test(v),
    noSpecial: v => /^[A-Za-z0-9]*$/.test(v),
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

// ── Regras: atualiza chip ──────────────────────────────────
function setRule(key, valid) {
    const chip = document.getElementById('rule-' + key);
    const ri = document.getElementById('ri-' + key);
    chip.classList.toggle('valid', valid);
    ri.innerHTML = valid ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-dash"></i>';
}

// ── Força da senha + regras ────────────────────────────────
function onPwdInput() {
    const v = document.getElementById('newPwd').value;

    setRule('len', rules.len(v));
    setRule('upper', rules.upper(v));
    setRule('lower', rules.lower(v));
    setRule('num', rules.num(v));
    setRule('noSpecial', rules.noSpecial(v));

    let score = [rules.len(v), rules.upper(v), rules.lower(v), rules.num(v), rules.noSpecial(v)]
        .filter(Boolean).length;

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
        bg: var_amber(),
        txt: 'Fraca'
    },
    {
        w: '78%',
        bg: '#f0c030',
        txt: 'Razoável'
    },
    {
        w: '100%',
        bg: var_green(),
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

// Helpers para ler CSS vars em JS
function var_amber() {
    return getComputedStyle(document.documentElement)
        .getPropertyValue('--amber').trim() || '#F4A261';
}

function var_green() {
    return getComputedStyle(document.documentElement)
        .getPropertyValue('--green').trim() || '#27ae60';
}

// ── Confirmar senha ────────────────────────────────────────
function validateConfirm() {
    const pwd = document.getElementById('newPwd').value;
    const conf = document.getElementById('confirmPwd');
    const msg = document.getElementById('msgConfirm');
    const v = conf.value;

    conf.classList.remove('input-ok', 'input-error');
    if (!v) {
        msg.innerHTML = '';
        msg.className = 'field-msg';
        return;
    }
    if (v !== pwd) {
        conf.classList.add('input-error');
        msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> As senhas não coincidem';
        msg.className = 'field-msg error';
    } else {
        conf.classList.add('input-ok');
        msg.innerHTML = '<i class="bi bi-check-circle-fill"></i> Senhas conferem';
        msg.className = 'field-msg ok';
    }
    checkSubmit();
}

// ── Habilita botão ─────────────────────────────────────────
function checkSubmit() {
    const pwd = document.getElementById('newPwd').value;
    const conf = document.getElementById('confirmPwd').value;
    const btn = document.getElementById('btnSubmit');

    const pwdOk = rules.len(pwd) && rules.upper(pwd) && rules.lower(pwd) && rules.num(pwd) && rules.noSpecial(pwd);
    const confirmOk = pwd === conf && conf.length > 0;

    btn.disabled = !(pwdOk && confirmOk);
    btn.style.cursor = btn.disabled ? 'not-allowed' : 'pointer';
}

document.getElementById('resetForm').addEventListener('submit', async function (e) {

    e.preventDefault();

    const btn = document.getElementById('btnSubmit');

    const errorsBox = document.getElementById('serverErrors');

    errorsBox.innerHTML = '';

    btn.innerHTML = `
    <span class="spinner-border spinner-border-sm"
      style="width:.9rem;height:.9rem;border-width:2px;">
    </span>
    Salvando...
  `;

    btn.disabled = true;

    const form = e.target;

    const formData = new FormData(form);

    try {

        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        });

        const data = await response.json();

        // erro backend
        if (!response.ok) {

            let html = `
        <div class="alert alert-danger py-2 px-3 mb-3 rounded-3"
          style="font-size:.82rem;">
          <ul class="mb-0 ps-3">
      `;

            // erros validate()
            if (data.errors) {

                Object.values(data.errors).forEach(arr => {

                    arr.forEach(msg => {
                        html += `<li>${msg}</li>`;
                    });

                });

            } else {

                html += `<li>${data.message || 'Erro ao processar.'}</li>`;
            }

            html += `</ul></div>`;

            errorsBox.innerHTML = html;

            btn.disabled = false;

            btn.innerHTML = `
        <i class="bi bi-save-fill"></i>
        Redefinir Senha
      `;

            return;
        }

        // sucesso
        document.getElementById('viewForm')
            .classList.add('hidden');

        setTimeout(() => {

            document.getElementById('viewSuccess')
                .classList.remove('hidden');

        }, 250);

    } catch (err) {

        errorsBox.innerHTML = `
      <div class="alert alert-danger py-2 px-3 mb-3 rounded-3"
        style="font-size:.82rem;">
        Ocorreu um erro inesperado.
      </div>
    `;

        btn.disabled = false;

        btn.innerHTML = `
      <i class="bi bi-save-fill"></i>
      Redefinir Senha
    `;
    }
});