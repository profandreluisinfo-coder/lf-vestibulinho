document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById('chartBairros');
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels);
    const values = JSON.parse(el.dataset.values);

    new Chart(el, {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Candidatos', data: values }] },
        options: {
            indexAxis: 'y',
            plugins: { title: { display: true, text: 'Candidatos por Bairro' } },
            scales: { x: { beginAtZero: true } }
        }
    });
});
