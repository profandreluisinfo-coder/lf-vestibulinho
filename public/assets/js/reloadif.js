window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        // Se a página veio do cache do navegador
        window.location.reload();
    }
});