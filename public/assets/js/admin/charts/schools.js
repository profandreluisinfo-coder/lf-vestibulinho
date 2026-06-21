document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById('chartEscolas');
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels);
    const values = JSON.parse(el.dataset.values);

    new Chart(el, {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Candidatos', data: values }] },
        options: {
            plugins: { title: { display: true, text: 'Escolas de Origem' } },
            scales: { y: { beginAtZero: true } }
        }
    });
});