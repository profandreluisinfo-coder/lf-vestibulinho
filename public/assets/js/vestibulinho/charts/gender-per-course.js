document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById('chartSexoPorCurso');
    if (!el) return;

    const cursos = JSON.parse(el.dataset.cursos);
    const masculino = JSON.parse(el.dataset.masculino);
    const feminino = JSON.parse(el.dataset.feminino);

    new Chart(el, {
        type: 'bar',
        data: {
            labels: cursos,
            datasets: [
                {
                    label: 'Masculino',
                    data: masculino,
                    backgroundColor: '#007BFF', // azul
                    borderColor: '#004A99',
                    borderWidth: 1
                },
                {
                    label: 'Feminino',
                    data: feminino,
                    backgroundColor: '#FF69B4', // rosa
                    borderColor: '#CC4E87',
                    borderWidth: 1
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Distribuição de Inscritos por Curso e Sexo (Masculino e Feminino)'
                },
                legend: {
                    position: 'top'
                },
                datalabels: {
                    color: '#000',
                    anchor: 'end',
                    align: 'end',
                    font: { weight: 'bold', size: 12 },
                    formatter: (value) => value.toLocaleString('pt-BR')
                },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y} inscritos`
                    }
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