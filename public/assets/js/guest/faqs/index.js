/* ═══════════════════════════════════════════════════════════════
   CONFIGURAÇÃO DAS CATEGORIAS
   ═══════════════════════════════════════════════════════════════ */
// Deriva as categorias únicas a partir dos dados,
// mantendo a ordem de aparição e aplicando as configs visuais
const CAT_CONFIG = {
    _default: {
        label: 'Outros',
        icon: 'bi-tag-fill',
        color: '#6C757D',
        bg: 'rgba(108,117,125,.12)'
    },
    geral: {
        label: 'Geral',
        icon: 'bi-question-circle-fill',
        color: '#E07A3A',
        bg: 'rgba(224,122,58,.12)'
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
    cursos: {
        label: 'Cursos',
        icon: 'bi-mortarboard-fill',
        color: '#1B3E72',
        bg: 'rgba(27,62,114,.1)'
    },
    resultado: {
        label: 'Resultado',
        icon: 'bi-trophy-fill',
        color: '#E07A3A',
        bg: 'rgba(224,122,58,.12)'
    },
    matricula: {
        label: 'Matrícula',
        icon: 'bi-journal-check',
        color: '#007F72',
        bg: 'rgba(0,127,114,.1)'
    },
};

function getCatConfig(cat) {
    return CAT_CONFIG[cat] ?? {
        ...CAT_CONFIG._default,
        label: cat.charAt(0).toUpperCase() + cat.slice(1)
    };
}

const CATEGORIES = [...new Set(FAQ_DATA.map(f => f.cat))]
    .filter(cat => cat)
    .map(cat => ({ id: cat, ...getCatConfig(cat) }));
    
/* ═══════════════════════════════════════════════════════════════
   ESTADO
   ═══════════════════════════════════════════════════════════════ */
const PAGE_SIZE = 8; // itens visíveis por "load more"
let currentCat = 'all';
let currentQ = '';
let visibleCount = PAGE_SIZE;

/* ═══════════════════════════════════════════════════════════════
   INIT
   ═══════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    buildSidebarNav();
    buildTicker();
    render();
    initScrollReveal();
    initNavbarScroll();
    document.getElementById('statTotal').textContent = FAQ_DATA.length;
    document.getElementById('statCategories').textContent = CATEGORIES.length;
});

/* ═══════════════════════════════════════════════════════════════
   SIDEBAR NAV
   ═══════════════════════════════════════════════════════════════ */
function buildSidebarNav() {
    const nav = document.getElementById('sidebarCatNav');
    nav.innerHTML = '';

    // "Todas"
    const allCount = FAQ_DATA.length;
    nav.innerHTML += `
            <div class="cat-nav-item active" data-cat="all" onclick="filterCatSidebar(this,'all')">
                <span><i class="bi bi-grid-3x3-gap-fill cat-icon"></i>Todas</span>
                <span class="cat-count">${allCount}</span>
            </div>`;

    CATEGORIES.forEach(cat => {
        const count = FAQ_DATA.filter(f => f.cat === cat.id).length;
        nav.innerHTML += `
                <div class="cat-nav-item" data-cat="${cat.id}" onclick="filterCatSidebar(this,'${cat.id}')">
                    <span><i class="bi ${cat.icon} cat-icon" style="color:${cat.color}"></i>${cat.label}</span>
                    <span class="cat-count">${count}</span>
                </div>`;
    });
}

/* ═══════════════════════════════════════════════════════════════
   TICKER INFINITO
   ═══════════════════════════════════════════════════════════════ */
function buildTicker() {
    const items = FAQ_DATA;
    const track = document.getElementById('tickerTrack');
    // duplica para loop infinito sem salto
    const html = [...items, ...items].map(f =>
        `<a href="#faq-${f.id}" class="ticker-item" onclick="scrollToFaq(${f.id})"><i class="bi bi-question-circle-fill"></i>${f.q}</a>`
    ).join('');
    track.innerHTML = html;
}

function scrollToFaq(faqId) {
    const element = document.getElementById(`faq-${faqId}`);
    if (element) {
        // scroll suave para o elemento
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // abre a pergunta
        setTimeout(() => {
            const question = element.querySelector('.faq-question');
            if (question && !element.classList.contains('open')) {
                toggleFaq(question);
            }
        }, 300);
    }
}

/* ═══════════════════════════════════════════════════════════════
   FILTRO POR CATEGORIA
   ═══════════════════════════════════════════════════════════════ */
function filterCat(btn, cat) {
    currentCat = cat;
    currentQ = '';
    visibleCount = PAGE_SIZE;
    document.getElementById('faqSearch').value = '';
    document.getElementById('searchBox').classList.remove('has-value');

    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    syncSidebarActive(cat);
    render();
}

function filterCatSidebar(btn, cat) {
    currentCat = cat;
    currentQ = '';
    visibleCount = PAGE_SIZE;
    document.getElementById('faqSearch').value = '';
    document.getElementById('searchBox').classList.remove('has-value');

    document.querySelectorAll('.cat-nav-item').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    syncPillActive(cat);
    render();
    // scroll suave para a seção de faqs
    document.querySelector('.faq-main').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
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
let searchTimer = null;

function handleSearch(input) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        currentQ = input.value.trim().toLowerCase();
        visibleCount = PAGE_SIZE;
        document.getElementById('searchBox').classList.toggle('has-value', currentQ.length > 0);
        // reset cat pills para "Todas" durante busca textual
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
    document.getElementById('faqSearch').value = '';
    document.getElementById('searchBox').classList.remove('has-value');
    render();
}

/* ═══════════════════════════════════════════════════════════════
   FILTRO + HIGHLIGHT
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

function highlight(text, q) {
    if (!q) return text;
    const clean = text.replace(/<[^>]+>/g, m => '\x00' + m + '\x00');
    const parts = clean.split('\x00');
    return parts.map(p => {
        if (p.startsWith('<')) return p; // tag HTML — preserva
        return p.replace(new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi'),
            '<span class="hl">$1</span>');
    }).join('');
}

/* ═══════════════════════════════════════════════════════════════
   RENDER
   ═══════════════════════════════════════════════════════════════ */
function render() {
    const filtered = getFiltered();
    const slice = filtered.slice(0, visibleCount);

    // empty state
    const empty = document.getElementById('emptyState');
    empty.classList.toggle('visible', filtered.length === 0);

    // search count
    const countEl = document.getElementById('searchCount');
    if (currentQ && filtered.length > 0) {
        countEl.innerHTML =
            `<strong>${filtered.length}</strong> pergunta${filtered.length > 1 ? 's' : ''} encontrada${filtered.length > 1 ? 's' : ''}`;
    } else if (currentQ && filtered.length === 0) {
        countEl.innerHTML = 'Nenhuma pergunta encontrada';
    } else {
        countEl.innerHTML = '';
    }

    // agrupa por categoria
    const grouped = {};
    CATEGORIES.forEach(c => {
        grouped[c.id] = [];
    });
    slice.forEach(f => {
        if (grouped[f.cat]) grouped[f.cat].push(f);
    });

    // renderiza seções
    const container = document.getElementById('faqSections');
    container.innerHTML = '';

    const catsToShow = currentCat === 'all' ?
        CATEGORIES.map(c => c.id) : [currentCat];

    catsToShow.forEach(catId => {
        const items = grouped[catId] || [];
        if (!items.length) return;
        const catInfo = CATEGORIES.find(c => c.id === catId);

        const section = document.createElement('div');
        section.className = 'faq-section reveal visible';
        section.id = `sect-${catId}`;

        section.innerHTML = `
                <div class="faq-section-header">
                    <div class="section-icon" style="background:${catInfo.bg};color:${catInfo.color};">
                        <i class="bi ${catInfo.icon}"></i>
                    </div>
                    <h3>${catInfo.label}</h3>
                    <span class="sect-count">${items.length} pergunta${items.length > 1 ? 's' : ''}</span>
                </div>
                ${items.map(f => buildFaqItem(f)).join('')}
            `;
        container.appendChild(section);
    });

    // load more
    const loadWrap = document.getElementById('loadMoreWrap');
    loadWrap.style.display = filtered.length > visibleCount ? 'block' : 'none';
    
    // Ajusta as alturas dos elementos de resposta
    setTimeout(adjustFaqAnswerHeights, 50);
}

function buildFaqItem(f) {
    const q = highlight(f.q, currentQ);
    const a = highlight(f.a, currentQ);
    return `
            <div class="faq-item" id="faq-${f.id}">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span class="question-text">${q}</span>
                    <div class="faq-icon"><i class="bi bi-plus-lg"></i></div>
                </div>
                <div class="faq-answer">${a}</div>
            </div>`;
}

/* Após renderizar, ajusta max-height dinamicamente */
function adjustFaqAnswerHeights() {
    document.querySelectorAll('.faq-answer').forEach(answer => {
        // Calcula a altura real do conteúdo
        answer.style.maxHeight = 'none';
        const fullHeight = answer.scrollHeight;
        answer.style.maxHeight = '';
        
        // Armazena a altura para usar quando abrir
        answer.dataset.fullHeight = fullHeight;
    });
}

/* ═══════════════════════════════════════════════════════════════
   TOGGLE FAQ
   ═══════════════════════════════════════════════════════════════ */
function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    const answer = item.querySelector('.faq-answer');
    
    // fecha todos
    document.querySelectorAll('.faq-item.open').forEach(el => {
        el.classList.remove('open');
        const ans = el.querySelector('.faq-answer');
        ans.style.maxHeight = '0px';
    });
    
    if (!isOpen) {
        item.classList.add('open');
        // Define a altura com base no conteúdo real
        answer.style.maxHeight = (answer.dataset.fullHeight || 300) + 'px';
    }
}

/* ═══════════════════════════════════════════════════════════════
   LOAD MORE
   ═══════════════════════════════════════════════════════════════ */
function loadMore() {
    const btn = document.getElementById('btnLoadMore');
    btn.classList.add('loading');
    setTimeout(() => {
        visibleCount += PAGE_SIZE;
        render();
        btn.classList.remove('loading');
    }, 600);
}

/* ═══════════════════════════════════════════════════════════════
   SCROLL REVEAL
   ═══════════════════════════════════════════════════════════════ */
function initScrollReveal() {
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                obs.unobserve(e.target);
            }
        });
    }, {
        threshold: .12
    });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
}

/* ═══════════════════════════════════════════════════════════════
   NAVBAR SCROLL
   ═══════════════════════════════════════════════════════════════ */
function initNavbarScroll() {
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    }, {
        passive: true
    });
}