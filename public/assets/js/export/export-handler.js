function handleExport(event, url) {
    event.preventDefault();
    const link = event.target;
    const originalText = link.textContent;
    
    link.textContent = 'Aguarde...';
    link.style.pointerEvents = 'none';
    
    // Faz o download
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = downloadUrl;
            a.download = 'notas.xlsx'; // ou o nome do arquivo
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(downloadUrl);
            a.remove();
            
            // Volta ao normal
            link.textContent = originalText;
            link.style.pointerEvents = 'auto';
        })
        .catch(error => {
            console.error('Erro:', error);
            link.textContent = 'Erro ao exportar';
            setTimeout(() => {
                link.textContent = originalText;
                link.style.pointerEvents = 'auto';
            }, 2000);
        });
}