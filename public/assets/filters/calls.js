document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".search-input").forEach((input) => {
        input.addEventListener("keyup", function () {
            let searchValue = this.value.toLowerCase().trim();
            let tableId = this.getAttribute("data-table");
            let tbody = document.querySelector(`#${tableId} tbody`);
            let rows = tbody.querySelectorAll("tr");

            let visibleCount = 0;

            rows.forEach((row) => {
                let text = row.innerText.toLowerCase();
                let rankingCell = row.querySelector(
                    "td:first-child, th:first-child"
                );
                let ranking = rankingCell
                    ? rankingCell.innerText.replace("º", "").trim()
                    : "";

                // Se o valor digitado for só número, filtra pelo ranking
                if (/^\d+$/.test(searchValue)) {
                    if (ranking === searchValue) {
                        row.style.display = "";
                        visibleCount++;
                    } else {
                        row.style.display = "none";
                    }
                }
                // Busca textual normal
                else {
                    if (text.includes(searchValue)) {
                        row.style.display = "";
                        visibleCount++;
                    } else {
                        row.style.display = "none";
                    }
                }
            });

            // Remove mensagens antigas antes de adicionar nova
            let noResultRow = tbody.querySelector(".no-result");
            if (noResultRow) noResultRow.remove();

            // Se nenhum resultado visível, adiciona linha com aviso
            if (visibleCount === 0) {
                let tr = document.createElement("tr");
                tr.classList.add("no-result");
                tr.innerHTML = `<td colspan="5" class="text-center text-muted">Nenhum resultado encontrado.</td>`;
                tbody.appendChild(tr);
            }
        });
    });
});
