// Função para baixar o gráfico como imagem PNG
function baixarImagem(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return alert("Gráfico não encontrado!");

    const link = document.createElement("a");
    link.href = canvas.toDataURL("image/png");
    link.download = `${canvasId}.png`;
    link.click();
}

// Função para imprimir o gráfico
function imprimirGrafico(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return alert("Gráfico não encontrado!");

    const dataUrl = canvas.toDataURL("image/png");

    const janela = window.open("", "_blank");
    janela.document.write(`
        <html>
        <head>
            <title>Impressão do Gráfico</title>
            <style>
                body {
                    text-align: center;
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
                img {
                    max-width: 100%;
                    height: auto;
                    margin-top: 20px;
                }
                h3 {
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body>
            <h3>Relatório de Gráfico</h3>
            <img src="${dataUrl}" alt="Gráfico">
            <script>
                window.onload = () => { window.print(); }
            </script>
        </body>
        </html>
    `);
    janela.document.close();
}