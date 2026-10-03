
/* ═══════════════════════════════════════════════════════════════
   CONFIGURAÇÃO DAS CATEGORIAS
   ═══════════════════════════════════════════════════════════════ */

const CAT_CONFIG = {
    _default: {
        label: 'Outros',
        icon: 'bi-tag-fill',
        color: '#6C757D',
        bg: 'rgba(108,117,125,.12)'
    },
    inscricao: {
        label: 'Inscrição',
        icon: 'bi-pencil-fill',
        color: '#00A896',
        bg: 'rgba(0,168,150,.1)'
    },
    prova: {
        label: 'Prova',
        icon: 'bi-file-earmark-text-fill',
        color: '#F4A261',
        bg: 'rgba(244,162,97,.12)'
    },
    resultado: {
        label: 'Resultado',
        icon: 'bi-trophy-fill',
        color: '#E07A3A',
        bg: 'rgba(224,122,58,.12)'
    },
    convocacao: {
        label: 'Convocação',
        icon: 'bi-megaphone-fill',
        color: '#F4A261',
        bg: 'rgba(244,162,97,.12)'
    },
    geral: {
        label: 'Geral',
        icon: 'bi-question-circle-fill',
        color: '#E07A3A',
        bg: 'rgba(224,122,58,.12)'
    },
    matricula: {
        label: 'Matrícula',
        icon: 'bi-journal-check',
        color: '#007F72',
        bg: 'rgba(0,127,114,.1)'
    },
    escola: {
        label: 'Escola',
        icon: 'bi-building-fill',
        color: '#1B3E72',
        bg: 'rgba(27,62,114,.1)'
    },
    cursos: {
        label: 'Cursos',
        icon: 'bi-mortarboard-fill',
        color: '#1B3E72',
        bg: 'rgba(27,62,114,.1)'
    }
};

function getCatConfig(cat) {
    const hasConfig =
        typeof cat === 'string' &&
        Object.prototype.hasOwnProperty.call(CAT_CONFIG, cat) &&
        cat !== '_default';

    if (hasConfig) {
        return CAT_CONFIG[cat];
    }

    const label = typeof cat === 'string' && cat.length
        ? cat.charAt(0).toUpperCase() + cat.slice(1)
        : CAT_CONFIG._default.label;

    return {
        ...CAT_CONFIG._default,
        label
    };
}

const CAT_ORDER = [
    'geral',
    'inscricao',
    'prova',
    'resultado',
    'convocacao',
    'matricula',
    'escola'
    // 'cursos'
];

const CATEGORIES = [
    ...new Set(
        FAQ_DATA
            .map(f => f.cat)
            .filter(cat => typeof cat === 'string' && cat.length > 0)
    )
]
    .map(cat => ({
        id: cat,
        ...getCatConfig(cat)
    }))
    .sort((a, b) => {
        const ia = CAT_ORDER.indexOf(a.id);
        const ib = CAT_ORDER.indexOf(b.id);

        if (ia === -1 && ib === -1) {
            return a.label.localeCompare(b.label, 'pt-BR');
        }

        if (ia === -1) return 1;
        if (ib === -1) return -1;

        return ia - ib;
    });


/* ═══════════════════════════════════════════════════════════════
   ESTADO
   ═══════════════════════════════════════════════════════════════ */

const PAGE_SIZE = 8;

let currentCat = 'all';
let currentQ = '';
let visibleCount = PAGE_SIZE;
let searchTimer = null;


/* ═══════════════════════════════════════════════════════════════
   INIT
   ═══════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {
    buildSidebarNav();
    buildTicker();
    render();
    initScrollReveal();
    initNavbarScroll();

    const statTotal = document.getElementById('statTotal');
    const statCategories = document.getElementById('statCategories');

    if (statTotal) {
        statTotal.textContent = FAQ_DATA.length;
    }

    if (statCategories) {
        statCategories.textContent = CATEGORIES.length;
    }
});


/* ═══════════════════════════════════════════════════════════════
   SIDEBAR NAV
   Cria os itens pelo DOM, sem interpolar dados em HTML.
   ═══════════════════════════════════════════════════════════════ */

