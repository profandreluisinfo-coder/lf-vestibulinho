<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Redefinição de senha." />
    <title>Redefinir Senha — Vestibulinho {{ $calendar?->year }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <style>
        
    </style>
</head>

<body>

    <div class="page-wrapper">

        <!-- ═══════════════════ PAINEL ESQUERDO ═══════════════════════ -->
        <aside class="panel-left">
            <div class="deco-circle deco-c1"></div>
            <div class="deco-circle deco-c2"></div>
            <div class="deco-circle deco-c3"></div>
            <div class="deco-blob"></div>

            <!-- Brand -->
            <div class="panel-brand">
                <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <h2>EM Dr. Leandro Franceschini</h2>
                <p>Vestibulinho {{ $calendar?->year }} · Cursos Técnicos Gratuitos</p>
            </div>

            <!-- Centro -->
            <div class="panel-center">
                <p class="headline">
                    Quase lá!<br>Crie uma senha<br><em>nova e segura</em>.
                </p>
                <p class="lead-text">
                    Você está a um passo de recuperar o acesso à sua Área do Candidato. Escolha uma senha forte.
                </p>

                <!-- Dicas de senha segura -->
                <div class="tips-card">
                    <h6><i class="bi bi-lightbulb-fill"></i> Dicas para uma senha segura</h6>
                    <div class="tip-item">
                        <div class="tip-icon"><i class="bi bi-check-lg"></i></div>
                        <p>Misture letras <strong style="color:#fff;">maiúsculas</strong> e <strong
                                style="color:#fff;">minúsculas</strong> para aumentar a complexidade.</p>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon"><i class="bi bi-check-lg"></i></div>
                        <p>Inclua pelo menos um <strong style="color:#fff;">número</strong> na senha.</p>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon"><i class="bi bi-x-lg" style="color:#f87171;"></i></div>
                        <p>Evite sequências óbvias como <strong style="color:#f87171;">123456</strong> ou seu próprio
                            nome.</p>
                    </div>
                </div>

                <!-- Progresso de etapas -->
                <div class="step-progress">
                    <div class="sp-step">
                        <div class="sp-dot done"><i class="bi bi-check-lg"></i></div>
                        <span>Solicitou</span>
                    </div>
                    <div class="sp-line"></div>
                    <div class="sp-step">
                        <div class="sp-dot done"><i class="bi bi-check-lg"></i></div>
                        <span>E-mail recebido</span>
                    </div>
                    <div class="sp-line"></div>
                    <div class="sp-step">
                        <div class="sp-dot current"><i class="bi bi-pencil-fill"></i></div>
                        <span style="color:#6eefb0;font-weight:600;">Nova senha</span>
                    </div>
                </div>
            </div>

            <!-- Rodapé -->
            <div class="panel-footer">
                © {{ $currentYear }} EM Dr. Leandro Franceschini · Todos os direitos reservados
            </div>
        </aside>

        <!-- ═══════════════════ PAINEL DIREITO ════════════════════════ -->
        <main class="panel-right">
            <div class="form-card">

                <!-- ─── VIEW 1: Formulário ─────────────────────────── -->
                <div class="view" id="viewForm">

                    <div class="form-top">
                        <a href="{{ route('login') }}" class="back-link">
                            <i class="bi bi-arrow-left"></i> Voltar ao login
                        </a>
                        <div class="form-badge">
                            <i class="bi bi-shield-lock-fill"></i> Redefinir Senha
                        </div>
                        <h1>Crie sua<br>nova senha</h1>
                        <p>Para redefinir sua senha, preencha o formulário abaixo com atenção às regras.</p>
                    </div>

                    <form method="POST" action="{{ route('reset.password.action') }}" id="resetForm">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}" />
                        <div id="serverErrors"></div>

                        <!-- Toggle mostrar/ocultar senhas -->
                        <div style="display:flex;justify-content:flex-end;margin-bottom:1.2rem;">
                            <button class="toggle-pwd-btn" id="toggleAllBtn" onclick="toggleAllPwd()" type="button">
                                <i class="bi bi-eye" id="toggleAllIcon"></i>
                                <span id="toggleAllLabel">Mostrar senhas</span>
                            </button>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:1.1rem;">

                            <!-- Nova Senha -->
                            <div>
                                <div class="field-label">
                                    Nova Senha <span class="req">*</span>
                                </div>
                                <div class="field-wrap">
                                    <input type="password" id="newPwd" name="password"
                                        class="form-input pwd-input @error('password') input-error @enderror"
                                        placeholder="Crie sua nova senha" style="padding-right:2.8rem;"
                                        oninput="onPwdInput()" />
                                    <i class="bi bi-lock-fill field-icon"></i>
                                    <button class="eye-btn" type="button" onclick="toggleEye('newPwd','eye1')"
                                        aria-label="Mostrar senha">
                                        <i class="bi bi-eye-fill" id="eye1"></i>
                                    </button>
                                </div>
                                <!-- Barra de força -->
                                <div class="strength-wrap">
                                    <div class="strength-track">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <span class="strength-label" id="strengthLabel"></span>
                                </div>
                            </div>

                            <!-- Repetir Senha -->
                            <div>
                                <div class="field-label">
                                    Repetir Senha <span class="req">*</span>
                                </div>
                                <div class="field-wrap">
                                    <input type="password" id="confirmPwd" name="password_confirmation"
                                        class="form-input pwd-input @error('password_confirmation') input-error @enderror"
                                        placeholder="Repita a nova senha" style="padding-right:2.8rem;"
                                        oninput="validateConfirm()" />
                                    <i class="bi bi-shield-lock-fill field-icon"></i>
                                    <button class="eye-btn" type="button" onclick="toggleEye('confirmPwd','eye2')"
                                        aria-label="Mostrar senha">
                                        <i class="bi bi-eye-fill" id="eye2"></i>
                                    </button>
                                </div>
                                <div class="field-msg" id="msgConfirm"></div>
                            </div>

                            <!-- Regras + Checklist -->
                            <div class="rules-box">
                                <p class="rules-note">
                                    <strong style="color:var(--navy);">ATENÇÃO:</strong> Sua senha deve ter no
                                    <strong>mínimo 6</strong> e no <strong>máximo 8</strong> caracteres, incluindo
                                    <strong>pelo menos</strong> uma letra maiúscula, uma minúscula
                                    <strong>e</strong> um número.
                                </p>
                                <div class="rules-grid">
                                    <div class="rule-chip" id="rule-len">
                                        <div class="ri" id="ri-len"><i class="bi bi-dash"></i></div>
                                        6 a 8 caracteres
                                    </div>
                                    <div class="rule-chip" id="rule-upper">
                                        <div class="ri" id="ri-upper"><i class="bi bi-dash"></i></div>
                                        Letra maiúscula
                                    </div>
                                    <div class="rule-chip" id="rule-lower">
                                        <div class="ri" id="ri-lower"><i class="bi bi-dash"></i></div>
                                        Letra minúscula
                                    </div>
                                    <div class="rule-chip" id="rule-num">
                                        <div class="ri" id="ri-num"><i class="bi bi-dash"></i></div>
                                        Número
                                    </div>
                                </div>
                            </div>

                            <!-- Botão -->
                            <button class="btn-reset" id="btnSubmit" type="submit" disabled>
                                <i class="bi bi-save-fill"></i> Redefinir Senha
                            </button>

                            <!-- Link -->
                            <div class="form-links">
                                <a href="{{ route('login') }}" class="link-back">
                                    <i class="bi bi-arrow-left"></i> Lembrei minha senha
                                </a>
                            </div>

                        </div>
                    </form>
                </div><!-- /viewForm -->

                <!-- ─── VIEW 2: Sucesso ────────────────────────────── -->
                {{-- <div class="view @unless (session('success')) hidden @endunless" id="viewSuccess"> --}}<div class="view hidden" id="viewSuccess">
                    <div class="success-card">
                        <div class="success-ring">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <h2>Senha redefinida!</h2>
                        <p>
                            Sua nova senha foi salva com sucesso. Agora você já pode acessar sua Área do Candidato.
                        </p>
                        <a href="{{ route('login') }}" class="btn-go-login">
                            <i class="bi bi-box-arrow-in-right"></i> Ir para o Login
                        </a>
                    </div>
                </div><!-- /viewSuccess -->

            </div><!-- /form-card -->
        </main>

    </div><!-- /page-wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Estado ─────────────────────────────────────────────────
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

            let score = [rules.len(v), rules.upper(v), rules.lower(v), rules.num(v)]
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

            const pwdOk = rules.len(pwd) && rules.upper(pwd) && rules.lower(pwd) && rules.num(pwd);
            const confirmOk = pwd === conf && conf.length > 0;

            btn.disabled = !(pwdOk && confirmOk);
            btn.style.cursor = btn.disabled ? 'not-allowed' : 'pointer';
        }

        document.getElementById('resetForm').addEventListener('submit', async function(e) {

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
    </script>
</body>

</html>
