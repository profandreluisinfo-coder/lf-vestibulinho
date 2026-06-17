$(document).ready(function () {

    function applyFilters() {

        let search = $("#search").val().toLowerCase();
        let selectedCategory = $("#filterCategory").val();

        let showPublished = $("#filterPublished").is(":checked");
        let showUnpublished = $("#filterUnpublished").is(":checked");

        $(".accordion-item").each(function () {

            let item = $(this);

            let status = item.data("status");
            let category = item.data("category");

            let question = item.find(".accordion-button").text().toLowerCase();

            let matchSearch = question.includes(search);

            let matchCategory =
                selectedCategory === "" ||
                category === selectedCategory;

            let matchStatus =
                (status == 1 && showPublished) ||
                (status == 0 && showUnpublished);

            if (matchSearch && matchCategory && matchStatus) {
                item.show();
            } else {
                item.hide();
            }

        });

    }

    // Pesquisa
    $("#search").on("keyup", function () {
        applyFilters();
    });

    // Checkboxes
    $("#filterPublished, #filterUnpublished").on("change", function () {
        applyFilters();
    });

    // Categoria
    $("#filterCategory").on("change", function () {
        applyFilters();
    });

});