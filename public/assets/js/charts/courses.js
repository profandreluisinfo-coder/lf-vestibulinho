document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById('chartCursos');
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels);
    const values = JSON.parse(el.dataset.values);

    // Define as cores
    const colorMap = {
        'ADMINISTRAÇÃO': '#007BFF',   // preto
        'CONTABILIDADE': '#FF0000',   // vermelho
        'INFORMÁTICA': '#000000',     // azul
        'SEGURANÇA DO TRABALHO': '#008000' // verde
    };

    const backgroundColors = labels.map(label => colorMap[label] || '#888888');

    // Total geral
    const totalGeral = values.reduce((a, b) => a + b, 0);
    labels.push('TOTAL');
    values.push(totalGeral);
    backgroundColors.push('#AAAAAA');

    new Chart(el, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Inscritos',
                data: values,
                backgroundColor: backgroundColors,
                borderColor: '#333',
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Inscrições por Curso (com total geral)'
                },
                legend: { display: false },
                tooltip: { enabled: true },
                datalabels: {
                    color: '#000', // cor do texto
                    anchor: 'end', // posição no topo
                    align: 'end',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: (value) => value.toLocaleString('pt-BR')
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        },
        plugins: [ChartDataLabels] // 👉 ativa o plugin
    });
});
