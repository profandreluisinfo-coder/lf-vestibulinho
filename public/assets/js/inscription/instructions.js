document.addEventListener('DOMContentLoaded', function () {
    const STORAGE_KEY = 'inscription_instructions_confirmed';
    const checkbox = document.getElementById('confirmRead');
    const btn = document.getElementById('startBtn');
    const confirmedOnServer = @json(session('instructions_confirmed', false));

    function setEnabled(enabled) {
        btn.classList.toggle('disabled', !enabled);
        if (enabled) {
            btn.removeAttribute('aria-disabled');
            btn.removeAttribute('tabindex');
        } else {
            btn.setAttribute('aria-disabled', 'true');
            btn.setAttribute('tabindex', '-1');
        }
    }

    // Prioriza o valor do servidor; sessionStorage é só um cache local
    let saved = confirmedOnServer;
    try {
        if (!saved) {
            saved = sessionStorage.getItem(STORAGE_KEY) === 'true';
        }
    } catch (e) {
        // sessionStorage indisponível — segue com o valor do servidor
    }

    checkbox.checked = saved;
    setEnabled(saved);

    checkbox.addEventListener('change', function () {
        setEnabled(this.checked);

        try {
            sessionStorage.setItem(STORAGE_KEY, this.checked);
        } catch (e) {
            // não crítico
        }

        fetch("{{ route('inscription.confirm-instructions') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                confirmed: this.checked
            }),
        }).catch(() => {
            // Falha de rede — o middleware ainda vai bloquear no acesso à rota
        });
    });

    btn.addEventListener('click', function (e) {
        if (btn.classList.contains('disabled')) {
            e.preventDefault();
        }
    });
});