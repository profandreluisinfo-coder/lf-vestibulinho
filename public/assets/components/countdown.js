function updateCountdown() {
    const countdownElement = document.getElementById('countdown');
    const deadline = new Date(countdownElement.dataset.deadline).getTime();
    const now = new Date().getTime();
    const timeLeft = deadline - now;

    if (timeLeft > 0) {
        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        document.getElementById('dias').textContent = days.toString().padStart(2, '0');
        document.getElementById('horas').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutos').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('segundos').textContent = seconds.toString().padStart(2, '0');

        const alertElement = document.getElementById('countdown-alert');

        if (days === 0 && hours < 24) {
            alertElement.classList.remove('d-none');
            countdownElement.classList.add('urgent-pulse');
        } else {
            alertElement.classList.add('d-none');
            countdownElement.classList.remove('urgent-pulse');
        }
    } else {
        document.getElementById('dias').textContent = '00';
        document.getElementById('horas').textContent = '00';
        document.getElementById('minutos').textContent = '00';
        document.getElementById('segundos').textContent = '00';

        const container = document.querySelector('.countdown-container');
        container.innerHTML = `
            <div class="text-center">
                <i class="bi bi-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                <h3 class="text-danger mt-3">Prazo Encerrado!</h3>
                <p class="lead">O período de inscrições foi finalizado.</p>
            </div>
        `;
    }
}

updateCountdown();
setInterval(updateCountdown, 1000);