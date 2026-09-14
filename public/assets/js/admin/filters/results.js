document.addEventListener('DOMContentLoaded', function () {
    const filterSelect = document.getElementById('filter-status');
    const pcdSelect = document.getElementById('filter-pcd');
    const searchInput = document.getElementById('search');
    const rows = document.querySelectorAll('#results tr');

    function applyFilters() {
        const selectedStatus = filterSelect.value.toLowerCase();
        const selectedPCD = pcdSelect.value.toLowerCase();
        const searchTerm = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const pcd = row.getAttribute('data-pcd');
            const textContent = row.textContent.toLowerCase();

            const matchesStatus = selectedStatus === 'all' || status === selectedStatus;
            const matchesPCD = selectedPCD === 'all' || pcd === selectedPCD;
            const matchesSearch = searchTerm === '' || textContent.includes(searchTerm);

            row.style.display =
                matchesStatus && matchesPCD && matchesSearch
                    ? ''
                    : 'none';
        });
    }

    filterSelect.addEventListener('change', applyFilters);
    pcdSelect.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);
});