function buildSidebarNav() {
    const nav = document.getElementById('sidebarCatNav');

    if (!nav) return;

    nav.replaceChildren();

    function createNavItem({
        id,
        label,
        icon,
        color
    }, count, active = false) {
        const item = document.createElement('div');

        item.className = 'cat-nav-item';
        item.dataset.cat = id;
        item.classList.toggle('active', active);
        item.setAttribute('role', 'button');
        item.setAttribute('tabindex', '0');

        const content = document.createElement('span');
        const iconElement = document.createElement('i');

        iconElement.classList.add('bi', 'cat-icon');

        // As classes de ícone e as cores vêm de configurações
        // definidas pela aplicação, nunca diretamente do banco.
        const iconConfig = icon || CAT_CONFIG._default.icon;

        iconElement.classList.add(
            ...iconConfig.split(/\s+/).filter(Boolean)
        );

        iconElement.style.color = color || CAT_CONFIG._default.color;

        content.append(
            iconElement,
            document.createTextNode(label)
        );

        const countElement = document.createElement('span');

        countElement.className = 'cat-count';
        countElement.textContent = String(count);

        item.append(content, countElement);

        const activate = () => {
            filterCatSidebar(item, id);
        };

        item.addEventListener('click', activate);

        item.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                activate();
            }
        });

        return item;
    }

    // Categoria "Todas": ícone definido pela aplicação.
    nav.appendChild(
        createNavItem(
            {
                id: 'all',
                label: 'Todas',
                icon: 'bi-grid-3x3-gap-fill',
                color: CAT_CONFIG._default.color
            },
            FAQ_DATA.length,
            currentCat === 'all'
        )
    );

    CATEGORIES.forEach(cat => {
        const count = FAQ_DATA.filter(
            f => f.cat === cat.id
        ).length;

        nav.appendChild(
            createNavItem(
                cat,
                count,
                currentCat === cat.id
            )
        );
    });
}


/* ═══════════════════════════════════════════════════════════════
   TICKER INFINITO
   ═══════════════════════════════════════════════════════════════ */

function buildTicker() {
    const track = document.getElementById('tickerTrack');

    if (!track) return;

    track.replaceChildren();

    [...FAQ_DATA, ...FAQ_DATA].forEach(f => {
        const link = document.createElement('a');

        link.href = `#faq-${Number(f.id)}`;
        link.className = 'ticker-item';

        link.addEventListener('click', event => {
            event.preventDefault();
            scrollToFaq(Number(f.id));
        });

        const icon = document.createElement('i');

        icon.className = 'bi bi-question-circle-fill';

        link.append(
            icon,
            document.createTextNode(f.q ?? '')
        );

        track.appendChild(link);
    });
}

function scrollToFaq(faqId) {
    const element = document.getElementById(`faq-${faqId}`);

    if (!element) return;

    element.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });

    setTimeout(() => {
        const question = element.querySelector('.faq-question');

        if (question && !element.classList.contains('open')) {
            toggleFaq(question);
        }
    }, 300);
}


/* ═══════════════════════════════════════════════════════════════
   FILTRO POR CATEGORIA
   ═══════════════════════════════════════════════════════════════ */

function filterCat(btn, cat) {
    currentCat = cat;
    currentQ = '';
    visibleCount = PAGE_SIZE;

    const searchInput = document.getElementById('faqSearch');
    const searchBox = document.getElementById('searchBox');

    if (searchInput) searchInput.value = '';
    if (searchBox) searchBox.classList.remove('has-value');

    document.querySelectorAll('.cat-pill').forEach(p => {
        p.classList.remove('active');
    });

    if (btn) btn.classList.add('active');

    syncSidebarActive(cat);
    render();
}

