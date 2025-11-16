document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('nav-link-out').addEventListener('click', (event) => {
        event.preventDefault();
        if (confirm('Tem certeza de que deseja encerrar sua sessão? Seus dados serão perdidos e sua inscrição não será efetivada.')) {
            window.location.href = endMySession;
        }
    });
});