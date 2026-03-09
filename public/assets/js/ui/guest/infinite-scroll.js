// Script de Infinite Scroll para FAQs dentro do Modal
(function () {
    'use strict';

    // Configurações
    const ITEMS_PER_PAGE = 10;

    let currentPage = 1;
    let isLoading = false;
    let hasMoreItems = true;
    let allFaqs = [];
    let filteredFaqs = [];
    let initialized = false;

    // Elementos DOM
    const modal = document.getElementById('staticBackdrop');
    const modalBody = document.querySelector('#staticBackdrop .modal-body');
    const accordion = document.getElementById('faqAccordion');
    const searchInput = document.getElementById('search');

    if (!modal || !modalBody || !accordion || !searchInput) return;

    // Função para criar elemento de loading
    function createLoadingElement() {
        const loading = document.createElement('div');
        loading.id = 'loading-indicator';
        loading.className = 'text-center py-4';
        loading.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2 text-muted">Carregando mais perguntas...</p>
        `;
        return loading;
    }

    // Função para criar elemento de "fim dos resultados"
    function createEndElement() {
        const end = document.createElement('div');
        end.id = 'end-indicator';
        end.className = 'text-center py-4 text-muted';
        end.innerHTML =
            '<div class="alert alert-success"><i class="bi bi-info-circle me-2"></i>Você visualizou todas as perguntas disponíveis. Caso ainda tenha  duvidas, entre em contato conosco.</div>';
        return end;
    }

    // Captura todos os FAQs existentes no carregamento inicial
    function captureInitialFaqs() {
        const items = accordion.querySelectorAll('.accordion-item');

        items.forEach((item, index) => {
            allFaqs.push({
                element: item.cloneNode(true),
                question: item.querySelector('.accordion-button').textContent.trim().toLowerCase(),
                index: index
            });
        });

        filteredFaqs = [...allFaqs];
    }

    // Inicializa mostrando apenas os primeiros itens
    function initializeView() {
        accordion.innerHTML = '';

        const itemsToShow = filteredFaqs.slice(0, ITEMS_PER_PAGE);

        itemsToShow.forEach(faq => {
            accordion.appendChild(faq.element.cloneNode(true));
        });

        hasMoreItems = filteredFaqs.length > ITEMS_PER_PAGE;
    }

    // Carrega mais itens
    function loadMoreItems() {

        if (isLoading || !hasMoreItems) return;

        isLoading = true;

        const loadingEl = createLoadingElement();
        accordion.parentElement.appendChild(loadingEl);

        setTimeout(() => {

            currentPage++;

            const start = (currentPage - 1) * ITEMS_PER_PAGE;
            const end = start + ITEMS_PER_PAGE;

            const itemsToShow = filteredFaqs.slice(start, end);

            loadingEl.remove();

            itemsToShow.forEach(faq => {
                accordion.appendChild(faq.element.cloneNode(true));
            });

            hasMoreItems = end < filteredFaqs.length;

            if (!hasMoreItems && filteredFaqs.length > 0) {
                const endEl = createEndElement();
                accordion.parentElement.appendChild(endEl);
            }

            isLoading = false;

        }, 300);
    }

    // Detecta scroll dentro do modal
    function handleScroll() {

        const scrollPosition = modalBody.scrollTop + modalBody.clientHeight;
        const pageHeight = modalBody.scrollHeight;
        const threshold = 150;

        if (scrollPosition >= pageHeight - threshold) {
            loadMoreItems();
        }
    }

    // Função de busca
    function handleSearch() {

        const searchTerm = searchInput.value.toLowerCase().trim();

        document.getElementById('loading-indicator')?.remove();
        document.getElementById('end-indicator')?.remove();

        if (searchTerm === '') {

            filteredFaqs = [...allFaqs];

        } else {

            filteredFaqs = allFaqs.filter(faq =>
                faq.question.includes(searchTerm)
            );
        }

        currentPage = 1;
        hasMoreItems = true;

        initializeView();

        // volta scroll para o topo do modal
        modalBody.scrollTop = 0;

        if (filteredFaqs.length === 0) {

            accordion.innerHTML = `
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Nenhuma pergunta encontrada para "${searchInput.value}".
                </div>
            `;
        }
    }

    // Debounce
    function debounce(func, wait) {

        let timeout;

        return function (...args) {

            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };

            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Inicialização
    function init() {

        if (initialized) return;
        initialized = true;

        captureInitialFaqs();
        initializeView();

        modalBody.addEventListener('scroll', debounce(handleScroll, 100));
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }

    // Inicia quando o modal abrir
    modal.addEventListener('shown.bs.modal', function () {
        init();
    });

})();