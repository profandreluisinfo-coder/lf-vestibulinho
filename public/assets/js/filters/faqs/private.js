$(document).ready(function () {

    function applyFilters() {

        let search = $("#search").val().toLowerCase();
        let showPublished = $("#filterPublished").is(":checked");
        let showUnpublished = $("#filterUnpublished").is(":checked");

        $(".accordion-item").each(function () {

            let item = $(this);
            let status = item.data("status"); // 1 ou 0
            let question = item.find(".accordion-button").text().toLowerCase();

            let matchSearch = question.includes(search);

            let matchStatus =
                (status == 1 && showPublished) ||
                (status == 0 && showUnpublished);

            if (matchSearch && matchStatus) {
                item.show();
            } else {
                item.hide();
            }

        });

    }

    // Pesquisa digitando
    $("#search").on("keyup", function () {
        applyFilters();
    });

    // Mudança nos checkboxes
    $("#filterPublished, #filterUnpublished").on("change", function () {
        applyFilters();
    });

});