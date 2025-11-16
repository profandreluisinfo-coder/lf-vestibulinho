document.addEventListener('DOMContentLoaded', function () {
    const filterSelect = document.getElementById('filter-status');
    const searchInput = document.getElementById('search');
    const rows = document.querySelectorAll('#results tr');

    function applyFilters() {
        const selectedStatus = filterSelect.value.toLowerCase();
        const searchTerm = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const textContent = row.textContent.toLowerCase();

            const matchesStatus = selectedStatus === 'all' || status === selectedStatus;
            const matchesSearch = searchTerm === '' || textContent.includes(searchTerm);

            if (matchesStatus && matchesSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Escuta ambos os eventos
    filterSelect.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);
});