document.addEventListener('DOMContentLoaded', function() {
    const carouselSection = document.getElementById('provas-anteriores');
    if (!carouselSection) return;

    const cardsContainer = carouselSection.querySelector('.row');
    const cards = Array.from(cardsContainer.querySelectorAll('.col-6'));
    
    if (cards.length === 0) return;

    let currentIndex = 0;
    let intervalId = null;
    const autoPlayDelay = 3000; // 3 segundos
    const cardsToShow = getCardsToShow();

    // Determina quantos cards mostrar baseado no tamanho da tela
    function getCardsToShow() {
        const width = window.innerWidth;
        if (width >= 992) return 4; // lg
        if (width >= 768) return 3; // md
        return 2; // mobile
    }

    // Esconde todos os cards
    function hideAllCards() {
        cards.forEach(card => {
            card.style.display = 'none';
        });
    }

    // Mostra os cards visíveis
    function showVisibleCards() {
        hideAllCards();
        
        for (let i = 0; i < cardsToShow; i++) {
            const index = (currentIndex + i) % cards.length;
            cards[index].style.display = 'block';
            cards[index].style.animation = 'fadeIn 0.5s ease-in-out';
        }
    }

    // Avança para o próximo conjunto de cards
    function nextSlide() {
        currentIndex = (currentIndex + 1) % cards.length;
        showVisibleCards();
    }

    // Inicia o autoplay
    function startAutoPlay() {
        if (intervalId) return;
        intervalId = setInterval(nextSlide, autoPlayDelay);
    }

    // Para o autoplay
    function stopAutoPlay() {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    }

    // Adiciona selo "Nova" no primeiro card (mais recente)
    if (cards.length > 0) {
        const firstCard = cards[0].querySelector('.card');
        if (firstCard) {
            const badge = document.createElement('span');
            badge.className = 'badge bg-success position-absolute top-0 end-0 m-2';
            badge.style.cssText = 'font-size: 0.75rem; z-index: 10;';
            badge.innerHTML = '<i class="bi bi-award me-1"></i>Recente';
            firstCard.style.position = 'relative';
            firstCard.insertBefore(badge, firstCard.firstChild);
        }
    }

    // Adiciona CSS para a animação
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    `;
    document.head.appendChild(style);

    // Event listeners para pausar/retomar ao passar o mouse
    carouselSection.addEventListener('mouseenter', stopAutoPlay);
    carouselSection.addEventListener('mouseleave', startAutoPlay);

    // Reajusta o carrossel ao redimensionar a janela
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            const newCardsToShow = getCardsToShow();
            if (newCardsToShow !== cardsToShow) {
                location.reload(); // Recarrega para aplicar novo layout
            }
        }, 250);
    });

    // Inicializa o carrossel
    showVisibleCards();
    startAutoPlay();

    // Para o autoplay quando a página perde o foco
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoPlay();
        } else {
            startAutoPlay();
        }
    });
});