function filterCatSidebar(btn, cat) {
    currentCat = cat;
    currentQ = '';
    visibleCount = PAGE_SIZE;

    const searchInput = document.getElementById('faqSearch');
    const searchBox = document.getElementById('searchBox');

    if (searchInput) searchInput.value = '';
    if (searchBox) searchBox.classList.remove('has-value');

    document.querySelectorAll('.cat-nav-item').forEach(p => {
        p.classList.remove('active');
    });

    if (btn) btn.classList.add('active');

    syncPillActive(cat);
    render();

    const main = document.querySelector('.faq-main');

    if (main) {
        main.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

function syncSidebarActive(cat) {
    document.querySelectorAll('.cat-nav-item').forEach(p => {
        p.classList.toggle('active', p.dataset.cat === cat);
    });
}

function syncPillActive(cat) {
    document.querySelectorAll('.cat-pill').forEach(p => {
        p.classList.toggle('active', p.dataset.cat === cat);
    });
}


/* ═══════════════════════════════════════════════════════════════
   BUSCA
   ═══════════════════════════════════════════════════════════════ */

function handleSearch(input) {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        currentQ = input.value.trim().toLowerCase();
        visibleCount = PAGE_SIZE;

        const searchBox = document.getElementById('searchBox');

        if (searchBox) {
            searchBox.classList.toggle(
                'has-value',
                currentQ.length > 0
            );
        }

        // Durante a busca textual, remove o filtro de categoria.
        if (currentQ) {
            currentCat = 'all';
            syncPillActive('all');
            syncSidebarActive('all');
        }

        render();
    }, 200);
}

function clearSearch() {
    currentQ = '';
    visibleCount = PAGE_SIZE;

    const searchInput = document.getElementById('faqSearch');
    const searchBox = document.getElementById('searchBox');

    if (searchInput) searchInput.value = '';
    if (searchBox) searchBox.classList.remove('has-value');

    render();
}


/* ═══════════════════════════════════════════════════════════════
   FILTRO
   Mantida a lógica original de getFiltered(), conforme solicitado.
   ═══════════════════════════════════════════════════════════════ */

function getFiltered() {
    return FAQ_DATA.filter(f => {
        const catOk = currentCat === 'all' || f.cat === currentCat;
        const qOk = !currentQ ||
            f.q.toLowerCase().includes(currentQ) ||
            f.a.replace(/<[^>]+>/g, '').toLowerCase().includes(currentQ);

        return catOk && qOk;
    });
}


/* ═══════════════════════════════════════════════════════════════
   DESTAQUE DA BUSCA
   Trabalha com nós de texto, sem reconstruir o HTML da resposta.
   ═══════════════════════════════════════════════════════════════ */

function highlightAnswer(container, query) {
    if (!container || !query) return;

    // Escapa caracteres especiais para a consulta ser literal.
    const escapedQuery = query.replace(
        /[.*+?^${}()|[\]\\]/g,
        '\\$&'
    );

    const regex = new RegExp(escapedQuery, 'gi');

    // Recolhe os nós antes de modificá-los.
    const walker = document.createTreeWalker(
        container,
        NodeFilter.SHOW_TEXT
    );

    const textNodes = [];
    let node;

    while ((node = walker.nextNode())) {
        if (node.nodeValue && regex.test(node.nodeValue)) {
            textNodes.push(node);
        }

        // Reinicia lastIndex porque a expressão usa a flag global.
        regex.lastIndex = 0;
    }

    textNodes.forEach(textNode => {
        const text = textNode.nodeValue;
        const fragment = document.createDocumentFragment();

        let lastIndex = 0;
        let match;

        regex.lastIndex = 0;

        while ((match = regex.exec(text)) !== null) {
            if (match.index > lastIndex) {
                fragment.appendChild(
                    document.createTextNode(
                        text.slice(lastIndex, match.index)
                    )
                );
            }

            const mark = document.createElement('span');

            mark.className = 'hl';
            mark.textContent = match[0];

            fragment.appendChild(mark);

            lastIndex = match.index + match[0].length;

            // Evita um loop infinito em correspondências vazias.
            if (match[0].length === 0) {
                regex.lastIndex++;
            }
        }

        if (lastIndex < text.length) {
            fragment.appendChild(
                document.createTextNode(text.slice(lastIndex))
            );
        }

        textNode.parentNode.replaceChild(fragment, textNode);
    });
}


