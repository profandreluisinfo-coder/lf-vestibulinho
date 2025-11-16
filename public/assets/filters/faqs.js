$(document).ready(function () {
    function normalize(str) {
        return str
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase();
    }

    function filterFaqs() {
        var value = normalize($("#search").val());

        // pega checkboxes
        var showPublished = $("#filterPublished").is(":checked");
        var showUnpublished = $("#filterUnpublished").is(":checked");

        $(".accordion-item").each(function () {
            let $btn = $(this).find(".accordion-header button");
            let originalText = $btn.data("original") || $btn.text();
            let normalizedText = normalize(originalText);

            // restaurar antes de highlight
            $btn.html(originalText);
            $btn.data("original", originalText);

            // status da FAQ pelo badge
            let faqStatus = $(this).find(".badge").text().trim() === "Publicado" ? "1" : "0";

            // regras de filtro
            let matchesText = value === "" || normalizedText.indexOf(value) > -1;
            let matchesStatus =
                (faqStatus === "1" && showPublished) ||
                (faqStatus === "0" && showUnpublished);

            if (matchesText && matchesStatus) {
                if (value !== "") {
                    let regex = new RegExp("(" + value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")",
                        "gi");
                    let highlighted = originalText.replace(regex, "<mark>$1</mark>");
                    $btn.html(highlighted);
                }
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $("#search").on("keyup", filterFaqs);
    $("#filterPublished, #filterUnpublished").on("change", filterFaqs);
});