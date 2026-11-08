(() => {
    const input = document.getElementById('filter');
    const body = document.querySelector('#results-table tbody');
    const count = document.getElementById('results-count');
    const loading = document.getElementById('results-loading');
    const end = document.getElementById('results-end');
    const empty = document.getElementById('results-empty');
    const errorMessage = document.getElementById('results-error');
    const sentinel = document.getElementById('results-sentinel');
    const moreButton = document.getElementById('results-more');
    let offset = body.querySelectorAll('[data-inscription]').length;
    let hasMore = true;
    let busy = false;
    let search = '';
    let requestId = 0;

    function rowHtml(item) {
        const row = document.createElement('tr');
        [
            `${item.ranking}º`,
            String(item.inscription_id),
            String(item.score),
        ].forEach((value, index) => {
            const cell = document.createElement('td');
            cell.className = 'text-center';
            cell.textContent = value;
            if (index === 1) cell.dataset.inscription = '';
            row.appendChild(cell);
        });
        return row;
    }

    async function load(reset = false) {
        if ((busy && !reset) || (!hasMore && !reset)) return;
        busy = true;
        const currentRequest = ++requestId;
        if (reset) {
            body.replaceChildren();
            offset = 0;
            hasMore = true;
            end.hidden = true;
        }
        loading.hidden = false;
        moreButton.hidden = false;
        moreButton.disabled = true;
        moreButton.textContent = 'Carregando…';
        errorMessage.hidden = true;
        empty.hidden = true;
        try {
            const params = new URLSearchParams({ offset: String(offset), search });
            const response = await fetch(`${window.resultsMoreUrl}?${params}`, {
                headers: { Accept: 'application/json' },
                cache: 'no-store',
            });
            const contentType = response.headers.get('content-type') || '';
            if (!response.ok || !contentType.includes('application/json')) {
                throw new Error(`Falha ao carregar (HTTP ${response.status}). Tente novamente.`);
            }
            const result = await response.json();
            if (!Array.isArray(result.data) || typeof result.next_offset !== 'number') {
                throw new Error('A resposta do servidor veio em formato inesperado. Tente novamente.');
            }
            if (currentRequest !== requestId) return;
            result.data.forEach(item => body.appendChild(rowHtml(item)));
            offset = result.next_offset;
            hasMore = result.has_more;
            count.textContent = search
                ? `${offset} candidato(s) encontrado(s)${hasMore ? ' — continue rolando para ver mais' : ''}`
                : `Exibindo ${offset} candidato(s)`;
            empty.hidden = offset > 0;
            end.hidden = hasMore || offset === 0;
            moreButton.hidden = !hasMore;
        } catch (error) {
            if (currentRequest === requestId) {
                errorMessage.textContent = error.message;
                errorMessage.hidden = false;
            }
        } finally {
            if (currentRequest === requestId) {
                loading.hidden = true;
                busy = false;
                moreButton.disabled = false;
                moreButton.textContent = 'Carregar mais candidatos';
            }
        }
    }

    input.addEventListener('input', () => {
        window.clearTimeout(input.searchTimer);
        input.searchTimer = window.setTimeout(() => {
            search = input.value.trim();
            load(true);
        }, 250);
    });
    moreButton.addEventListener('click', () => load());
    new IntersectionObserver(entries => {
        if (entries.some(entry => entry.isIntersecting)) load();
    }, { rootMargin: '300px' }).observe(sentinel);

    if (offset === 0) load();
})();