/* ═══════════════════════════════════════════════════════════════
   RENDER
   ═══════════════════════════════════════════════════════════════ */

function render() {
    const filtered = getFiltered();
    const slice = filtered.slice(0, visibleCount);

    // Estado vazio.
    const empty = document.getElementById('emptyState');

    if (empty) {
        empty.classList.toggle('visible', filtered.length === 0);
    }

    // Contagem dos resultados.
    const countEl = document.getElementById('searchCount');

    if (countEl) {
        if (currentQ && filtered.length > 0) {
            const total = document.createElement('strong');
            total.textContent = String(filtered.length);

            countEl.replaceChildren(
                total,
                document.createTextNode(
                    ` pergunta${filtered.length > 1 ? 's' : ''}` +
                    ` encontrada${filtered.length > 1 ? 's' : ''}`
                )
            );
        } else if (currentQ && filtered.length === 0) {
            countEl.textContent = 'Nenhuma pergunta encontrada';
        } else {
            countEl.replaceChildren();
        }
    }

    // Agrupa os resultados por categoria.
    const grouped = {};

    CATEGORIES.forEach(category => {
        grouped[category.id] = [];
    });

    slice.forEach(f => {
        if (grouped[f.cat]) {
            grouped[f.cat].push(f);
        }
    });

    // Renderiza as seções.
    const container = document.getElementById('faqSections');

    if (!container) return;

    container.replaceChildren();

    const catsToShow = currentCat === 'all'
        ? CATEGORIES.map(category => category.id)
        : [currentCat];

    catsToShow.forEach(catId => {
        const items = grouped[catId] || [];

        if (!items.length) return;

        const catInfo = CATEGORIES.find(
            category => category.id === catId
        );

        if (!catInfo) return;

        const section = document.createElement('div');

        section.className = 'faq-section reveal visible';
        section.id = `sect-${catId}`;

        const header = document.createElement('div');
        header.className = 'faq-section-header';

        const sectionIcon = document.createElement('div');

        sectionIcon.className = 'section-icon';
        sectionIcon.style.background = catInfo.bg;
        sectionIcon.style.color = catInfo.color;

        const icon = document.createElement('i');

        icon.classList.add(
            'bi',
            ...catInfo.icon.split(/\s+/).filter(Boolean)
        );

        sectionIcon.appendChild(icon);

        const title = document.createElement('h3');

        title.textContent = catInfo.label;

        const count = document.createElement('span');

        count.className = 'sect-count';
        count.textContent =
            `${items.length} pergunta${items.length > 1 ? 's' : ''}`;

        header.append(sectionIcon, title, count);
        section.appendChild(header);

        // Insere os elementos diretamente no DOM.
        items.forEach(f => {
            section.appendChild(buildFaqItem(f));
        });

        container.appendChild(section);
    });

    // Botão "Carregar mais".
    const loadWrap = document.getElementById('loadMoreWrap');

    if (loadWrap) {
        loadWrap.style.display =
            filtered.length > visibleCount ? 'block' : 'none';
    }

    // Ajusta as alturas das respostas após a renderização.
    setTimeout(adjustFaqAnswerHeights, 50);
}


/* ═══════════════════════════════════════════════════════════════
   CONSTRUÇÃO DO ITEM FAQ
   ═══════════════════════════════════════════════════════════════ */

