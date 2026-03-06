// Elementos DOM
const filterStatus = document.getElementById('filter-status');
const searchInput = document.getElementById('search');
const resultsBody = document.getElementById('results-tbody');
const resultsCounter = document.getElementById('results-counter');

// Função para aplicar filtros
function applyFilters() {
    const statusFilter = filterStatus.value;
    const searchTerm = searchInput.value.toLowerCase().trim();
    const rows = resultsBody.querySelectorAll('tr');
    
    let visibleCount = 0;
    
    rows.forEach(row => {
        // Ignora linha de "nenhum resultado"
        if (row.querySelector('td[colspan]')) {
            row.style.display = 'none';
            return;
        }
        
        const rowStatus = row.getAttribute('data-status');
        const inscricao = row.cells[1]?.textContent.toLowerCase() || '';
        const nome = row.cells[2]?.textContent.toLowerCase() || '';
        
        // Verifica filtro de status
        const matchesStatus = statusFilter === 'all' || rowStatus === statusFilter;
        
        // Verifica filtro de busca
        const matchesSearch = searchTerm === '' || 
                             inscricao.includes(searchTerm) || 
                             nome.includes(searchTerm);
        
        // Mostra ou oculta a linha
        if (matchesStatus && matchesSearch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Mostra mensagem se não houver resultados
    showNoResultsMessage(visibleCount);
    
    // Atualiza contador de resultados
    updateResultsCounter(visibleCount);
}

// Função para mostrar mensagem de "nenhum resultado"
function showNoResultsMessage(count) {
    let noResultRow = resultsBody.querySelector('.no-result-row');
    
    if (count === 0) {
        if (!noResultRow) {
            noResultRow = document.createElement('tr');
            noResultRow.className = 'no-result-row';
            noResultRow.innerHTML = '<td colspan="6" class="text-center py-3">Nenhum resultado encontrado.</td>';
            resultsBody.appendChild(noResultRow);
        }
        noResultRow.style.display = '';
    } else {
        if (noResultRow) {
            noResultRow.style.display = 'none';
        }
    }
}

// Função para atualizar o contador de resultados
function updateResultsCounter(count) {
    if (resultsCounter) {
        const totalRows = resultsBody.querySelectorAll('tr:not(.no-result-row)').length;
        
        if (count === totalRows) {
            resultsCounter.innerHTML = `<span class="text-muted">Exibindo <strong>${count}</strong> ${count === 1 ? 'candidato' : 'candidatos'}</span>`;
        } else {
            resultsCounter.innerHTML = `<span class="text-primary">Encontrados <strong>${count}</strong> de <strong>${totalRows}</strong> ${totalRows === 1 ? 'candidato' : 'candidatos'}</span>`;
        }
    }
}

// Event listeners
filterStatus.addEventListener('change', applyFilters);
searchInput.addEventListener('input', applyFilters);

// Inicializa os filtros
document.addEventListener('DOMContentLoaded', applyFilters);