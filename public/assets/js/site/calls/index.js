document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".search-input").forEach((input) => {
        input.addEventListener("input", function () {
            let searchValue = this.value.toLowerCase().trim();
            let tableId = this.getAttribute("data-table");
            let tbody = document.querySelector(`#${tableId} tbody`);
            let noResultRow = tbody.querySelector(".no-result");
            if (noResultRow) noResultRow.remove();

            let rows = tbody.querySelectorAll("tr");

            let visibleCount = 0;

            rows.forEach((row) => {
                const cells = row.querySelectorAll("td");
                const ranking = cells[0]
                    ? cells[0].innerText.replace("º", "").trim()
                    : "";
                const inscriptionNumber = cells[1]
                    ? cells[1].innerText.trim()
                    : "";

                // Uma busca numérica pode ser classificação ou número de inscrição.
                if (/^\d+$/.test(searchValue)) {
                    if (ranking === searchValue || inscriptionNumber === searchValue) {
                        row.style.display = "";
                        visibleCount++;
                    } else {
                        row.style.display = "none";
                    }
                }
                // Busca textual normal
                else {
                    if (row.innerText.toLowerCase().includes(searchValue)) {
                        row.style.display = "";
                        visibleCount++;
                    } else {
                        row.style.display = "none";
                    }
                }
            });

            // Se nenhum resultado visível, adiciona linha com aviso
            if (visibleCount === 0) {
                let tr = document.createElement("tr");
                tr.classList.add("no-result");
                tr.innerHTML = `<td colspan="3" class="text-center text-muted">Nenhum resultado encontrado nesta chamada.</td>`;
                tbody.appendChild(tr);
            }
        });
    });
});
