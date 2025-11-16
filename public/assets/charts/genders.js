document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById('chartSexos');
    if (!el) return;

    const rawLabels = JSON.parse(el.dataset.labels);
    const values = JSON.parse(el.dataset.values);

    // Mapeia os códigos numéricos para texto e cor
    const labelMap = {
        1: 'Masculino',
        2: 'Feminino',
        3: 'Outro',
        4: 'Prefiro não informar'
    };

    const colorMap = {
        1: '#007BFF', // Azul
        2: '#FF69B4', // Rosa
        3: '#8A2BE2', // Roxo
        4: '#A9A9A9'  // Cinza
    };

    // Converte os códigos em nomes e aplica as cores
    const labels = rawLabels.map(code => labelMap[code] || 'Desconhecido');
    const backgroundColors = rawLabels.map(code => colorMap[code] || '#CCC');

    // Calcula o total geral
    const totalGeral = values.reduce((a, b) => a + b, 0);
    labels.push('TOTAL');
    values.push(totalGeral);
    backgroundColors.push('#333');

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
                    text: 'Distribuição de Inscritos por Sexo'
                },
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.parsed.y} inscritos`
                    }
                },
                datalabels: {
                    color: '#000',
                    anchor: 'end',
                    align: 'end',
                    font: { weight: 'bold', size: 12 },
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
        plugins: [ChartDataLabels]
    });
});