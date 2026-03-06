// Script de Infinite Scroll para FAQs
(function() {
    'use strict';

    // Configurações
    const ITEMS_PER_PAGE = 10;
    let currentPage = 1;
    let isLoading = false;
    let hasMoreItems = true;
    let allFaqs = [];
    let filteredFaqs = [];

    // Elementos DOM
    const accordion = document.getElementById('faqAccordion');
    const searchInput = document.getElementById('search');

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
        end.innerHTML = '<p class="text-success">Você visualizou todas as perguntas disponíveis.</p>';
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
        
        // Adiciona indicador de loading
        const loadingEl = createLoadingElement();
        accordion.parentElement.appendChild(loadingEl);

        // Simula delay de carregamento (remova em produção se não necessário)
        setTimeout(() => {
            currentPage++;
            const start = (currentPage - 1) * ITEMS_PER_PAGE;
            const end = start + ITEMS_PER_PAGE;
            const itemsToShow = filteredFaqs.slice(start, end);

            // Remove loading
            loadingEl.remove();

            // Adiciona novos itens
            itemsToShow.forEach(faq => {
                accordion.appendChild(faq.element.cloneNode(true));
            });

            // Verifica se há mais itens
            hasMoreItems = end < filteredFaqs.length;
            
            // Se não houver mais itens, mostra mensagem
            if (!hasMoreItems && filteredFaqs.length > 0) {
                const endEl = createEndElement();
                accordion.parentElement.appendChild(endEl);
            }

            isLoading = false;
        }, 300);
    }

    // Detecta quando usuário chega perto do final da página
    function handleScroll() {
        const scrollPosition = window.innerHeight + window.scrollY;
        const pageHeight = document.documentElement.scrollHeight;
        const threshold = 300; // pixels antes do fim

        if (scrollPosition >= pageHeight - threshold) {
            loadMoreItems();
        }
    }

    // Função de busca/filtro
    function handleSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        // Remove indicadores existentes
        document.getElementById('loading-indicator')?.remove();
        document.getElementById('end-indicator')?.remove();

        // Filtra FAQs
        if (searchTerm === '') {
            filteredFaqs = [...allFaqs];
        } else {
            filteredFaqs = allFaqs.filter(faq => 
                faq.question.includes(searchTerm)
            );
        }

        // Reseta paginação
        currentPage = 1;
        hasMoreItems = true;

        // Reinicializa view
        initializeView();

        // Mostra mensagem se não houver resultados
        if (filteredFaqs.length === 0) {
            accordion.innerHTML = `
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Nenhuma pergunta encontrada para "${searchInput.value}".
                </div>
            `;
        }
    }

    // Debounce para otimizar a busca
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
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
        captureInitialFaqs();
        initializeView();

        // Event listeners
        window.addEventListener('scroll', debounce(handleScroll, 100));
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }

    // Inicia quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();