function buildFaqItem(f) {
    const item = document.createElement('div');

    item.className = 'faq-item';
    item.id = `faq-${Number(f.id)}`;

    const question = document.createElement('div');

    question.className = 'faq-question';
    question.setAttribute('role', 'button');
    question.setAttribute('tabindex', '0');
    question.setAttribute('aria-expanded', 'false');

    const questionText = document.createElement('span');

    questionText.className = 'question-text';
    questionText.textContent = f.q ?? '';

    const icon = document.createElement('div');

    icon.className = 'faq-icon';

    const plusIcon = document.createElement('i');

    plusIcon.className = 'bi bi-plus-lg';

    icon.appendChild(plusIcon);
    question.append(questionText, icon);

    const answer = document.createElement('div');

    answer.className = 'faq-answer';

    /*
     * IMPORTANTE:
     * f.a precisa ter sido sanitizado no backend com HTML Purifier.
     * Não insira aqui HTML arbitrário vindo diretamente do banco.
     */
    answer.innerHTML = f.a ?? '';

    // Aplica o destaque depois de inserir o HTML sanitizado.
    highlightAnswer(answer, currentQ);

    answer.style.maxHeight = '0px';

    question.addEventListener('click', () => {
        toggleFaq(question);
    });

    question.addEventListener('keydown', event => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            toggleFaq(question);
        }
    });

    item.append(question, answer);

    return item;
}


/* ═══════════════════════════════════════════════════════════════
   AJUSTE DAS ALTURAS DAS RESPOSTAS
   ═══════════════════════════════════════════════════════════════ */

function adjustFaqAnswerHeights() {
    document.querySelectorAll('.faq-answer').forEach(answer => {
        answer.style.maxHeight = 'none';

        const fullHeight = answer.scrollHeight;

        answer.style.maxHeight = '';
        answer.dataset.fullHeight = String(fullHeight);
    });
}


/* ═══════════════════════════════════════════════════════════════
   TOGGLE FAQ
   ═══════════════════════════════════════════════════════════════ */

function toggleFaq(btn) {
    const item = btn.closest('.faq-item');

    if (!item) return;

    const isOpen = item.classList.contains('open');
    const answer = item.querySelector('.faq-answer');

    if (!answer) return;

    // Fecha as demais perguntas abertas.
    document.querySelectorAll('.faq-item.open').forEach(el => {
        el.classList.remove('open');

        const question = el.querySelector('.faq-question');
        const answerEl = el.querySelector('.faq-answer');

        if (question) {
            question.setAttribute('aria-expanded', 'false');
        }

        if (answerEl) {
            answerEl.style.maxHeight = '0px';
        }
    });

    // Se a pergunta clicada já estava aberta, ela fica fechada.
    if (isOpen) return;

    item.classList.add('open');
    btn.setAttribute('aria-expanded', 'true');

    answer.style.maxHeight =
        `${answer.dataset.fullHeight || answer.scrollHeight}px`;
}


/* ═══════════════════════════════════════════════════════════════
   LOAD MORE
   ═══════════════════════════════════════════════════════════════ */

function loadMore() {
    const btn = document.getElementById('btnLoadMore');

    if (btn) {
        btn.classList.add('loading');
    }

    setTimeout(() => {
        visibleCount += PAGE_SIZE;
        render();

        if (btn) {
            btn.classList.remove('loading');
        }
    }, 600);
}


/* ═══════════════════════════════════════════════════════════════
   SCROLL REVEAL
   ═══════════════════════════════════════════════════════════════ */

function initScrollReveal() {
    const obs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                obs.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12
    });

    document.querySelectorAll('.reveal').forEach(element => {
        obs.observe(element);
    });
}


/* ═══════════════════════════════════════════════════════════════
   NAVBAR SCROLL
   ═══════════════════════════════════════════════════════════════ */

function initNavbarScroll() {
    const nav = document.getElementById('mainNav');

    if (!nav) return;

    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    }, {
        passive: true
    });
}
