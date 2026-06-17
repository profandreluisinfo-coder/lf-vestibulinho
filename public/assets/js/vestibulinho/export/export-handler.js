function handleExport(event, url) {
    event.preventDefault();

    const link = event.target;
    const originalText = link.textContent;

    link.textContent = 'Aguarde...';
    link.style.pointerEvents = 'none';

    // Redireciona direto (forma correta para download)
    window.location.href = url;

    setTimeout(() => {
        link.textContent = originalText;
        link.style.pointerEvents = 'auto';
    }, 2000);
}