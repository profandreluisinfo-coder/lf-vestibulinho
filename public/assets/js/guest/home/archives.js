/* ── Scroll reveal ─────────────────────────────────────────── */
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
}, { threshold: .1 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

/* ── Navbar scroll ─────────────────────────────────────────── */
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 40), { passive: true });

/* ── Busca por ano ─────────────────────────────────────────── */
function filterArchives(q) {
    q = q.trim().toLowerCase();
    const items = document.querySelectorAll('.pa-item');
    const divider = document.querySelector('[data-divider]');
    const empty = document.getElementById('searchEmpty');
    let visible = 0;

    items.forEach(item => {
        const year = item.dataset.year ?? '';
        const show = !q || year.includes(q);
        item.classList.toggle('hidden-item', !show);
        if (show) visible++;
    });

    // esconde divider se filtrando
    if (divider) divider.style.display = q ? 'none' : '';

    // empty state
    if (empty) empty.style.display = visible === 0 ? 'flex' : 'none';

    // atualiza contador
    const countEl = document.getElementById('visibleCount');
    if (countEl) countEl.textContent = visible;
}

function clearSearch() {
    const input = document.getElementById('paSearch');
    if (input) { input.value = ''; filterArchives(''); }
}